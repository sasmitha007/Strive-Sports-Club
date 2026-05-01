<?php
require_once __DIR__ . '/../core/sessions.php';

/* ---- Determine a safe return URL (next) ---- */
$next = '';
if (isset($_GET['next']) && is_string($_GET['next']) && $_GET['next'] !== '' && $_GET['next'][0] === '/') {
    // allow only relative paths to avoid open redirects
    $next = $_GET['next'];
}

/* ---- If already logged in, skip the form ---- */
if (isLoggedIn()) {
    $dest = $next ?: '/Indoor%20sports%20club/pages/dashboard.php';
    header('Location: ' . $dest);
    exit;
}

/* ---- Flash: login error ---- */
$loginError = !empty($_SESSION['login_error']);
unset($_SESSION['login_error']);

/* We're only reading session now; release the lock */
session_write_close();

include('../partials/header.php');
?>

<?php if ($loginError): ?>
  <div class="bg-red-600 text-white text-center py-2 px-4 font-medium">
      Invalid email or password. Please try again.
  </div>
<?php endif; ?>

<?php if ($next): ?>
  <div class="bg-yellow-900/40 border border-yellow-600 text-yellow-200 text-center py-2 px-4">
      Please log in to continue to the page you requested.
  </div>
<?php endif; ?>

<section class="max-w-md mx-auto px-4 py-10 min-h-[60vh]">
    <h1 class="text-3xl font-bold mb-6 text-center">Login to Your Account</h1>

    <form action="../controllers/authcontroller.php" method="POST" class="bg-gray-800 p-6 rounded-lg shadow-lg" autocomplete="off">
        <!-- carry next to the controller so it can redirect back -->
        <input type="hidden" name="next" value="<?= htmlspecialchars($next, ENT_QUOTES) ?>">

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" id="email" required
                class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                placeholder="example@email.com">
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium mb-2">Password</label>
            <input type="password" name="password" id="password" required
                class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                placeholder="••••••••">
            <div class="mt-2 text-sm text-gray-400">
                <input type="checkbox" id="show-password">
                <label for="show-password">Show Password</label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center mt-6">
            <button type="submit" name="login"
                class="bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-6 py-2 rounded-full shadow transition">
                Login
            </button>
        </div>

        <!-- Redirect to Register -->
        <div class="text-sm text-gray-400 mt-4 text-center">
            Don’t have an account? <a href="register.php" class="text-yellow-400 hover:underline">Register here</a>
        </div>

        <!-- Forgot password -->
        <div class="text-sm text-gray-400 mt-4 text-center">
            Forgotten your password? <a href="forgot.php" class="text-yellow-400 hover:underline">Forgot password</a>
        </div>
    </form>
</section>

<script>
  // simple password toggle
  document.getElementById('show-password')?.addEventListener('change', function () {
    const inp = document.getElementById('password');
    if (!inp) return;
    inp.type = this.checked ? 'text' : 'password';
  });
</script>

<?php include('../partials/footer.php'); ?>