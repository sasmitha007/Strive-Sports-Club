<?php
include(__DIR__ . '/../partials/header.php');
require_once __DIR__ . '/../core/db.php';
requireLogin();

$db = Database::connect();
$bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : (int)($_SESSION['pay_booking_id'] ?? 0);
if ($bookingId <= 0) {
  header('Location: ./dashboard.php');
  exit;
}

// Fetch booking info to confirm ownership and get sport
$stmt = $db->prepare("SELECT b.id, s.sport_name FROM bookings b JOIN timetable t ON t.id = b.timetable_id JOIN sports s ON s.id = t.sport_id WHERE b.id = :bid AND b.user_id = :uid LIMIT 1");
$stmt->execute(['bid' => $bookingId, 'uid' => $_SESSION['user']['id']]);
$bk = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$bk) {
  header('Location: ./dashboard.php');
  exit;
}

// Sport-wise rate
$rates = [
  'Badminton' => 6000,
  'Basketball' => 7500,
  'Futsal' => 9000,
  'Table Tennis' => 5000,
  'Volleyball' => 7500,
  'Boxing' => 10000,
  'Indoor Climbing' => 12000
];

$amount = $rates[$bk['sport_name']] ?? 0;
?>

<section class="max-w-xl mx-auto px-4 py-10">
  <h1 class="text-3xl font-bold text-center mb-6">Complete Payment</h1>

  <?php if (!empty($_SESSION['payment_error'])): ?>
    <div class="mb-4 p-3 bg-red-900/40 border border-red-600 text-red-300 rounded">
      <?= htmlspecialchars($_SESSION['payment_error']) ?>
    </div>
    <?php unset($_SESSION['payment_error']); ?>
  <?php endif; ?>

  <form action="../controllers/paycontroller.php" method="POST" class="bg-gray-800 p-6 rounded shadow space-y-4">
    <input type="hidden" name="action" value="complete_payment">
    <input type="hidden" name="booking_id" value="<?= (int)$bookingId ?>">

    <div>
      <label class="block mb-1 font-medium">Payment Method</label>
      <select name="method" required class="w-full px-4 py-2 rounded bg-gray-700 text-white">
        <option value="">-- Select Method --</option>
        <option value="card">Card</option>
        <option value="cash">Cash</option>
      </select>
    </div>

    <div>
      <label class="block mb-1 font-medium">Amount(LKR)</label>
      <input type="number" step="0.01" min="0" name="amount" required class="w-full px-4 py-2 rounded bg-gray-700 text-white" value="<?= htmlspecialchars(number_format($amount, 2, '.', '')) ?>" readonly>
    </div>

    <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-6 py-3 rounded-full">
      Complete Payment
    </button>
  </form>
</section>

<?php include(__DIR__ . '/../partials/footer.php'); ?>