<?php
include('../partials/header.php');

// Flash messages
$regErr = isset($_SESSION['register_error']) ? (string)$_SESSION['register_error'] : '';
$regOk  = isset($_SESSION['register_success']);

unset($_SESSION['register_error'], $_SESSION['register_success']);
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
      <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">Create Your Account</h1>
      <p class="text-gray-300 mt-3">Join Strive to book sessions, track your timetable, and play more.</p>
      <div class="mt-5 inline-flex items-center gap-2 rounded-full bg-emerald-500/10 ring-1 ring-emerald-400/30 px-3 py-1 text-emerald-200 text-xs">
        <span class="relative flex h-2 w-2">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
        </span>
        Secure • Fast • Free
      </div>
    </div>
  </div>
</header>

<section class="max-w-md mx-auto px-4 py-10 min-h-[60vh]">
  <!-- Toasts -->
  <?php if (!empty($regErr)): ?>
    <div class="mb-4 rounded-xl border border-red-400/40 bg-red-500/10 text-red-200 px-4 py-3 text-sm">
      ⚠ <?= htmlspecialchars($regErr) ?>
    </div>
  <?php endif; ?>

  <?php if ($regOk): ?>
    <div class="mb-4 rounded-xl border border-emerald-400/40 bg-emerald-500/10 text-emerald-200 px-4 py-3 text-sm">
      ✅ Registration successful! Please log in.
    </div>
  <?php endif; ?>

  <!-- Card -->
  <div class="relative rounded-3xl border border-white/10 bg-[#0B0F14]/70 backdrop-blur-xl shadow-[0_0_40px_rgba(0,255,200,0.08)] overflow-hidden">
    <div class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-emerald-400/15 via-cyan-400/15 to-purple-400/15 blur-2xl"></div>

    <div class="relative p-6 md:p-8">
      <!-- Steps -->
      <ol class="flex flex-wrap gap-2 mb-6 text-xs">
        <li class="inline-flex items-center gap-2 rounded-full bg-white/5 border border-white/10 px-3 py-1">
          <span class="h-5 w-5 grid place-items-center rounded-full bg-white text-black text-[10px]">1</span>
          Your Details
        </li>
        <li class="inline-flex items-center gap-2 rounded-full bg-white/5 border border-white/10 px-3 py-1">
          <span class="h-5 w-5 grid place-items-center rounded-full bg-white text-black text-[10px]">2</span>
          Security
        </li>
        <li class="inline-flex items-center gap-2 rounded-full bg-white/5 border border-white/10 px-3 py-1">
          <span class="h-5 w-5 grid place-items-center rounded-full bg-white text-black text-[10px]">3</span>
          Confirm
        </li>
      </ol>

      <form action="../controllers/authcontroller.php" method="POST" class="space-y-5" autocomplete="off" novalidate>
        <!-- First Name -->
        <div>
          <label for="first_name" class="block text-sm text-gray-300 mb-2">First Name</label>
          <input type="text" name="first_name" id="first_name" required
                 class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder-gray-500
                        focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400/40 transition"
                 placeholder="Your first name">
        </div>

        <!-- Last Name -->
        <div>
          <label for="last_name" class="block text-sm text-gray-300 mb-2">Last Name</label>
          <input type="text" name="last_name" id="last_name" required
                 class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder-gray-500
                        focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400/40 transition"
                 placeholder="Your last name">
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm text-gray-300 mb-2">Email</label>
          <div class="relative">
            <input type="email" name="email" id="email" required
                   class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 pr-10 text-white placeholder-gray-500
                          focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400/40 transition"
                   placeholder="you@example.com">
            <span class="pointer-events-none absolute inset-y-0 right-3 grid place-items-center text-gray-500">✉️</span>
          </div>
        </div>

        <!-- Contact Number -->
        <div>
          <label for="contact" class="block text-sm text-gray-300 mb-2">Contact Number</label>
          <input type="text" name="contact" id="contact" required
                 class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder-gray-500
                        focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400/40 transition"
                 placeholder="+94 7XXXXXXXX">
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm text-gray-300 mb-2">Password</label>
          <div class="relative">
            <input type="password" name="password" id="password" required minlength="8"
                   class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 pr-12 text-white placeholder-gray-500
                          focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400/40 transition"
                   placeholder="••••••••">
            <!-- Toggle button (Show/Hide like login page) -->
            <button type="button"
                    class="toggle-btn absolute inset-y-0 right-2 my-auto h-9 px-3 rounded-lg border border-white/10 bg-white/5 text-sm text-gray-300
                           hover:border-emerald-400/40 hover:bg-white/10 transition"
                    data-target="password"
                    aria-label="Show password"
                    aria-pressed="false">
              Show
            </button>
          </div>

          <div id="strength-text" class="mt-2 text-xs font-medium text-gray-300">Strength: <span id="strength-label" class="text-gray-400">—</span></div>
          <div id="strength-bar" class="h-2 w-full mt-1 rounded bg-white/5 overflow-hidden">
            <div id="strength-fill" class="h-2 w-0 rounded bg-red-400 transition-all duration-300"></div>
          </div>
          <ul class="mt-2 text-[11px] text-gray-400 grid grid-cols-2 gap-x-3">
            <li id="req-length">• 8+ characters</li>
            <li id="req-number">• a number</li>
            <li id="req-upper">• an uppercase letter</li>
            <li id="req-special">• a symbol</li>
          </ul>
        </div>

        <!-- Confirm Password -->
        <div>
          <label for="password_confirm" class="block text-sm text-gray-300 mb-2">Confirm Password</label>
          <div class="relative">
            <input type="password" name="password_confirm" id="password_confirm" required minlength="8"
                   class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 pr-12 text-white placeholder-gray-500
                          focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400/40 transition"
                   placeholder="••••••••">
            <!-- Toggle button (Show/Hide like login page) -->
            <button type="button"
                    class="toggle-btn absolute inset-y-0 right-2 my-auto h-9 px-3 rounded-lg border border-white/10 bg-white/5 text-sm text-gray-300
                           hover:border-emerald-400/40 hover:bg-white/10 transition"
                    data-target="password_confirm"
                    aria-label="Show password"
                    aria-pressed="false">
              Show
            </button>
          </div>
          <div id="match-text" class="mt-2 text-sm text-gray-400">Passwords must match.</div>
        </div>

        <!-- Terms & Conditions -->
        <div class="flex items-center gap-2">
          <input type="checkbox" name="terms" id="terms" required
                 class="w-4 h-4 text-emerald-400 bg-transparent border-white/20 rounded focus:ring-emerald-400">
          <label for="terms" class="text-sm text-gray-300">
            I agree to the
            <a href="<?= $BASE_URL ?>/pages/terms.php" target="_blank" class="text-emerald-300 hover:underline underline-offset-4">Terms &amp; Conditions</a>
          </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-2 text-center">
          <button type="submit" name="register"
                  class="group relative inline-flex items-center gap-2 rounded-full bg-white text-black px-8 py-3 text-sm font-medium
                         transition hover:shadow-[0_0_30px_rgba(250,204,21,0.45)]">
            <span class="absolute inset-0 -z-10 rounded-full border border-yellow-400/40"></span>
            Register
          </button>
        </div>

        <!-- Redirect to Login -->
        <p class="text-sm text-gray-400 mt-2 text-center">
          Already have an account?
          <a href="<?= $BASE_URL ?>/pages/login.php" class="text-emerald-300 hover:underline underline-offset-4">Login here</a>
        </p>
      </form>
    </div>
  </div>
</section>

<script>
  // --- Password toggle (Show <-> Hide like login page) ---
  (function () {
    const buttons = document.querySelectorAll('.toggle-btn');
    buttons.forEach(btn => {
      const targetId = btn.getAttribute('data-target');
      const input = document.getElementById(targetId);
      if (!input) return;

      btn.addEventListener('click', () => {
        const showing = input.type === 'text';
        input.type = showing ? 'password' : 'text';
        btn.textContent = showing ? 'Show' : 'Hide';
        btn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
        btn.setAttribute('aria-pressed', String(!showing));
        input.focus();
      });
    });
  })();

  // --- Password strength meter ---
  const pwd = document.getElementById('password');
  const fill = document.getElementById('strength-fill');
  const label = document.getElementById('strength-label');

  const reqLen = document.getElementById('req-length');
  const reqNum = document.getElementById('req-number');
  const reqUp  = document.getElementById('req-upper');
  const reqSp  = document.getElementById('req-special');

  function mark(el, ok) {
    if (!el) return;
    el.classList.toggle('text-emerald-300', ok);
    el.classList.toggle('text-gray-400', !ok);
  }

  function strengthColor(score) {
    if (score <= 1) return 'bg-red-400';
    if (score === 2) return 'bg-yellow-400';
    if (score === 3) return 'bg-amber-400';
    return 'bg-emerald-400';
  }

  function strengthLabel(score) {
    return ['Very weak', 'Weak', 'Fair', 'Strong'][Math.min(score, 3)];
  }

  function calcStrength(v) {
    let score = 0;
    const hasLen = v.length >= 8;
    const hasNum = /[0-9]/.test(v);
    const hasUp  = /[A-Z]/.test(v);
    const hasSp  = /[^A-Za-z0-9]/.test(v);

    mark(reqLen, hasLen);
    mark(reqNum, hasNum);
    mark(reqUp,  hasUp);
    mark(reqSp,  hasSp);

    score += hasLen ? 1 : 0;
    score += hasNum ? 1 : 0;
    score += hasUp  ? 1 : 0;
    score += hasSp  ? 1 : 0;

    const pct = (score / 4) * 100;
    fill.style.width = pct + '%';
    fill.classList.remove('bg-red-400','bg-yellow-400','bg-amber-400','bg-emerald-400');
    fill.classList.add(strengthColor(score));
    label.textContent = strengthLabel(score);
  }

  if (pwd) {
    pwd.addEventListener('input', (e) => calcStrength(e.target.value || ''));
    calcStrength(pwd.value || '');
  }

  // --- Password match feedback ---
  const pwd2 = document.getElementById('password_confirm');
  const matchText = document.getElementById('match-text');
  function checkMatch() {
    if (!pwd || !pwd2 || !matchText) return;
    const ok = pwd2.value.length > 0 && pwd2.value === pwd.value;
    matchText.textContent = ok ? 'Passwords match.' : (pwd2.value ? 'Passwords do not match.' : 'Passwords must match.');
    matchText.classList.toggle('text-emerald-300', ok);
    matchText.classList.toggle('text-red-300', !ok && pwd2.value);
    matchText.classList.toggle('text-gray-400', !pwd2.value);
  }
  if (pwd2) {
    pwd2.addEventListener('input', checkMatch);
    if (pwd) pwd.addEventListener('input', checkMatch);
  }
</script>

<?php include('../partials/footer.php'); ?>
