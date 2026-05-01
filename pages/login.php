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

<!-- HERO -->
<header class="relative overflow-hidden rounded-b-3xl">
  <div class="pointer-events-none absolute inset-0">
    <div class="absolute -top-24 -left-28 w-[42rem] h-[42rem] bg-emerald-500/10 blur-3xl rounded-full"></div>
    <div class="absolute -bottom-24 -right-24 w-[46rem] h-[46rem] bg-purple-500/10 blur-3xl rounded-full"></div>
  </div>
  <div class="relative h-[40vh] flex items-center justify-center text-center">
    <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/60 to-black/80"></div>
    <div class="relative z-10 max-w-3xl mx-auto px-6">
      <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">Welcome Back</h1>
      <p class="text-gray-300 mt-3">Sign in to manage bookings, see your sessions, and more.</p>
      <div class="mt-5 inline-flex items-center gap-2 rounded-full bg-emerald-500/10 ring-1 ring-emerald-400/30 px-3 py-1 text-emerald-200 text-xs">
        <span class="relative flex h-2 w-2">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
        </span>
        Secure sign-in • Encrypted
      </div>
    </div>
  </div>
</header>

<section class="max-w-md mx-auto px-4 py-10 min-h-[60vh]">
  <!-- Toasters -->
  <?php if ($loginError): ?>
    <div class="mb-4 rounded-xl border border-red-400/40 bg-red-500/10 text-red-200 px-4 py-3 text-sm">
      ⚠ Invalid email or password. Please try again.
    </div>
  <?php endif; ?>

  <?php if ($next): ?>
    <div class="mb-4 rounded-xl border border-yellow-400/40 bg-yellow-500/10 text-yellow-200 px-4 py-3 text-sm">
      Please log in to continue to the page you requested.
    </div>
  <?php endif; ?>

  <!-- Card -->
  <div class="relative rounded-3xl border border-white/10 bg-[#0B0F14]/70 backdrop-blur-xl shadow-[0_0_40px_rgba(0,255,200,0.08)] overflow-hidden">
    <div class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-emerald-400/15 via-cyan-400/15 to-purple-400/15 blur-2xl"></div>

    <div class="relative p-6 md:p-8">
      <h2 class="text-2xl font-semibold mb-6 text-center">Login to Your Account</h2>

      <form action="../controllers/authcontroller.php" method="POST" class="space-y-5" autocomplete="off" novalidate>
        <!-- carry next to the controller so it can redirect back -->
        <input type="hidden" name="next" value="<?= htmlspecialchars($next, ENT_QUOTES) ?>">

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm text-gray-300 mb-2">Email</label>
          <div class="relative">
            <input
              type="email" name="email" id="email" required
              class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 pr-10 text-white placeholder-gray-500
                     focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400/40 transition"
              placeholder="you@example.com">
            <span class="pointer-events-none absolute inset-y-0 right-3 grid place-items-center text-gray-500">✉️</span>
          </div>
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm text-gray-300 mb-2">Password</label>
          <div class="relative">
            <input
              type="password" name="password" id="password" required
              class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 pr-12 text-white placeholder-gray-500
                     focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400/40 transition"
              placeholder="••••••••">
            <!-- Toggle -->
            <button type="button" id="togglePass"
              class="absolute inset-y-0 right-2 my-auto h-9 px-3 rounded-lg border border-white/10 bg-white/5 text-sm text-gray-300
                     hover:border-emerald-400/40 hover:bg-white/10 transition"
              aria-label="Show password">Show</button>
          </div>
          <div class="mt-2 flex items-center justify-between text-xs text-gray-400">
            <span id="capsHint" class="hidden">Caps Lock is ON</span>
            <a href="#" class="hover:underline underline-offset-4">Forgot password?</a>
          </div>
        </div>

        <!-- Submit -->
        <div class="pt-2 text-center">
          <button type="submit" name="login"
                  class="group relative inline-flex items-center gap-2 rounded-full bg-white text-black px-8 py-3 text-sm font-medium
                         transition hover:shadow-[0_0_30px_rgba(250,204,21,0.45)]">
            <span class="absolute inset-0 -z-10 rounded-full border border-yellow-400/40"></span>
            Login
          </button>
        </div>

        <!-- Redirect to Register -->
        <p class="text-sm text-gray-400 mt-2 text-center">
          Don’t have an account?
          <a href="<?= $BASE_URL ?>/pages/register.php" class="text-emerald-300 hover:underline underline-offset-4">Register here</a>
        </p>
      </form>
    </div>
  </div>
</section>

<script>
  // Password show/hide + Caps Lock hint
  (function () {
    const btn = document.getElementById('togglePass');
    const inp = document.getElementById('password');
    const caps = document.getElementById('capsHint');

    if (btn && inp) {
      btn.addEventListener('click', () => {
        const showing = inp.type === 'text';
        inp.type = showing ? 'password' : 'text';
        btn.textContent = showing ? 'Show' : 'Hide';
        btn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
        inp.focus();
      });
    }

    if (inp && caps) {
      const onKey = (e) => {
        const isCaps = e.getModifierState && e.getModifierState('CapsLock');
        caps.classList.toggle('hidden', !isCaps);
      };
      inp.addEventListener('keydown', onKey);
      inp.addEventListener('keyup', onKey);
    }
  })();
</script>

<?php include('../partials/footer.php'); ?>
