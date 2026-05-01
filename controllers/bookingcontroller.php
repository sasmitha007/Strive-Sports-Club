<?php
//declare(strict_types=1);

require_once __DIR__ . '/../core/sessions.php';
require_once __DIR__ . '/../core/db.php';

requireLogin();
$db = Database::connect();

/** Ensure a status exists in booking_status(status_name) and return its id. */
function ensureStatus(string $name): int {
    $db = Database::connect();
    $find = $db->prepare("SELECT id FROM booking_status WHERE LOWER(status_name) = LOWER(:n) LIMIT 1");
    $find->execute(['n' => $name]);
    $row = $find->fetch(PDO::FETCH_ASSOC);
    if ($row) return (int)$row['id'];

    // Postgres-safe: RETURNING id (instead of lastInsertId)
    $ins = $db->prepare("INSERT INTO booking_status (status_name) VALUES (:n) RETURNING id");
    $ins->execute(['n' => $name]);
    return (int)$ins->fetchColumn();
}

/** Count seats using only Confirmed bookings (status_id = Confirmed). */
function getConfirmedSeatCount(int $timetableId, string $date): int {
    $db = Database::connect();
    $confirmedId = ensureStatus('Confirmed'); // resilient to id changes
    $st = $db->prepare("
        SELECT COUNT(*) FROM bookings
        WHERE timetable_id = :tid
          AND booking_date = :bd
          AND status_id = :cid
    ");
    $st->execute(['tid' => $timetableId, 'bd' => $date, 'cid' => $confirmedId]);
    return (int)$st->fetchColumn();
}

/** ---------- rows for My Bookings table (hide cancelled; compute refund eligibility) ---------- */
function getMyBookingsWithStatus(int $userId): array {
    $db = Database::connect();

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
      -- Hide cancelled and refunded rows:
      AND NOT (
        LOWER(COALESCE(bs.status_name, '')) LIKE 'cancel%'
        OR LOWER(COALESCE(bs.status_name, '')) LIKE 'refund%'
      )

    ORDER BY
        CASE t.day_of_week
            WHEN 'Monday' THEN 1 WHEN 'Tuesday' THEN 2 WHEN 'Wednesday' THEN 3
            WHEN 'Thursday' THEN 4 WHEN 'Friday' THEN 5 WHEN 'Saturday' THEN 6
            WHEN 'Sunday'  THEN 7 ELSE 8
        END,
        t.start_time ASC
    ";

    $st = $db->prepare($sql);
    $st->execute(['uid' => $userId]);
    return $st->fetchAll(PDO::FETCH_ASSOC);
}

/** GET: Return all timetable rows for a sport (day + time) */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['slots'])) {
    header('Content-Type: application/json');
    $sport = trim($_GET['sport'] ?? '');
    if ($sport === '') { echo json_encode(['ok'=>true,'slots'=>[]]); exit; }

    // sport name -> id
    $s = $db->prepare("SELECT id FROM sports WHERE sport_name = :n LIMIT 1");
    $s->execute(['n' => $sport]);
    $row = $s->fetch(PDO::FETCH_ASSOC);
    if (!$row) { echo json_encode(['ok'=>true,'slots'=>[]]); exit; }

    $sportId = (int)$row['id'];

    $tt = $db->prepare("
      SELECT t.id, t.day_of_week, t.start_time, t.end_time, u.full_name AS coach_name
      FROM timetable t
      LEFT JOIN users u ON u.id = t.coach_id
      WHERE t.sport_id = :sid
      ORDER BY CASE t.day_of_week
                 WHEN 'Monday' THEN 1 WHEN 'Tuesday' THEN 2 WHEN 'Wednesday' THEN 3
                 WHEN 'Thursday' THEN 4 WHEN 'Friday' THEN 5 WHEN 'Saturday' THEN 6
                 WHEN 'Sunday' THEN 7 ELSE 8
               END, t.start_time
    ");
    $tt->execute(['sid' => $sportId]);
    $rows = $tt->fetchAll(PDO::FETCH_ASSOC);

    $slots = [];
    foreach ($rows as $r) {
        $label = $r['day_of_week'] . ' ' .
                 date('H:i', strtotime($r['start_time'])) . ' - ' .
                 date('H:i', strtotime($r['end_time']));
        if (!empty($r['coach_name'])) $label .= ' (' . $r['coach_name'] . ')';
        $slots[] = ['id' => (int)$r['id'], 'label' => $label];
    }

    echo json_encode(['ok'=>true, 'slots'=>$slots]);
    exit;
}

/** POST: create a booking (Pending) */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    $userId      = (int)($_SESSION['user']['id'] ?? 0);
    $sportName   = trim($_POST['sport'] ?? '');
    $bookingDate = trim($_POST['date'] ?? '');
    $timetableId = (int)($_POST['timetable_id'] ?? 0);

    if (!$userId || $sportName === '' || $bookingDate === '' || !$timetableId) {
        $_SESSION['booking_error'] = 'Missing required fields.';
        header('Location: ../pages/book.php'); exit;
    }
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $bookingDate)) {
        $_SESSION['booking_error'] = 'Invalid date.';
        header('Location: ../pages/book.php'); exit;
    }

    // Validate sport & timetable
    $sportStmt = $db->prepare("SELECT id FROM sports WHERE sport_name = :n LIMIT 1");
    $sportStmt->execute(['n' => $sportName]);
    $sport = $sportStmt->fetch(PDO::FETCH_ASSOC);
    if (!$sport) { $_SESSION['booking_error'] = 'Unknown sport.'; header('Location: ../pages/book.php'); exit; }
    $sportId = (int)$sport['id'];

    $tt = $db->prepare("SELECT id FROM timetable WHERE id = :id AND sport_id = :sid LIMIT 1");
    $tt->execute(['id' => $timetableId, 'sid' => $sportId]);
    $ttRow = $tt->fetch(PDO::FETCH_ASSOC);
    if (!$ttRow) { $_SESSION['booking_error'] = 'Invalid time slot for the selected sport.'; header('Location: ../pages/book.php'); exit; }

    // Ensure status ids (used for duplicate checks and insert)
    $pendingId   = ensureStatus('Pending');
    $confirmedId = ensureStatus('Confirmed');

    // SOFT GATE (UX): if already 10 confirmed, tell the user early
    if (getConfirmedSeatCount($timetableId, $bookingDate) >= 10) {
        $_SESSION['booking_error'] = 'This session is fully booked (10 users max). Please pick another slot.';
        header('Location: ../pages/book.php'); exit;
    }

    // Prevent duplicate booking of the same slot by the same user (Pending or Confirmed)
    $dup = $db->prepare("
      SELECT 1
      FROM bookings
      WHERE user_id = :uid
        AND timetable_id = :tid
        AND booking_date = :bd
        AND status_id IN (:p, :c)
      LIMIT 1
    ");
    $dup->execute(['uid' => $userId, 'tid' => $timetableId, 'bd' => $bookingDate, 'p' => $pendingId, 'c' => $confirmedId]);
    if ($dup->fetch()) {
        $_SESSION['booking_error'] = 'You already have a booking for this slot.';
        header('Location: ../pages/book.php'); exit;
    }

    try {
        $db->beginTransaction();

        // Re-check duplicate inside the txn to close race windows
        $dup->execute(['uid' => $userId, 'tid' => $timetableId, 'bd' => $bookingDate, 'p' => $pendingId, 'c' => $confirmedId]);
        if ($dup->fetch()) {
            $db->rollBack();
            $_SESSION['booking_error'] = 'You already have a booking for this slot.';
            header('Location: ../pages/book.php'); exit;
        }

        // RACE-PROOF capacity re-check inside the transaction
        if (getConfirmedSeatCount($timetableId, $bookingDate) >= 10) {
            $db->rollBack();
            $_SESSION['booking_error'] = 'This session just filled up.';
            header('Location: ../pages/book.php'); exit;
        }

        $ins = $db->prepare("
          INSERT INTO bookings (user_id, timetable_id, status_id, booking_date)
          VALUES (:uid, :tid, :sid, :bd)
          RETURNING id
        ");
        $ins->execute([
            'uid' => $userId,
            'tid' => $timetableId,
            'sid' => $pendingId,
            'bd'  => $bookingDate
        ]);

        $bookingId = (int)$ins->fetchColumn();
        $db->commit();

        $_SESSION['pay_booking_id'] = $bookingId;
        header('Location: ../pages/paychoice.php?booking_id='.$bookingId);
        exit;
    } catch (Throwable $e) {
        if ($db->inTransaction()) $db->rollBack();
        error_log('Booking insert failed: '.$e->getMessage());
        $_SESSION['booking_error'] = 'Could not create the booking. Please try again.';
        header('Location: ../pages/book.php'); exit;
    }
}

/** Cancel (unpaid) */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking'])) {
    $userId = (int)($_SESSION['user']['id'] ?? 0);
    $bid    = (int)($_POST['booking_id'] ?? 0);

    // ownership
    $own = $db->prepare("SELECT id FROM bookings WHERE id = :id AND user_id = :uid");
    $own->execute(['id'=>$bid,'uid'=>$userId]);
    if (!$own->fetch()) {
        $_SESSION['booking_error'] = 'Booking not found.';
        header('Location: ../pages/user/my_bookings.php'); exit;
    }

    // already paid?
    $paid = $db->prepare("SELECT 1 FROM payments WHERE booking_id = :id AND status = 'Success' LIMIT 1");
    $paid->execute(['id'=>$bid]);
    if ($paid->fetch()) {
        $_SESSION['booking_error'] = 'This booking is already paid and cannot be cancelled here.';
        header('Location: ../pages/user/my_bookings.php'); exit;
    }

    $cancelId = ensureStatus('Cancelled');
    $upd = $db->prepare("UPDATE bookings SET status_id = :sid WHERE id = :id");
    $upd->execute(['sid'=>$cancelId, 'id'=>$bid]);

    $_SESSION['booking_success'] = 'Booking cancelled.';
    header('Location: ../pages/user/my_bookings.php'); exit;
}

/** Start payment */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_payment'])) {
    $userId = (int)($_SESSION['user']['id'] ?? 0);
    $bid    = (int)($_POST['booking_id'] ?? 0);

    // ownership
    $own = $db->prepare("SELECT id FROM bookings WHERE id = :id AND user_id = :uid");
    $own->execute(['id'=>$bid,'uid'=>$userId]);
    if (!$own->fetch()) {
        $_SESSION['booking_error'] = 'Booking not found.';
        header('Location: ../pages/user/my_bookings.php'); exit;
    }

    // prevent duplicate payment
    $paid = $db->prepare("SELECT 1 FROM payments WHERE booking_id = :id AND status = 'Success' LIMIT 1");
    $paid->execute(['id'=>$bid]);
    if ($paid->fetch()) {
        $_SESSION['booking_error'] = 'This booking is already paid.';
        header('Location: ../pages/user/my_bookings.php'); exit;
    }

    $_SESSION['pay_booking_id'] = $bid;
    header('Location: ../pages/payment.php?booking_id='.$bid);
    exit;
}

/** ---------- Refund (paid bookings, within 10 days of sessions.created_at) ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_refund'])) {
    $userId = (int)($_SESSION['user']['id'] ?? 0);
    $bid    = (int)($_POST['booking_id'] ?? 0);

    // ownership
    $own = $db->prepare("SELECT b.id, b.timetable_id FROM bookings b WHERE b.id = :id AND b.user_id = :uid");
    $own->execute(['id'=>$bid,'uid'=>$userId]);
    $row = $own->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        $_SESSION['booking_error'] = 'Booking not found.';
        header('Location: ../pages/user/my_bookings.php'); exit;
    }
    $timetableId = (int)$row['timetable_id'];

    // must be paid (and not already refunded)
    $p = $db->prepare("SELECT id FROM payments WHERE booking_id = :id AND status = 'Success' ORDER BY id DESC LIMIT 1");
    $p->execute(['id'=>$bid]);
    $payment = $p->fetch(PDO::FETCH_ASSOC);
    if (!$payment) {
        $_SESSION['booking_error'] = 'This booking is not paid or already refunded.';
        header('Location: ../pages/user/my_bookings.php'); exit;
    }

    // refund window: within 10 days of session creation
    // primary: sessions.created_at (sessions.timetable_id = timetable.id)
    // fallback: timetable.created_at if sessions row does not exist
    $q = $db->prepare("
        SELECT
            COALESCE(
                (SELECT s.created_at FROM sessions s WHERE s.timetable_id = :tid ORDER BY s.created_at DESC LIMIT 1),
                (SELECT t.created_at FROM timetable t WHERE t.id = :tid2 LIMIT 1)
            ) AS created_at
    ");
    $q->execute(['tid' => $timetableId, 'tid2' => $timetableId]);
    $createdAt = $q->fetchColumn();

    if (!$createdAt) {
        $_SESSION['booking_error'] = 'Refund window could not be determined for this session.';
        header('Location: ../pages/user/my_bookings.php'); exit;
    }

    // Postgres: check interval
    $win = $db->prepare("SELECT CASE WHEN (NOW() - :c)::interval <= INTERVAL '10 days' THEN 1 ELSE 0 END");
    $win->execute(['c' => $createdAt]);
    $ok = (int)$win->fetchColumn();

    if (!$ok) {
        $_SESSION['booking_error'] = 'Refund period (10 days) has expired.';
        header('Location: ../pages/user/my_bookings.php'); exit;
    }

    try {
        $db->beginTransaction();

        // Mark payment as Refunded (you can also insert a separate refund row if you prefer)
        $updPay = $db->prepare("UPDATE payments SET status = 'Refunded' WHERE id = :pid");
        $updPay->execute(['pid' => (int)$payment['id']]);

        // Optionally mark booking status as Refunded (keeps states explicit)
        $refundedId = ensureStatus('Refunded');
        $updBk = $db->prepare("UPDATE bookings SET status_id = :sid WHERE id = :bid");
        $updBk->execute(['sid' => $refundedId, 'bid' => $bid]);

        $db->commit();
        $_SESSION['booking_success'] = 'Refund processed successfully.';
    } catch (Throwable $e) {
        if ($db->inTransaction()) $db->rollBack();
        error_log('Refund failed: '.$e->getMessage());
        $_SESSION['booking_error'] = 'Could not process refund. Please try again.';
    }

    header('Location: ../pages/user/my_bookings.php'); exit;
}

/** Fallback only when script is the direct entry point (not when included) */
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    header('Location: ../pages/book.php');
    exit;
}
