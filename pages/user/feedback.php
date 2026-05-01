<?php
include __DIR__ . '/../../partials/header.php';
require_once __DIR__ . '/../../core/db.php';
requireLogin();

$db = Database::connect();
$userId = $_SESSION['user']['id'] ?? null;

// Fetch recent feedback by this user (optional, for display)
$myStmt = $db->prepare("
    SELECT id, message, target_type, target_id, rating, submitted_at
    FROM feedback
    WHERE user_id = :uid
    ORDER BY submitted_at DESC
    LIMIT 10
");
$myStmt->execute(['uid' => $userId]);
$myFeedback = $myStmt->fetchAll(PDO::FETCH_ASSOC);

// flash messages
if (!empty($_SESSION['feedback_error'])) {
    echo "<div class='bg-red-600 text-white text-center py-2 px-4 font-medium mb-4'>"
    . htmlspecialchars($_SESSION['feedback_error'])
    . "</div>";
    unset($_SESSION['feedback_error']);
}
if (!empty($_SESSION['feedback_success'])) {
    echo "<div class='bg-green-600 text-white text-center py-2 px-4 font-medium mb-4'>"
    . htmlspecialchars($_SESSION['feedback_success'])
    . "</div>";
    unset($_SESSION['feedback_success']);
}
?>

<section class="max-w-lg mx-auto px-4 py-10 min-h-[60vh]">
    <h1 class="text-3xl font-bold mb-6 text-center">Give Feedback</h1>

    <form action="../../controllers/usercontroller.php" method="POST" class="bg-gray-800 p-6 rounded-lg shadow-lg" autocomplete="off">
        <input type="hidden" name="submit_feedback" value="1">

        <!-- Target Type -->
        <div class="mb-4">
            <label for="target_type" class="block text-sm font-medium mb-2">Target Type</label>
            <select name="target_type" id="target_type" required
                    class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                <option value="coach">Coach</option>
                <option value="sport">Sport</option>
                <option value="session">Session</option>
                <option value="other">Other</option>
            </select>
            <p class="text-xs text-gray-400 mt-1">Pick what this feedback is about.</p>
        </div>

        <!-- Target ID (optional if 'other') -->
        <div class="mb-4">
            <label for="target_id" class="block text-sm font-medium mb-2">Target ID (optional)</label>
            <input type="number" name="target_id" id="target_id"
            class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400"
            placeholder="e.g., Coach ID / Sport ID / Session ID">
        </div>

        <!-- Rating (5-star) -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Rating</label>

            <div id="ratingStars" class="flex items-center gap-2 select-none">
                <!-- radios (hidden but accessible); 'required' on the group -->
                <input class="sr-only" type="radio" id="star1" name="rating" value="1" required>
                <input class="sr-only" type="radio" id="star2" name="rating" value="2">
                <input class="sr-only" type="radio" id="star3" name="rating" value="3">
                <input class="sr-only" type="radio" id="star4" name="rating" value="4">
                <input class="sr-only" type="radio" id="star5" name="rating" value="5">

                <!-- star labels -->
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <label
                        for="star<?= $i ?>"
                        data-value="<?= $i ?>"
                        class="cursor-pointer inline-flex focus:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400 rounded"
                        title="<?= $i ?> star<?= $i > 1 ? 's' : '' ?>"
                        aria-label="<?= $i ?> star<?= $i > 1 ? 's' : '' ?>"
                    >
                        <!-- pointer-events-none ensures click hits the label -->
                        <svg class="w-8 h-8 transition-opacity duration-150 text-yellow-400 pointer-events-none" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.18 3.63a1 1 0 00.95.69h3.813c.967 0 1.371 1.24.588 1.81l-3.084 2.24a1 1 0 00-.364 1.118l1.18 3.63c.3.92-.755 1.688-1.54 1.118l-3.084-2.24a1 1 0 00-1.175 0l-3.084 2.24c-.784.57-1.839-.198-1.54-1.118l1.18-3.63a1 1 0 00-.364-1.118L2.518 9.057c-.783-.57-.379-1.81.588-1.81H6.92a1 1 0 00.95-.69l1.18-3.63z"/>
                        </svg>
                    </label>
                <?php endfor; ?>

                <span id="ratingValue" class="ml-2 text-sm text-gray-300">No rating</span>
            </div>
        </div>

        <!-- Message -->
        <div class="mb-6">
            <label for="message" class="block text-sm font-medium mb-2">Message</label>
            <textarea name="message" id="message" required rows="5"
            class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400"
            placeholder="Write your feedback here..."></textarea>
        </div>

        <!-- Submit -->
        <div class="text-center">
            <button type="submit"
                    class="bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-6 py-2 rounded-full shadow transition">
                Submit Feedback
            </button>
        </div>

        <div class="text-sm text-gray-400 mt-4 text-center">
            <a href="../dashboard.php" class="text-yellow-400 hover:underline">Back to dashboard</a>
        </div>
    </form>

    <?php if ($myFeedback): ?>
    <h2 class="text-2xl font-semibold mt-10 mb-4">Your recent feedback</h2>
    <div class="space-y-4">
        <?php foreach ($myFeedback as $fb): ?>
            <div class="bg-gray-800 p-4 rounded border border-gray-700">
                <div class="text-sm text-gray-400 mb-1">
                    <?= htmlspecialchars(ucfirst($fb['target_type'])) ?>
                    <?php if (!is_null($fb['target_id'])): ?>
                        #<?= (int)$fb['target_id'] ?>
                    <?php endif; ?>
                    • Rated: <?= (int)$fb['rating'] ?>/5
                    • <?= htmlspecialchars(date('Y-m-d H:i', strtotime($fb['submitted_at']))) ?>
                </div>
                <div class="text-gray-200">
                    <?= nl2br(htmlspecialchars($fb['message'])) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
