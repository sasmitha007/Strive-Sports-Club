<?php
require_once ('../../core/sessions.php');
include('../../partials/header.php');

requireLogin();
$user = getLoggedInUser();
?>

<section class="max-w-6xl mx-auto px-4 py-10 min-h-[60vh]">
    <?php $firstName = explode(' ', trim($user['fullname']))[0]; ?>
    <h1 class="text-3xl font-bold mb-6 text-center text-yellow-400">Admin Dashboard - Welcome, <?= htmlspecialchars($firstName) ?> 🛠️</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Manage Users -->
        <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-semibold mb-2 text-yellow-400">👥 Manage Users</h2>
            <p class="text-gray-300 mb-4">View, add, edit, or remove users.</p>
            <a href="manage_users.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-full font-medium transition">Go to Users</a>
        </div>

        <!-- View All Bookings -->
        <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-semibold mb-2 text-yellow-400">📑 All Bookings</h2>
            <p class="text-gray-300 mb-4">Monitor all session bookings across users.</p>
            <a href="all_bookings.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-full font-medium transition">View Bookings</a>
        </div>

        <!-- Schedule Sessions -->
        <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-semibold mb-2 text-yellow-400">🗓️ Schedule Sessions</h2>
            <p class="text-gray-300 mb-4">Create or update the weekly timetable.</p>
            <a href="schedule_sessions.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-full font-medium transition">Manage Schedule</a>
        </div>

        <!-- Feedback Overview -->
        <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-semibold mb-2 text-yellow-400">💬 User Feedback</h2>
            <p class="text-gray-300 mb-4">Review user-submitted feedback.</p>
            <a href="feedback_overview.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-full font-medium transition">View Feedback</a>
        </div>

        <!-- Admin Settings -->
        <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-semibold mb-2 text-yellow-400">⚙️ Admin Settings</h2>
            <p class="text-gray-300 mb-4">Change platform configurations.</p>
            <a href="settings.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-full font-medium transition">Settings</a>
        </div>

        <!-- Logout -->
        <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-semibold mb-2 text-yellow-400">🚪 Logout</h2>
            <p class="text-gray-300 mb-4">End your session securely.</p>
            <a href="<?= $BASE_URL ?>/controllers/authcontroller.php?logout=1" class="inline-block bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full font-medium transition">Logout</a>
        </div>
    </div>
</section>

<?php include('../../partials/footer.php'); ?>
