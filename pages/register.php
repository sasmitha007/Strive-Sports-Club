<?php
include('../partials/header.php');

// Show error alert if registration failed
if (isset($_SESSION['register_error'])) {
    echo "<div class='bg-red-600 text-white text-center py-2 px-4 font-medium mb-4'>"
        . htmlspecialchars($_SESSION['register_error']) . "</div>";
    unset($_SESSION['register_error']);
}

// Show success message after registration
if (isset($_SESSION['register_success'])) {
    echo "<div class='bg-green-600 text-white text-center py-2 px-4 font-medium mb-4'>
            Registration successful! Please log in.</div>";
    unset($_SESSION['register_success']);
}
?>

<section class="max-w-md mx-auto px-4 py-10 min-h-[60vh]">
    <h1 class="text-3xl font-bold mb-6 text-center">Create Your Account</h1>

    <form action="../controllers/authcontroller.php" method="POST" class="bg-gray-800 p-6 rounded-lg shadow-lg" autocomplete="off">
        <!-- First Name -->
        <div class="mb-4">
            <label for="first_name" class="block text-sm font-medium mb-2">First Name</label>
            <input type="text" name="first_name" id="first_name" required
                class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                placeholder="Your first name">
        </div>

        <!-- Last Name -->
        <div class="mb-4">
            <label for="last_name" class="block text-sm font-medium mb-2">Last Name</label>
            <input type="text" name="last_name" id="last_name" required
                class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                placeholder="Your last name">
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" id="email" required
                class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                placeholder="example@email.com">
        </div>

        <!-- Contact Number -->
        <div class="mb-4">
            <label for="contact" class="block text-sm font-medium mb-2">Contact Number</label>
            <input type="text" name="contact" id="contact" required
                class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                placeholder="+94 7XXXXXXXX">
        </div>

        <!-- Password -->
        <div class="mb-4 relative">
            <label for="password" class="block text-sm font-medium mb-2">Password</label>
            <input type="password" name="password" id="password" required minlength="6"
                oninput="checkStrength(this.value)"
                class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 pr-12"
                placeholder="••••••••">
            <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-9 text-gray-400 hover:text-yellow-400 focus:outline-none">
                👁️
            </button>
            <div id="strength-text" class="mt-2 text-sm font-medium"></div>
            <div id="strength-bar" class="h-2 w-full mt-1 rounded bg-gray-600">
                <div id="strength-fill" class="h-2 rounded transition-all duration-300"></div>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="mb-4 relative">
            <label for="password_confirm" class="block text-sm font-medium mb-2">Confirm Password</label>
            <input type="password" name="password_confirm" id="password_confirm" required minlength="6"
                oninput="checkMatch()"
                class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 pr-12"
                placeholder="••••••••">
            <button type="button" onclick="togglePassword('password_confirm')" class="absolute right-3 top-9 text-gray-400 hover:text-yellow-400 focus:outline-none">
                👁️
            </button>
            <div id="match-text" class="mt-2 text-sm font-medium flex items-center space-x-2 transition duration-200 ease-in-out">
            <!-- Message gets inserted dynamically -->
            </div>

        </div>

        <!-- Terms & Conditions -->
        <div class="mb-4 flex items-center">
            <input type="checkbox" name="terms" id="terms" required
                class="w-4 h-4 text-yellow-400 bg-gray-700 border-gray-600 rounded focus:ring-yellow-400">
            <label for="terms" class="ml-2 text-sm text-gray-300">
                I agree to the
                <a href="terms.php" target="_blank" class="text-yellow-400 hover:underline">Terms & Conditions</a>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="text-center mt-6">
            <button type="submit" name="register"
                class="bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-6 py-2 rounded-full shadow transition">
                Register
            </button>
        </div>

        <!-- Redirect to Login -->
        <div class="text-sm text-gray-400 mt-4 text-center">
            Already have an account? <a href="login.php" class="text-yellow-400 hover:underline">Login here</a>
        </div>
    </form>
</section>

<?php include('../partials/footer.php'); ?>
