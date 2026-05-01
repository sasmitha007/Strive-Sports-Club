<?php
include('../partials/header.php');

requireLogin(); // Will redirect if user not logged in
$user = getLoggedInUser(); // Clean access to logged-in user's info

$firstName = 'Member';
if (isset($user['fullname'])) {
  $parts = preg_split('/\s+/', trim((string)$user['fullname']));
  if (!empty($parts[0])) $firstName = $parts[0];
}
?>

<!-- HERO -->
<header class="relative overflow-hidden rounded-b-3xl">
  <!-- Ambient gradient glows -->
  <div class="pointer-events-none absolute inset-0">
    <div class="absolute -top-24 -left-28 w-[42rem] h-[42rem] bg-emerald-500/10 blur-3xl rounded-full"></div>
    <div class="absolute -bottom-24 -right-24 w-[46rem] h-[46rem] bg-purple-500/10 blur-3xl rounded-full"></div>
  </div>

  <div class="relative h-[38vh] md:h-[42vh] flex items-center justify-center text-center">
    <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/60 to-black/80"></div>
    <div class="relative z-10 max-w-3xl mx-auto px-6">
      <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">
        Welcome, <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-300 via-cyan-300 to-purple-300"><?= htmlspecialchars($firstName) ?></span> 🎉
      </h1>
      <p class="text-gray-300 mt-3">
        Manage your bookings, check the weekly timetable, and update your profile.
      </p>
      <div class="mt-5 inline-flex items-center gap-2 rounded-full bg-emerald-500/10 ring-1 ring-emerald-400/30 px-3 py-1 text-emerald-200 text-xs">
        <span class="relative flex h-2 w-2">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
        </span>
        Logged in • Ready to book
      </div>
    </div>
  </div>
</header>

<section class="max-w-7xl mx-auto px-4 py-10 min-h-[60vh]">
  <!-- Quick Actions -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- My Bookings -->
    <a href="<?= $BASE_URL ?>/pages/user/my_bookings.php"
       class="reveal group relative overflow-hidden rounded-3xl border border-white/10 bg-[#0B0F14]/70 backdrop-blur-xl p-6 shadow-[0_0_40px_rgba(0,255,200,0.08)] transition hover:border-emerald-400/40">
      <div class="absolute inset-0 bg-gradient-to-b from-black/0 via-black/20 to-black/60 pointer-events-none"></div>
      <div class="relative z-10">
        <div class="inline-flex items-center gap-2 text-sm text-emerald-200">
          <span class="text-lg">📅</span>
          <span>Schedule</span>
        </div>
        <h2 class="text-xl font-semibold mt-2">My Bookings</h2>
        <p class="text-gray-300 mt-1">View or manage your upcoming sports sessions.</p>
        <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-white text-black px-4 py-2 text-sm font-medium transition group-hover:shadow-[0_0_24px_rgba(250,204,21,0.45)]">
          View Bookings →
        </div>
      </div>
    </a>

    <!-- Weekly Timetable -->
    <a href="<?= $BASE_URL ?>/pages/timetable.php"
       class="reveal group relative overflow-hidden rounded-3xl border border-white/10 bg-[#0B0F14]/70 backdrop-blur-xl p-6 shadow-[0_0_40px_rgba(0,255,200,0.08)] transition hover:border-emerald-400/40">
      <div class="absolute inset-0 bg-gradient-to-b from-black/0 via-black/20 to-black/60 pointer-events-none"></div>
      <div class="relative z-10">
        <div class="inline-flex items-center gap-2 text-sm text-emerald-200">
          <span class="text-lg">📆</span>
          <span>Timetable</span>
        </div>
        <h2 class="text-xl font-semibold mt-2">Weekly Timetable</h2>
        <p class="text-gray-300 mt-1">Check the full indoor sports session schedule.</p>
        <div class="mt-4 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-2 text-sm text-white backdrop-blur transition group-hover:border-cyan-400/50 group-hover:bg-white/10">
          View Timetable →
        </div>
      </div>
    </a>

    <!-- My Profile -->
    <a href="<?= $BASE_URL ?>/pages/user/profile.php"
       class="reveal group relative overflow-hidden rounded-3xl border border-white/10 bg-[#0B0F14]/70 backdrop-blur-xl p-6 shadow-[0_0_40px_rgba(0,255,200,0.08)] transition hover:border-emerald-400/40">
      <div class="absolute inset-0 bg-gradient-to-b from-black/0 via-black/20 to-black/60 pointer-events-none"></div>
      <div class="relative z-10">
        <div class="inline-flex items-center gap-2 text-sm text-emerald-200">
          <span class="text-lg">👤</span>
          <span>Account</span>
        </div>
        <h2 class="text-xl font-semibold mt-2">My Profile</h2>
        <p class="text-gray-300 mt-1">Update your account details and password.</p>
        <div class="mt-4 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-2 text-sm text-white backdrop-blur transition group-hover:border-cyan-400/50 group-hover:bg-white/10">
          Edit Profile →
        </div>
      </div>
    </a>

    <!-- Feedback -->
    <a href="<?= $BASE_URL ?>/pages/user/feedback.php"
       class="reveal group relative overflow-hidden rounded-3xl border border-white/10 bg-[#0B0F14]/70 backdrop-blur-xl p-6 shadow-[0_0_40px_rgba(0,255,200,0.08)] transition hover:border-emerald-400/40">
      <div class="absolute inset-0 bg-gradient-to-b from-black/0 via-black/20 to-black/60 pointer-events-none"></div>
      <div class="relative z-10">
        <div class="inline-flex items-center gap-2 text-sm text-emerald-200">
          <span class="text-lg">💬</span>
          <span>Tell us</span>
        </div>
        <h2 class="text-xl font-semibold mt-2">Feedback</h2>
        <p class="text-gray-300 mt-1">Let us know how we can improve your experience.</p>
        <div class="mt-4 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-2 text-sm text-white backdrop-blur transition group-hover:border-cyan-400/50 group-hover:bg-white/10">
          Give Feedback →
        </div>
      </div>
    </a>

    <!-- Quick Book -->
    <a href="<?= $BASE_URL ?>/pages/book.php"
       class="reveal group relative overflow-hidden rounded-3xl border border-white/10 bg-[#0B0F14]/70 backdrop-blur-xl p-6 shadow-[0_0_40px_rgba(0,255,200,0.08)] transition hover:border-emerald-400/40">
      <div class="absolute inset-0 bg-gradient-to-b from-black/0 via-black/20 to-black/60 pointer-events-none"></div>
      <div class="relative z-10">
        <div class="inline-flex items-center gap-2 text-sm text-emerald-200">
          <span class="text-lg">⚡</span>
          <span>Fast</span>
        </div>
        <h2 class="text-xl font-semibold mt-2">Quick Book</h2>
        <p class="text-gray-300 mt-1">Jump straight to booking and grab a slot.</p>
        <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-white text-black px-4 py-2 text-sm font-medium transition group-hover:shadow-[0_0_24px_rgba(250,204,21,0.45)]">
          Book Now →
        </div>
      </div>
    </a>

    <!-- Logout -->
    <a href="<?= $BASE_URL ?>/controllers/authcontroller.php?logout=1"
       class="reveal group relative overflow-hidden rounded-3xl border border-white/10 bg-[#0B0F14]/70 backdrop-blur-xl p-6 shadow-[0_0_40px_rgba(255,0,0,0.08)] transition hover:border-red-400/40">
      <div class="absolute inset-0 bg-gradient-to-b from-black/0 via-black/20 to-black/60 pointer-events-none"></div>
      <div class="relative z-10">
        <div class="inline-flex items-center gap-2 text-sm text-red-300">
          <span class="text-lg">🚪</span>
          <span>Sign out</span>
        </div>
        <h2 class="text-xl font-semibold mt-2">Logout</h2>
        <p class="text-gray-300 mt-1">You're done? Come back anytime to book again!</p>
        <div class="mt-4 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-2 text-sm text-white backdrop-blur transition group-hover:border-red-400/50 group-hover:bg-white/10">
          Logout →
        </div>
      </div>
    </a>
  </div>

  <!-- Tip / Info -->
  <div class="reveal mt-8 relative rounded-3xl border border-white/10 bg-gradient-to-r from-gray-900/70 to-gray-900/40 backdrop-blur-xl overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(60%_60%_at_50%_0%,rgba(16,185,129,0.12),transparent_70%)]"></div>
    <div class="relative p-6 md:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div class="max-w-3xl">
        <h3 class="text-xl md:text-2xl font-bold">Pro tip: Book off-peak for more choices</h3>
        <p class="text-gray-300 mt-1">
          Early mornings and late evenings fill up fast. Check the timetable first, then book to secure your preferred coach and slot.
        </p>
      </div>
      <a href="<?= $BASE_URL ?>/pages/timetable.php"
         class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-6 py-3 text-sm text-white backdrop-blur transition hover:border-emerald-400/50 hover:bg-white/10">
        View Timetable
      </a>
    </div>
  </div>
</section>

<!-- Reveal-on-scroll -->
<script>
  const revealEls = document.querySelectorAll('.reveal');
  const io = new IntersectionObserver((entries) => {
    entries.forEach((e) => {
      if (e.isIntersecting) {
        e.target.classList.add('opacity-100', 'translate-y-0');
        e.target.classList.remove('opacity-0', 'translate-y-6');
        io.unobserve(e.target);
      }
    });
  }, { threshold: 0.15 });

  revealEls.forEach((el) => {
    el.classList.add('opacity-0', 'translate-y-6', 'transition', 'duration-700', 'ease-out');
    io.observe(el);
  });
</script>

<?php include('../partials/footer.php'); ?>
