<?php
require_once __DIR__ . '/../core/sessions.php';

// Flash messages
$err   = !empty($_SESSION['forgot_error']);
$ok    = !empty($_SESSION['forgot_success']);
$flash = $_SESSION['flash_message'] ?? '';
unset($_SESSION['forgot_error'], $_SESSION['forgot_success'], $_SESSION['flash_message']);

// Is there a valid reset grant in session?
$grantValid = !empty($_SESSION['pw_reset_user_id'])
           && !empty($_SESSION['pw_reset_expires'])
           && time() <= (int)$_SESSION['pw_reset_expires'];

include('../partials/header.php');
?>

<?php if ($ok): ?>
  <div class="bg-green-700 text-white text-center py-2 px-4 font-medium">
    <?= htmlspecialchars($flash ?: 'Password updated successfully.') ?>
  </div>
<?php elseif ($err): ?>
  <div class="bg-red-600 text-white text-center py-2 px-4 font-medium">
    <?= htmlspecialchars($flash ?: 'There was a problem. Please try again.') ?>
  </div>
<?php endif; ?>

<section class="max-w-md mx-auto px-4 py-10 min-h-[60vh]">
  <h1 class="text-3xl font-bold mb-2 text-center">Reset your password</h1>

  <?php if (!$grantValid): ?>
    <p class="text-center text-gray-400 mb-6">Your reset session is invalid or has expired.</p>
    <div class="text-center">
      <a href="forgot.php" class="text-yellow-400 hover:underline">Start over</a>
    </div>
  <?php else: ?>
    <p class="text-center text-gray-400 mb-6">Enter a new password.</p>

    <form action="../controllers/forgotcontroller.php" method="POST" class="bg-gray-800 p-6 rounded-lg shadow-lg" autocomplete="off" novalidate>
      <input type="hidden" name="action" value="reset_password">
      <input type="hidden" name="nonce" value="<?= htmlspecialchars($_SESSION['pw_reset_nonce'] ?? '', ENT_QUOTES) ?>">

      <div class="mb-4">
        <label for="password" class="block text-sm font-medium mb-2">New password</label>
        <input type="password" name="password" id="password" required minlength="8"
          class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400"
          placeholder="••••••••">
      </div>

      <div class="mb-4">
        <label for="password_confirm" class="block text-sm font-medium mb-2">Confirm password</label>
        <input type="password" name="password_confirm" id="password_confirm" required minlength="8"
          class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400"
          placeholder="••••••••">
        <div class="mt-2 text-sm text-gray-400">
          <input type="checkbox" id="show-password">
          <label for="show-password">Show passwords</label>
        </div>
      </div>

      <div class="text-center mt-6 flex items-center justify-center gap-3">
        <button type="submit"
          class="bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-6 py-2 rounded-full shadow transition">
          Update password
        </button>
        <a href="login.php" class="text-sm text-gray-300 hover:underline">Back to login</a>
      </div>
    </form>
  <?php endif; ?>
</section>

<script>
  // show/hide
  document.getElementById('show-password')?.addEventListener('change', function () {
    ['password','password_confirm'].forEach(id => {
      const inp = document.getElementById(id);
      if (inp) inp.type = this.checked ? 'text' : 'password';
    });
  });

  // client-side match check
  (function () {
    const form = document.querySelector('form');
    const pw1 = document.getElementById('password');
    const pw2 = document.getElementById('password_confirm');
    form?.addEventListener('submit', function (e) {
      if (!pw1?.value || pw1.value.length < 8) {
        e.preventDefault(); alert('Password must be at least 8 characters.'); return;
      }
      if (pw1.value !== pw2?.value) {
        e.preventDefault(); alert('Passwords do not match.'); return;
      }
    });
  })();
</script>

<?php include('../partials/footer.php'); ?>