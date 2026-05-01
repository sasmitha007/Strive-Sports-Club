<?php
require_once __DIR__ . '/../core/sessions.php';

// If not logged in: show inline message and auto-redirect to login with ?next=...
if (!isLoggedIn()) {
    $current  = $_SERVER['REQUEST_URI'] ?? '/Indoor%20sports%20club/pages/book.php';
    $loginUrl = '/Indoor%20sports%20club/pages/login.php?next=' . rawurlencode($current);

    http_response_code(401);           // semantic: not authorized
    header('Cache-Control: no-store'); // avoid caching this state

    require_once __DIR__ . '/../partials/header.php';
    ?>
    <section class="max-w-2xl mx-auto px-4 py-20 min-h-[60vh]">
      <h1 class="text-2xl font-semibold mb-4 text-center text-red-400">Login required</h1>
      <p class="text-center mb-6">
        You must log in to book a session. Redirecting you to the login page…
      </p>
      <p class="text-center">
        <a class="underline text-yellow-400 hover:text-yellow-300"
           href="<?= htmlspecialchars($loginUrl, ENT_QUOTES) ?>">Click here if you’re not redirected</a>
      </p>
      <script>
        // Auto-redirect after a short delay
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

// You only read session data on this page; release the lock
session_write_close();

require_once __DIR__ . '/../partials/header.php';
?>
<section class="max-w-2xl mx-auto px-4 py-10 min-h-[60vh]">
  <h1 class="text-3xl font-bold mb-6 text-center">Book a Session</h1>

  <?php if (!empty($bookingError)): ?>
    <div class="mb-6 rounded-lg bg-red-900/40 border border-red-700 text-red-200 p-3">
      <?= htmlspecialchars($bookingError) ?>
    </div>
  <?php endif; ?>

  <form action="../controllers/bookingcontroller.php" method="POST" class="bg-gray-800 p-6 rounded-lg shadow-md" autocomplete="off">
    <input type="hidden" name="book" value="1">

    <!-- Sport -->
    <div class="mb-4">
      <label for="sport" class="block text-sm font-medium mb-2">Choose a Sport</label>
      <select name="sport" id="sport" required
              class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400">
        <option value="">-- Select Sport --</option>
        <option value="Badminton">Badminton</option>
        <option value="Basketball">Basketball</option>
        <option value="Futsal">Futsal</option>
        <option value="Boxing">Boxing</option>
        <option value="Indoor Climbing">Indoor Climbing</option>
        <option value="Table Tennis">Table Tennis</option>
        <option value="Volleyball">Volleyball</option>
      </select>
    </div>

    <!-- Date -->
    <div class="mb-4">
      <label for="date" class="block text-sm font-medium mb-2">Select Date</label>
      <input type="date" name="date" id="date" required
             min="<?= htmlspecialchars($today) ?>" value="<?= htmlspecialchars($today) ?>"
             class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400">
    </div>

    <!-- Time slot (loaded after sport selection) -->
    <div class="mb-4">
      <label for="timetable_id" class="block text-sm font-medium mb-2">Select Time Slot</label>
      <select name="timetable_id" id="timetable_id" required disabled
              class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400">
        <option value="">-- Select Time --</option>
      </select>
      <p id="slotHelp" class="text-xs text-gray-400 mt-1 hidden">Showing all time slots for the selected sport.</p>
    </div>

    <div class="text-center mt-6">
      <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-6 py-2 rounded-full shadow transition">
        Confirm Booking
      </button>
    </div>
  </form>
</section>

<script>
(function () {
  const sport  = document.getElementById('sport');
  const select = document.getElementById('timetable_id');
  const help   = document.getElementById('slotHelp');

  async function loadSlots() {
    select.innerHTML = '<option value="">-- Select Time --</option>';
    select.disabled = true;
    help.classList.add('hidden');

    if (!sport.value) return;

    try {
      const url = `../controllers/bookingcontroller.php?slots=1&sport=${encodeURIComponent(sport.value)}`;
      const res = await fetch(url, { credentials: 'same-origin' });

      // If the controller returns 401 when not logged in, handle gracefully
      if (res.status === 401) {
        alert('Please log in to view available slots.');
        return;
      }

      const data = await res.json(); // { ok:true, slots:[{id,label}] }
      if (data && data.ok && Array.isArray(data.slots) && data.slots.length) {
        data.slots.forEach(s => {
          const opt = document.createElement('option');
          opt.value = s.id;        // timetable_id
          opt.textContent = s.label; // "Friday 16:00 - 18:00 (Coach)"
          select.appendChild(opt);
        });
        select.disabled = false;
        help.classList.remove('hidden');
      }
    } catch (e) {
      console.error(e);
      alert('Could not load time slots. Please try again.');
    }
  }

  sport.addEventListener('change', loadSlots);
})();
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>