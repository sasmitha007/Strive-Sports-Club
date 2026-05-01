<?php
include(__DIR__ . '/../partials/header.php');
require_once __DIR__ . '/../core/db.php';

requireLogin();
$db = Database::connect();

// Resolve booking_id (GET wins; else from session set by booking flow)
$bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : (int)($_SESSION['pay_booking_id'] ?? 0);
if ($bookingId <= 0) { header('Location: ./dashboard.php'); exit; }

// Load booking summary, ensure it belongs to logged-in user
$stmt = $db->prepare("
  SELECT b.id AS booking_id, b.booking_date, t.day_of_week, t.start_time, t.end_time,
         s.sport_name, u.full_name AS coach_name, b.status_id
  FROM bookings b
  JOIN timetable t ON t.id = b.timetable_id
  JOIN sports s    ON s.id = t.sport_id
  LEFT JOIN users u ON u.id = t.coach_id
  WHERE b.id = :bid AND b.user_id = :uid
  LIMIT 1
");
$stmt->execute(['bid' => $bookingId, 'uid' => $_SESSION['user']['id']]);
$bk = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$bk) { header('Location: ./dashboard.php'); exit; }

$slotLabel = sprintf(
  '%s %s - %s',
  $bk['day_of_week'],
  date('H:i', strtotime($bk['start_time'])),
  date('H:i', strtotime($bk['end_time']))
);
?>

<section class="max-w-2xl mx-auto px-4 py-10 min-h-[60vh]">
  <h1 class="text-3xl font-bold mb-6 text-center">Payment</h1>

  <?php if (!empty($_SESSION['payment_error'])): ?>
    <div class="mb-4 p-3 bg-red-900/40 border border-red-600 text-red-300 rounded">
      <?= htmlspecialchars($_SESSION['payment_error']) ?>
    </div>
    <?php unset($_SESSION['payment_error']); ?>
  <?php endif; ?>

  <div class="bg-gray-800 p-6 rounded-lg shadow-md space-y-4">
    <div class="flex items-center justify-between">
      <span class="text-gray-300">Sport</span>
      <span class="font-semibold"><?= htmlspecialchars($bk['sport_name']) ?></span>
    </div>
    <div class="flex items-center justify-between">
      <span class="text-gray-300">Date</span>
      <span class="font-semibold"><?= htmlspecialchars(date('Y-m-d', strtotime($bk['booking_date']))) ?></span>
    </div>
    <div class="flex items-center justify-between">
      <span class="text-gray-300">Time</span>
      <span class="font-semibold"><?= htmlspecialchars($slotLabel) ?></span>
    </div>
    <?php if (!empty($bk['coach_name'])): ?>
    <div class="flex items-center justify-between">
      <span class="text-gray-300">Coach</span>
      <span class="font-semibold"><?= htmlspecialchars($bk['coach_name']) ?></span>
    </div>
    <?php endif; ?>

    <div class="pt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
      <!-- Pay Now -->
      <form action="../controllers/paycontroller.php" method="POST" class="contents">
        <input type="hidden" name="action" value="pay_now">
        <input type="hidden" name="booking_id" value="<?= (int)$bookingId ?>">
        <button
          type="submit"
          class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-6 py-3 rounded-full shadow transition">
          Pay now (go to gateway)
        </button>
      </form>

      <!-- Pay Later -->
      <form action="../controllers/paycontroller.php" method="POST" class="contents">
        <input type="hidden" name="action" value="pay_later">
        <input type="hidden" name="booking_id" value="<?= (int)$bookingId ?>">
        <button
          type="submit"
          class="w-full bg-gray-700 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-full shadow transition">
          Pay later
        </button>
      </form>
    </div>
  </div>
</section>

<?php include(__DIR__ . '/../partials/footer.php'); ?>
