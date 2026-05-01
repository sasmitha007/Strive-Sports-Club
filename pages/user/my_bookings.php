<?php
require_once __DIR__ . '/../../controllers/bookingcontroller.php';
requireLogin();
include __DIR__ . '/../../partials/header.php';

$userId = (int)($_SESSION['user']['id'] ?? 0);
$rows   = $userId ? getMyBookingsWithStatus($userId) : [];

function fmtTime($t) { return $t ? date('g:i A', strtotime($t)) : ''; }
function fmtDate($d) { return $d ? date('Y-m-d', strtotime($d)) : '—'; }

// flash
if (!empty($_SESSION['booking_error'])) {
    echo "<div class='bg-red-600 text-white text-center py-2 px-4 font-medium mb-4'>"
        . htmlspecialchars($_SESSION['booking_error']) . "</div>";
    unset($_SESSION['booking_error']);
}
if (!empty($_SESSION['booking_success'])) {
    echo "<div class='bg-green-600 text-white text-center py-2 px-4 font-medium mb-4'>"
        . htmlspecialchars($_SESSION['booking_success']) . "</div>";
    unset($_SESSION['booking_success']);
}
?>

<section class="max-w-6xl mx-auto px-4 py-10 min-h-[60vh]">
    <h1 class="text-3xl font-bold mb-6 text-center">My Bookings</h1>

    <?php if (empty($rows)): ?>
        <div class="bg-gray-800 border border-gray-700 text-gray-300 p-6 rounded-lg text-center">
            You don’t have any bookings yet.
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
                <thead class="bg-gray-700 text-left text-sm uppercase text-gray-300">
                <tr>
                    <th class="px-4 py-3">User ID</th>
                    <th class="px-4 py-3">User Name</th>
                    <th class="px-4 py-3">Sport Name</th>
                    <th class="px-4 py-3">Sport ID</th>
                    <th class="px-4 py-3">Coach Assigned</th>
                    <th class="px-4 py-3">Coach ID</th>
                    <th class="px-4 py-3">Day</th>
                    <th class="px-4 py-3">Time (Start - End)</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                <?php foreach ($rows as $r): ?>
                    <tr class="hover:bg-gray-750">
                        <td class="px-4 py-3"><?= (int)$r['user_id'] ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($r['user_name']) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($r['sport_name']) ?></td>
                        <td class="px-4 py-3"><?= (int)$r['sport_id'] ?></td>
                        <td class="px-4 py-3"><?= $r['coach_name'] ? htmlspecialchars($r['coach_name']) : '—' ?></td>
                        <td class="px-4 py-3"><?= $r['coach_id'] ? (int)$r['coach_id'] : '—' ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($r['day_of_week']) ?></td>
                        <td class="px-4 py-3"><?= fmtTime($r['start_time']) ?> - <?= fmtTime($r['end_time']) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($r['status_text']) ?></td>
                        <td class="px-4 py-3">
                            <?php if (empty($r['is_paid'])): ?>
                                <!-- Pending: show Pay + Cancel -->
                                <div class="flex gap-2">
                                    <form action="../../controllers/bookingcontroller.php" method="POST">
                                        <input type="hidden" name="booking_id" value="<?= (int)$r['booking_id'] ?>">
                                        <button type="submit" name="start_payment"
                                            class="bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1 rounded-full text-sm">
                                            Pay now
                                        </button>
                                    </form>
                                    <form action="../../controllers/bookingcontroller.php" method="POST" onsubmit="return confirm('Cancel this booking?');">
                                        <input type="hidden" name="booking_id" value="<?= (int)$r['booking_id'] ?>">
                                        <button type="submit" name="cancel_booking"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full text-sm">
                                            Cancel
                                        </button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <!-- Paid: show Refund if eligible -->
                                <?php if (!empty($r['is_refundable'])): ?>
                                    <form action="../../controllers/bookingcontroller.php" method="POST" onsubmit="return confirm('Request refund for this booking?');">
                                        <input type="hidden" name="booking_id" value="<?= (int)$r['booking_id'] ?>">
                                        <button type="submit" name="request_refund"
                                            class="bg-blue-400 hover:bg-blue-500 text-black px-3 py-1 rounded-full text-sm">
                                            Refund
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-gray-400">—</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="text-center mt-6">
        <a href="../dashboard.php" class="text-yellow-400 hover:underline">← Back to Dashboard</a>
    </div>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>