<?php
require_once __DIR__ . '/../core/sessions.php';

// If not logged in: show inline message and auto-redirect to login with ?next=...
if (!isLoggedIn()) {
    $current  = $_SERVER['REQUEST_URI'] ?? '/Indoor%20sports%20club/pages/book.php';
    $loginUrl = '/Strive%20Club/pages/login.php?next=' . rawurlencode($current);

    http_response_code(401);
    header('Cache-Control: no-store');

    require_once __DIR__ . '/../partials/header.php';
    ?>
    <!-- HERO (not-logged-in) -->
    <header class="relative overflow-hidden rounded-b-3xl">
      <div class="pointer-events-none absolute inset-0">
        <div class="absolute -top-24 -left-28 w-[42rem] h-[42rem] bg-emerald-500/10 blur-3xl rounded-full"></div>
        <div class="absolute -bottom-24 -right-24 w-[46rem] h-[46rem] bg-purple-500/10 blur-3xl rounded-full"></div>
      </div>
      <div class="relative h-[45vh] flex items-center justify-center text-center">
        <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/60 to-black/80"></div>
        <div class="relative z-10 max-w-2xl mx-auto px-6">
          <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">Login Required</h1>
          <p class="text-gray-300 mt-3">You must log in to book a session. Redirecting you now…</p>
          <a class="inline-flex mt-6 items-center gap-2 rounded-full bg-white text-black px-6 py-3 text-sm font-medium hover:shadow-[0_0_30px_rgba(250,204,21,0.45)]"
             href="<?= htmlspecialchars($loginUrl, ENT_QUOTES) ?>">
            Go to Login
          </a>
        </div>
      </div>
    </header>

    <section class="max-w-2xl mx-auto px-4 py-12">
      <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur p-5 text-center">
        <p class="text-gray-300">
          If you’re not redirected automatically,&nbsp;
          <a class="underline text-emerald-300 hover:text-emerald-200"
             href="<?= htmlspecialchars($loginUrl, ENT_QUOTES) ?>">click here</a>.
        </p>
      </div>
      <script>
        setTimeout(() => { window.location.replace("<?= htmlspecialchars($loginUrl, ENT_QUOTES) ?>"); }, 1500);
      </script>
      <noscript>
        <p class="text-center text-sm text-gray-400 mt-4">JavaScript is disabled. Use the link above to continue.</p>
      </noscript>
    </section>
    <?php
    require_once __DIR__ . '/../partials/footer.php';
    exit;
}

// ---------- Logged-in path ----------
$today = date('Y-m-d');

// Flash once
$bookingError = $_SESSION['booking_error'] ?? '';
unset($_SESSION['booking_error']);

// Release session lock
session_write_close();

require_once __DIR__ . '/../partials/header.php';
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
      <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">Book a Session</h1>
      <p class="text-gray-300 mt-3">Choose your sport, date, and time — then lock it in.</p>
      <div class="mt-5 inline-flex items-center gap-2 rounded-full bg-emerald-500/10 ring-1 ring-emerald-400/30 px-3 py-1 text-emerald-200 text-xs">
        <span class="relative flex h-2 w-2">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
        </span>
        Real-time slot availability
      </div>
    </div>
  </div>
</header>

<section class="max-w-3xl mx-auto px-4 py-10">
  <!-- Toast for server-side flash error -->
  <?php if (!empty($bookingError)): ?>
    <div class="mb-6 rounded-xl border border-red-400/40 bg-red-500/10 text-red-200 px-4 py-3">
      ⚠ <?= htmlspecialchars($bookingError) ?>
    </div>
  <?php endif; ?>

  <!-- Booking card -->
  <div class="relative rounded-3xl border border-white/10 bg-[#0B0F14]/70 backdrop-blur-xl shadow-[0_0_40px_rgba(0,255,200,0.08)] overflow-hidden">
    <div class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-emerald-400/15 via-cyan-400/15 to-purple-400/15 blur-2xl"></div>

    <div class="relative p-6 md:p-8">
      <!-- Steps -->
      <ol class="flex flex-wrap gap-2 mb-6 text-xs">
        <li class="inline-flex items-center gap-2 rounded-full bg-white/5 border border-white/10 px-3 py-1">
          <span class="h-5 w-5 grid place-items-center rounded-full bg-white text-black text-[10px]">1</span>
          Choose Sport
        </li>
        <li class="inline-flex items-center gap-2 rounded-full bg-white/5 border border-white/10 px-3 py-1">
          <span class="h-5 w-5 grid place-items-center rounded-full bg-white text-black text-[10px]">2</span>
          Select Date
        </li>
        <li class="inline-flex items-center gap-2 rounded-full bg-white/5 border border-white/10 px-3 py-1">
          <span class="h-5 w-5 grid place-items-center rounded-full bg-white text-black text-[10px]">3</span>
          Pick Time
        </li>
      </ol>

      <form action="../controllers/bookingcontroller.php" method="POST" class="space-y-5" autocomplete="off">
        <input type="hidden" name="book" value="1">

        <!-- Sport -->
        <div>
          <label for="sport" class="block text-sm text-gray-300 mb-2">Choose a Sport</label>
          <select name="sport" id="sport" required
                  class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder-gray-500
                         focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400/40 transition">
            <option value="">-- Select Sport --</option>
            <option value="Badminton">🏸 Badminton</option>
            <option value="Basketball">🏀 Basketball</option>
            <option value="Futsal">⚽ Futsal</option>
            <option value="Boxing">🥊 Boxing</option>
            <option value="Indoor Climbing">🧗 Indoor Climbing</option>
            <option value="Table Tennis">🏓 Table Tennis</option>
            <option value="Volleyball">🏐 Volleyball</option>
          </select>
        </div>

        <!-- Date -->
        <div>
          <label for="date" class="block text-sm text-gray-300 mb-2">Select Date</label>
          <input type="date" name="date" id="date" required
                 min="<?= htmlspecialchars($today) ?>" value="<?= htmlspecialchars($today) ?>"
                 class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder-gray-500
                        focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400/40 transition">
        </div>

        <!-- Time slot -->
        <div>
          <div class="flex items-center justify-between">
            <label for="timetable_id" class="block text-sm text-gray-300 mb-2">Select Time Slot</label>
            <span id="slotStatus" class="text-xs text-gray-500"></span>
          </div>

          <div class="relative">
            <select name="timetable_id" id="timetable_id" required disabled
                    class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder-gray-500
                           focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400/40 transition">
              <option value="">-- Select Time --</option>
            </select>

            <!-- Loading shimmer -->
            <div id="slotSkeleton" class="hidden absolute inset-0 rounded-xl bg-white/5 overflow-hidden pointer-events-none">
              <div class="animate-pulse h-full w-full">
                <div class="h-full w-full bg-white/10"></div>
              </div>
            </div>
          </div>

          <p id="slotHelp" class="text-xs text-gray-400 mt-2 hidden">Showing all time slots for the selected sport.</p>
          <p id="slotEmpty" class="text-xs text-red-300 mt-2 hidden">No slots available for that selection. Please choose a different day or sport.</p>
        </div>

        <div class="pt-2 text-center">
          <button id="submitBtn" type="submit" disabled
                  class="group relative inline-flex items-center gap-2 rounded-full bg-white text-black px-8 py-3 text-sm font-medium
                         transition disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-[0_0_30px_rgba(250,204,21,0.45)]">
            <span class="absolute inset-0 -z-10 rounded-full border border-yellow-400/40"></span>
            Confirm Booking
          </button>
        </div>
      </form>

      <div class="mt-6 text-xs text-gray-500">
        By booking, you agree to our policies. Please arrive 10 minutes early to warm up.
      </div>
    </div>
  </div>
</section>

<script>
(function () {
  const sport   = document.getElementById('sport');
  const dateEl  = document.getElementById('date');
  const select  = document.getElementById('timetable_id');
  const help    = document.getElementById('slotHelp');
  const empty   = document.getElementById('slotEmpty');
  const status  = document.getElementById('slotStatus');
  const skeleton= document.getElementById('slotSkeleton');
  const submit  = document.getElementById('submitBtn');

  function setLoading(isLoading) {
    if (isLoading) {
      skeleton.classList.remove('hidden');
      status.textContent = 'Loading…';
    } else {
      skeleton.classList.add('hidden');
      status.textContent = '';
    }
  }

  function resetSlots() {
    select.innerHTML = '<option value="">-- Select Time --</option>';
    select.disabled = true;
    submit.disabled = true;
    help.classList.add('hidden');
    empty.classList.add('hidden');
  }

  async function loadSlots() {
    resetSlots();
    if (!sport.value) return;

    setLoading(true);
    try {
      const params = new URLSearchParams({
        slots: '1',
        sport: sport.value
      });
      // If your endpoint also filters by date, add: params.set('date', dateEl.value);
      const url = `../controllers/bookingcontroller.php?${params.toString()}`;

      const res = await fetch(url, { credentials: 'same-origin' });

      if (res.status === 401) {
        alert('Please log in to view available slots.');
        return;
      }

      const data = await res.json(); // { ok:true, slots:[{id,label}] }
      if (data && data.ok && Array.isArray(data.slots) && data.slots.length) {
        data.slots.forEach(s => {
          const opt = document.createElement('option');
          opt.value = s.id;
          opt.textContent = s.label; // e.g., "Friday 16:00 - 18:00 (Coach)"
          select.appendChild(opt);
        });
        select.disabled = false;
        help.classList.remove('hidden');
      } else {
        empty.classList.remove('hidden');
      }
    } catch (e) {
      console.error(e);
      empty.classList.remove('hidden');
      empty.textContent = 'Could not load time slots. Please try again.';
    } finally {
      setLoading(false);
    }
  }

  // Enable submit when a valid slot is chosen
  select.addEventListener('change', () => {
    submit.disabled = !select.value;
  });

  sport.addEventListener('change', loadSlots);
  // Optional: re-load when date changes, if your API supports it
  // dateEl.addEventListener('change', loadSlots);
})();
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>