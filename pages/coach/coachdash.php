<?php
require_once ('../../core/sessions.php');
include('../../partials/header.php');

requireLogin();
$user = getLoggedInUser();
?>

<section class="max-w-6xl mx-auto px-4 py-10 min-h-[60vh]">
    <?php $firstName = explode(' ', trim($user['fullname']))[0]; ?>
    <h1 class="text-3xl font-bold mb-6 text-center text-yellow-400">Coach Dashboard - Welcome, <?= htmlspecialchars($firstName) ?> 🏅</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- My Sessions -->
        <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-semibold mb-2 text-yellow-400">🏋️ My Sessions</h2>
            <p class="text-gray-300 mb-4">View and manage the sessions you're coaching.</p>
            <a href="coach_sessions.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-full font-medium transition">View Sessions</a>
        </div>

        <!-- Attendance -->
        <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-semibold mb-2 text-yellow-400">✅ Attendance</h2>
            <p class="text-gray-300 mb-4">Mark or review participant attendance.</p>
            <a href="attendance.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-full font-medium transition">Take Attendance</a>
        </div>

        <!-- Session Reports -->
        <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-semibold mb-2 text-yellow-400">📝 Reports</h2>
            <p class="text-gray-300 mb-4">Submit or view coaching session reports.</p>
            <a href="session_reports.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-full font-medium transition">Session Reports</a>
        </div>

        <!-- Weekly Timetable -->
        <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-semibold mb-2 text-yellow-400">📆 Timetable</h2>
            <p class="text-gray-300 mb-4">See the coaching timetable and locations.</p>
            <a href="../timetable.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-full font-medium transition">View Timetable</a>
        </div>

        <!-- Coach Profile -->
        <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-semibold mb-2 text-yellow-400">👤 My Profile</h2>
            <p class="text-gray-300 mb-4">Edit your coaching profile and bio.</p>
            <a href="../user/profile.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-full font-medium transition">Edit Profile</a>
        </div>

        <!-- Logout -->
        <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-semibold mb-2 text-yellow-400">🚪 Logout</h2>
            <p class="text-gray-300 mb-4">End your coaching session here.</p>
            <a href="<?= $BASE_URL ?>/controllers/authcontroller.php?logout=1" class="inline-block bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full font-medium transition">Logout</a>
        </div>
    </div>
</section>

<?php include('../../partials/footer.php'); ?>
