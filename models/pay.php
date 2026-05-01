<?php
/**
 * PaymentModel — encapsulates payment lifecycle + related booking/session updates.
 * Matches the current payController flow.
 *
 * Tables touched:
 *  - payments(booking_id, user_id, amount, method, status)
 *  - bookings(id, user_id, timetable_id, status_id, booking_date, session_id?)
 *  - sessions(id, sport_id, coach_id, timetable_id, session_day, start_time, end_time)
 *  - timetable/timetables (joined for sport/coach/time info) — adjust table name if needed
 *  - sports
 */

declare(strict_types=1);

require_once __DIR__ . '/../core/db.php'; // Database::connect()

class PaymentModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /** Factory using your shared DB connection */
    public static function fromDefault(): self
    {
        return new self(Database::connect());
    }

    /** Ensure the booking exists AND belongs to the user */
    public function verifyBookingOwnership(int $bookingId, int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT 1 FROM bookings WHERE id = :bid AND user_id = :uid LIMIT 1");
        $stmt->execute([':bid' => $bookingId, ':uid' => $userId]);
        return (bool)$stmt->fetchColumn();
    }

    /** Pay later: create an 'unpaid' record if one doesn't exist */
    public function ensureUnpaidRecord(int $bookingId, int $userId): void
    {
        $this->db->beginTransaction();
        try {
            $check = $this->db->prepare("SELECT id FROM payments WHERE booking_id = :bid LIMIT 1");
            $check->execute([':bid' => $bookingId]);
            $row = $check->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                $ins = $this->db->prepare("INSERT INTO payments (booking_id, user_id, status) VALUES (:bid, :uid, 'unpaid')");
                $ins->execute([':bid' => $bookingId, ':uid' => $userId]);
            }
            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $e;
        }
    }

    /** Pay now: create or reset to 'initiated' */
    public function startPayment(int $bookingId, int $userId): void
    {
        $this->db->beginTransaction();
        try {
            $sel = $this->db->prepare("SELECT id FROM payments WHERE booking_id = :bid LIMIT 1");
            $sel->execute([':bid' => $bookingId]);
            $exists = $sel->fetch(PDO::FETCH_ASSOC);

            if ($exists) {
                $upd = $this->db->prepare("UPDATE payments SET status = 'initiated', method = NULL, amount = NULL WHERE id = :id");
                $upd->execute([':id' => $exists['id']]);
            } else {
                $ins = $this->db->prepare("INSERT INTO payments (booking_id, user_id, status) VALUES (:bid, :uid, 'initiated')");
                $ins->execute([':bid' => $bookingId, ':uid' => $userId]);
            }

            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $e;
        }
    }

    /** Complete payment: upsert payment, confirm booking, create session, link booking */
    public function completePayment(int $bookingId, int $userId, string $method, float $amount): void
    {
        $this->db->beginTransaction();
        try {
            $info = $this->getBookingSessionInfo($bookingId);
            if (!$info) {
                throw new RuntimeException('Booking data missing.');
            }

            // Upsert payment as Success
            $check = $this->db->prepare("SELECT id FROM payments WHERE booking_id = :bid LIMIT 1");
            $check->execute([':bid' => $bookingId]);
            if ($check->fetch()) {
                $upd = $this->db->prepare("UPDATE payments SET amount = :amt, method = :mtd, status = 'Success' WHERE booking_id = :bid AND user_id = :uid");
                $upd->execute([':amt' => $amount, ':mtd' => $method, ':bid' => $bookingId, ':uid' => $userId]);
            } else {
                $ins = $this->db->prepare("INSERT INTO payments (booking_id, user_id, amount, method, status) VALUES (:bid, :uid, :amt, :mtd, 'Success')");
                $ins->execute([':bid' => $bookingId, ':uid' => $userId, ':amt' => $amount, ':mtd' => $method]);
            }

            // Confirm booking (status_id = 2 as in your controller)
            $this->confirmBooking($bookingId);

            // Create session row for this booking
            $sessionId = $this->createSessionForBooking($info);

            // Link booking to session (if the column exists)
            $this->linkBookingToSession($bookingId, $sessionId);

            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $e;
        }
    }

    /** Internal: fetch sport/coach/timetable/booking time details for a booking */
    private function getBookingSessionInfo(int $bookingId): ?array
    {
        // NOTE: If your table is named `timetables`, change JOIN timetable t -> JOIN timetables t
        $sql = "SELECT
                    s.id AS sport_id,
                    t.id AS timetable_id,
                    t.coach_id,
                    b.booking_date,
                    t.start_time,
                    t.end_time
                FROM bookings b
                JOIN timetable t ON t.id = b.timetable_id
                JOIN sports s    ON s.id = t.sport_id
                WHERE b.id = :bid
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':bid' => $bookingId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Internal: set bookings.status_id to Confirmed (2) */
    private function confirmBooking(int $bookingId): void
    {
        $upd = $this->db->prepare("UPDATE bookings SET status_id = 2 WHERE id = :bid");
        $upd->execute([':bid' => $bookingId]);
    }

    /** Internal: insert a session from booking info and return its id */
    private function createSessionForBooking(array $info): int
    {
        $sessionDay = date('l', strtotime((string)$info['booking_date']));
        $ins = $this->db->prepare
        ("INSERT INTO sessions (sport_id, coach_id, timetable_id, session_day, start_time, end_time)
        VALUES (:sid, :cid, :tid, :sd, :st, :et)
        RETURNING id");
        $ins->execute([
            ':sid' => $info['sport_id'],
            ':cid' => $info['coach_id'],
            ':tid' => $info['timetable_id'],
            ':sd'  => $sessionDay,
            ':st'  => $info['start_time'],
            ':et'  => $info['end_time'],
        ]);
        $sessionId = (int)$ins->fetchColumn();
        return $sessionId;
    }

    /** Internal: link bookings.session_id if column exists (ignore failure) */
    private function linkBookingToSession(int $bookingId, int $sessionId): void
    {
        try {
            $upd = $this->db->prepare("UPDATE bookings SET session_id = :sid WHERE id = :bid");
            $upd->execute([':sid' => $sessionId, ':bid' => $bookingId]);
        } catch (Throwable $ignored) {
            // Column may not exist yet; safe to ignore
        }
    }
}
