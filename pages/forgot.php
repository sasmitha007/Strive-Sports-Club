<?php
// /pages/forgot.php
require_once __DIR__ . '/../core/sessions.php';

/* If already logged in, go to dashboard */
if (function_exists('isLoggedIn') && isLoggedIn()) {
    header('Location: /Indoor%20sports%20club/pages/dashboard.php');
    exit;
}

/* Flash messages set by the controller */
$forgotError   = !empty($_SESSION['forgot_error']);     // bool
$forgotSuccess = !empty($_SESSION['forgot_success']);   // bool
$flashMessage  = $_SESSION['flash_message'] ?? '';

unset($_SESSION['forgot_error'], $_SESSION['forgot_success'], $_SESSION['flash_message']);

/* Done reading the session for now */
session_write_close();

include('../partials/header.php');
?>

<?php if ($forgotSuccess): ?>
  <div class="bg-green-700 text-white text-center py-2 px-4 font-medium">
    <?= $flashMessage ? htmlspecialchars($flashMessage) : "If the details match an account, we'll send reset instructions." ?>
  </div>
<?php elseif ($forgotError): ?>
  <div class="bg-red-600 text-white text-center py-2 px-4 font-medium">
    <?= $flashMessage ? htmlspecialchars($flashMessage) : "We couldn't process your request. Please try again." ?>
  </div>
<?php endif; ?>

<section class="max-w-md mx-auto px-4 py-10 min-h-[60vh]">
  <h1 class="text-3xl font-bold mb-2 text-center">Forgot your password?</h1>
  <p class="text-center text-gray-400 mb-6">
    Enter your <span class="font-semibold">email</span> and/or <span class="font-semibold">phone number</span> linked to your account.
  </p>

  <form action="../controllers/forgotcontroller.php" method="POST" class="bg-gray-800 p-6 rounded-lg shadow-lg" autocomplete="off" novalidate>
    <!-- Tell controller what this is -->
    <input type="hidden" name="action" value="forgot_request">

    <!-- Email -->
    <div class="mb-4">
      <label for="email" class="block text-sm font-medium mb-2">Email</label>
      <input
        type="email"
        name="email"
        id="email"
        class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400"
        placeholder="example@email.com"
      >
    </div>

    <!-- Phone -->
    <div class="mb-2">
      <label for="phone" class="block text-sm font-medium mb-2">Phone number</label>
      <input
        type="tel"
        name="phone"
        id="phone"
        inputmode="tel"
        pattern="^(?:\+94|0)\d{9}$"
        class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400"
        placeholder="0712345678 or +94712345678"
        aria-describedby="phoneHelp"
      >
      <p id="phoneHelp" class="text-xs text-gray-400 mt-1">
        Use your registered number (e.g., 0712345678 or +94712345678).
      </p>
    </div>

    <p class="text-xs text-gray-500 mt-2">Provide at least <span class="font-semibold">one</span> of the above.</p>

    <!-- Submit Button -->
    <div class="text-center mt-6 flex items-center justify-center gap-3">
      <button type="submit"
        class="bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-6 py-2 rounded-full shadow transition">
        Continue
      </button>
      <a href="login.php" class="text-sm text-gray-300 hover:underline">Back to login</a>
    </div>
  </form>
</section>

<script>
  // Require at least one of: email or phone; validate simple LK phone format when present
  (function () {
    const form  = document.querySelector('form');
    const email = document.getElementById('email');
    const phone = document.getElementById('phone');

    form?.addEventListener('submit', function (e) {
      const ev = (email?.value || '').trim();
      const ph = (phone?.value || '').trim();

      if (!ev && !ph) {
        e.preventDefault();
        alert('Please enter an email and/or phone number.');
        email?.focus();
        return;
      }

      if (ph) {
        const re = /^(?:\+94|0)\d{9}$/;
        if (!re.test(ph)) {
          e.preventDefault();
          phone.classList.add('ring-2','ring-red-500');
          alert('Please enter a valid Sri Lanka phone number (0712345678 or +94712345678).');
        }
      }
    });
  })();
</script>

<?php include('../partials/footer.php'); ?>