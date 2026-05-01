<?php
/**
 * BookingsModel — wraps booking-related queries and rules used by bookingController.
 *
 * DB:
 *  - bookings(id, user_id, timetable_id, status_id, booking_date, session_id?)
 *  - booking_status(id, status_name)
 *  - payments(id, booking_id, user_id, amount, method, status)
 *  - timetable(id, sport_id, coach_id, day_of_week, start_time, end_time, created_at)
 *  - sports(id, sport_name)
 *  - users(id, full_name)
 *  - sessions(id, sport_id, coach_id, timetable_id, session_day, start_time, end_time, created_at)
 */

declare(strict_types=1);

require_once __DIR__ . '/../core/db.php';

class BookingsModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function fromDefault(): self
    {
        return new self(Database::connect());
    }

    /* -------------------- Helpers -------------------- */
    public function ensureStatus(string $name): int
    {
        $find = $this->db->prepare("SELECT id FROM booking_status WHERE LOWER(status_name) = LOWER(:n) LIMIT 1");
        $find->execute([':n' => $name]);
        $row = $find->fetch(PDO::FETCH_ASSOC);
        if ($row) return (int)$row['id'];

        $ins = $this->db->prepare("INSERT INTO booking_status (status_name) VALUES (:n) RETURNING id");
        $ins->execute([':n' => $name]);
        return (int)$ins->fetchColumn();
    }

    public function verifyOwnership(int $bookingId, int $userId): bool
    {
        $st = $this->db->prepare("SELECT 1 FROM bookings WHERE id = :id AND user_id = :uid LIMIT 1");
        $st->execute([':id'=>$bookingId, ':uid'=>$userId]);
        return (bool)$st->fetchColumn();
    }

    public function isPaid(int $bookingId): bool
    {
        $st = $this->db->prepare("SELECT 1 FROM payments WHERE booking_id = :id AND status = 'Success' LIMIT 1");
        $st->execute([':id'=>$bookingId]);
        return (bool)$st->fetchColumn();
    }

    public function getSlotBookingCount(int $timetableId, string $date): int
    {
        $sql = "SELECT COUNT(*)
                FROM bookings b
                LEFT JOIN booking_status bs ON bs.id = b.status_id
                WHERE b.timetable_id = :tid
                  AND b.booking_date = :bd
                  AND (bs.id IS NULL OR LOWER(bs.status_name) NOT LIKE 'cancel%')";
        $st = $this->db->prepare($sql);
        $st->execute([':tid'=>$timetableId, ':bd'=>$date]);
        return (int)$st->fetchColumn();
    }

    /* -------------------- Queries for views -------------------- */
    public function getMyBookingsWithStatus(int $userId): array
    {
        $sql = "
        SELECT
            b.id                         AS booking_id,
            b.user_id,
            u.full_name                  AS user_name,
            sp.id                        AS sport_id,
            sp.sport_name                AS sport_name,
            t.coach_id,
            coach.full_name              AS coach_name,
            t.day_of_week,
            t.start_time,
            t.end_time,

            CASE WHEN EXISTS (
                SELECT 1 FROM payments p
                WHERE p.booking_id = b.id AND p.status = 'Success'
            ) THEN 1 ELSE 0 END          AS is_paid,

            CASE
                WHEN EXISTS (SELECT 1 FROM payments p WHERE p.booking_id = b.id AND p.status = 'Success')
                    THEN 'Paid'
                ELSE 'Pending'
            END                           AS status_text,

            CASE
                WHEN EXISTS (SELECT 1 FROM payments p WHERE p.booking_id = b.id AND p.status = 'Success')
                 AND EXISTS (
                    SELECT 1 FROM sessions s
                    WHERE s.timetable_id = t.id
                      AND (NOW() - s.created_at) <= INTERVAL '10 days'
                 )
                THEN 1 ELSE 0
            END                           AS is_refundable

        FROM bookings b
        JOIN users     u      ON u.id = b.user_id
        JOIN timetable t      ON t.id = b.timetable_id
        JOIN sports    sp     ON sp.id = t.sport_id
        LEFT JOIN users coach ON coach.id = t.coach_id
        LEFT JOIN booking_status bs ON bs.id = b.status_id

        WHERE b.user_id = :uid
          AND NOT (
            LOWER(COALESCE(bs.status_name, '')) LIKE 'cancel%'
            OR LOWER(COALESCE(bs.status_name, '')) LIKE 'refund%'
          )

        ORDER BY
            CASE t.day_of_week
                WHEN 'Monday' THEN 1 WHEN 'Tuesday' THEN 2 WHEN 'Wednesday' THEN 3
                WHEN 'Thursday' THEN 4 WHEN 'Friday' THEN 5 WHEN 'Saturday' THEN 6
                WHEN 'Sunday' THEN 7 ELSE 8
            END,
            t.start_time ASC";

        $st = $this->db->prepare($sql);
        $st->execute([':uid' => $userId]);
        return $st->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getSlotsForSport(string $sportName): array
    {
        if ($sportName === '') return [];

        $s = $this->db->prepare("SELECT id FROM sports WHERE sport_name = :n LIMIT 1");
        $s->execute([':n' => $sportName]);
        $row = $s->fetch(PDO::FETCH_ASSOC);
        if (!$row) return [];
        $sportId = (int)$row['id'];

        $tt = $this->db->prepare("SELECT t.id, t.day_of_week, t.start_time, t.end_time, u.full_name AS coach_name
                                   FROM timetable t
                                   LEFT JOIN users u ON u.id = t.coach_id
                                   WHERE t.sport_id = :sid
                                   ORDER BY CASE t.day_of_week
                                        WHEN 'Monday' THEN 1 WHEN 'Tuesday' THEN 2 WHEN 'Wednesday' THEN 3
                                        WHEN 'Thursday' THEN 4 WHEN 'Friday' THEN 5 WHEN 'Saturday' THEN 6
                                        WHEN 'Sunday' THEN 7 ELSE 8
                                   END, t.start_time");
        $tt->execute([':sid' => $sportId]);
        $rows = $tt->fetchAll(PDO::FETCH_ASSOC);

        $slots = [];
        foreach ($rows as $r) {
            $label = $r['day_of_week'] . ' ' .
                     date('H:i', strtotime((string)$r['start_time'])) . ' - ' .
                     date('H:i', strtotime((string)$r['end_time']));
            if (!empty($r['coach_name'])) $label .= ' (' . $r['coach_name'] . ')';
            $slots[] = ['id' => (int)$r['id'], 'label' => $label];
        }
        return $slots;
    }

    /* -------------------- Mutations -------------------- */
    public function createBooking(int $userId, string $sportName, string $bookingDate, int $timetableId): int
    {
        // Validate sport & slot
        $sportStmt = $this->db->prepare("SELECT id FROM sports WHERE sport_name = :n LIMIT 1");
        $sportStmt->execute([':n' => $sportName]);
        $sport = $sportStmt->fetch(PDO::FETCH_ASSOC);
        if (!$sport) throw new RuntimeException('Unknown sport.');
        $sportId = (int)$sport['id'];

        $tt = $this->db->prepare("SELECT id FROM timetable WHERE id = :id AND sport_id = :sid LIMIT 1");
        $tt->execute([':id' => $timetableId, ':sid' => $sportId]);
        $ttRow = $tt->fetch(PDO::FETCH_ASSOC);
        if (!$ttRow) throw new RuntimeException('Invalid time slot for the selected sport.');

        // Capacity rule: max 10
        if ($this->getSlotBookingCount($timetableId, $bookingDate) >= 10) {
            throw new RuntimeException('This session is fully booked (10 users max). Please pick another slot.');
        }

        $this->db->beginTransaction();
        try {
            $pendingId = $this->ensureStatus('Pending');
            $ins = $this->db->prepare("INSERT INTO bookings (user_id, timetable_id, status_id, booking_date)
                                       VALUES (:uid, :tid, :sid, :bd)
                                       RETURNING id");
            $ins->execute([
                ':uid' => $userId,
                ':tid' => $timetableId,
                ':sid' => $pendingId,
                ':bd'  => $bookingDate,
            ]);
            $bookingId = (int)$ins->fetchColumn();
            $this->db->commit();
            return $bookingId;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $e;
        }
    }

    public function cancelIfUnpaid(int $bookingId, int $userId): void
    {
        if (!$this->verifyOwnership($bookingId, $userId)) {
            throw new RuntimeException('Booking not found.');
        }
        if ($this->isPaid($bookingId)) {
            throw new RuntimeException('This booking is already paid and cannot be cancelled here.');
        }
        $cancelId = $this->ensureStatus('Cancelled');
        $upd = $this->db->prepare("UPDATE bookings SET status_id = :sid WHERE id = :id");
        $upd->execute([':sid'=>$cancelId, ':id'=>$bookingId]);
    }

    public function canStartPayment(int $bookingId, int $userId): void
    {
        if (!$this->verifyOwnership($bookingId, $userId)) {
            throw new RuntimeException('Booking not found.');
        }
        if ($this->isPaid($bookingId)) {
            throw new RuntimeException('This booking is already paid.');
        }
        // ok if no exception
    }

    public function requestRefund(int $bookingId, int $userId): void
    {
        // ownership
        $own = $this->db->prepare("SELECT b.id, b.timetable_id FROM bookings b WHERE b.id = :id AND b.user_id = :uid");
        $own->execute([':id'=>$bookingId, ':uid'=>$userId]);
        $row = $own->fetch(PDO::FETCH_ASSOC);
        if (!$row) throw new RuntimeException('Booking not found.');
        $timetableId = (int)$row['timetable_id'];

        // must be paid
        $p = $this->db->prepare("SELECT id FROM payments WHERE booking_id = :id AND status = 'Success' ORDER BY id DESC LIMIT 1");
        $p->execute([':id'=>$bookingId]);
        $payment = $p->fetch(PDO::FETCH_ASSOC);
        if (!$payment) throw new RuntimeException('This booking is not paid or already refunded.');

        // refund window (10 days since session creation or timetable created_at)
        $q = $this->db->prepare("SELECT COALESCE(
            (SELECT s.created_at FROM sessions s WHERE s.timetable_id = :tid ORDER BY s.created_at DESC LIMIT 1),
            (SELECT t.created_at FROM timetable t WHERE t.id = :tid2 LIMIT 1)
        ) AS created_at");
        $q->execute([':tid' => $timetableId, ':tid2' => $timetableId]);
        $createdAt = $q->fetchColumn();
        if (!$createdAt) throw new RuntimeException('Refund window could not be determined for this session.');

        $win = $this->db->prepare("SELECT CASE WHEN (NOW() - :c)::interval <= INTERVAL '10 days' THEN 1 ELSE 0 END");
        $win->execute([':c' => $createdAt]);
        $ok = (int)$win->fetchColumn();
        if (!$ok) throw new RuntimeException('Refund period (10 days) has expired.');

        // perform refund
        $this->db->beginTransaction();
        try {
            $updPay = $this->db->prepare("UPDATE payments SET status = 'Refunded' WHERE id = :pid");
            $updPay->execute([':pid' => (int)$payment['id']]);

            $refundedId = $this->ensureStatus('Refunded');
            $updBk = $this->db->prepare("UPDATE bookings SET status_id = :sid WHERE id = :bid");
            $updBk->execute([':sid' => $refundedId, ':bid' => $bookingId]);

            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $e;
        }
    }
}