<?php
include('../partials/header.php');

requireLogin(); // Will redirect if user not logged in
$user = getLoggedInUser(); // Clean access to logged-in user's info
?>

<section class="max-w-6xl mx-auto px-4 py-10 min-h-[60vh]">
    <?php
    $firstName = explode(' ', trim($user['fullname']))[0];
    ?>
    <h1 class="text-3xl font-bold mb-6 text-center">Welcome, <?= htmlspecialchars($firstName) ?> 🎉</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- My Bookings -->
        <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-semibold mb-2 text-yellow-400">📅 My Bookings</h2>
            <p class="text-gray-300 mb-4">View or manage your upcoming sports sessions.</p>
            <a href="user/my_bookings.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-full font-medium transition">View Bookings</a>
        </div>

        <!-- Weekly Timetable -->
        <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-semibold mb-2 text-yellow-400">📆 Weekly Timetable</h2>
            <p class="text-gray-300 mb-4">Check the full indoor sports session schedule.</p>
            <a href="timetable.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-full font-medium transition">View Timetable</a>
        </div>

        <!-- Edit Profile -->
        <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-semibold mb-2 text-yellow-400">👤 My Profile</h2>
            <p class="text-gray-300 mb-4">Update your account details and password.</p>
            <a href="user/profile.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-full font-medium transition">Edit Profile</a>
        </div>

        <!-- Feedback -->
        <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-semibold mb-2 text-yellow-400">💬 Feedback</h2>
            <p class="text-gray-300 mb-4">Let us know how we can improve your experience.</p>
            <a href="user/feedback.php" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-full font-medium transition">Give Feedback</a>
        </div>

        <!-- Logout -->
        <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-semibold mb-2 text-yellow-400">🚪 Logout</h2>
            <p class="text-gray-300 mb-4">You're done? Come back anytime to book again!</p>
            <a href="../controllers/authcontroller.php?logout=1" class="inline-block bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full font-medium transition">Logout</a>
        </div>
    </div>
</section>

<?php include('../partials/footer.php'); ?>