<?php include('../partials/header.php'); ?>

<?php
// ---------- Data prep ----------
date_default_timezone_set('Asia/Colombo');

$uiError = null;
$rows = $days = $slots = $grid = [];

try {
    if (!isset($pdo)) {
        // require_once __DIR__ . '/../config/db.php'; // if you prefer central config
        if (!isset($pdo)) {
            $dsn = 'pgsql:host=localhost;port=5432;dbname=isports_club';
            $pdo = new PDO($dsn, 'postgres', 'UOG0723002', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        }
    }

    // Keep the same 5-day layout as your static page
    $wantedDays = ['Tuesday','Wednesday','Friday','Saturday','Sunday'];
    $inDays = "'" . implode("','", array_map(fn($d)=>str_replace("'", "''", $d), $wantedDays)) . "'";

    $stmt = $pdo->query("
        SELECT
          st.day_of_week,
          st.start_time,
          st.end_time,
          s.sport_name,
          u.full_name AS coach
        FROM sport_timetable st
        JOIN sports s ON s.id = st.sport_id
        JOIN users  u ON u.id = st.coach_id
        WHERE st.day_of_week IN ($inDays)
        ORDER BY st.start_time, st.end_time, st.day_of_week
    ");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $days = $wantedDays;

    // Build slots and grid (store objects so we can style sport/coach separately)
    $slots = [];   // key => ['start'=>..., 'end'=>...]
    $grid  = [];   // $grid[$slotKey][$day] = [ ['sport'=>..., 'coach'=>...], ... ]

    foreach ($rows as $r) {
        $slotKey = $r['start_time'].'|'.$r['end_time'];
        if (!isset($slots[$slotKey])) {
            $slots[$slotKey] = ['start'=>$r['start_time'], 'end'=>$r['end_time']];
        }
        $grid[$slotKey][$r['day_of_week']][] = [
            'sport' => $r['sport_name'],
            'coach' => $r['coach'],
        ];
    }

    // Sort by start time
    uasort($slots, fn($a,$b)=>strcmp($a['start'], $b['start']));

    // Helpers
    $fmt = fn($t) => date('g:i A', strtotime($t));

    // Simple sport icon map (emoji = zero-dependency + readable)
    $sportIcons = [
        'badminton'    => '🏸',
        'basketball'   => '🏀',
        'volleyball'   => '🏐',
        'futsal'       => '⚽',
        'table tennis' => '🏓',
        'boxing'       => '🥊',
        'climbing'     => '🧗',
    ];
    $iconFor = function($name) use ($sportIcons) {
        $k = strtolower(trim($name));
        return $sportIcons[$k] ?? '🧗';
    };

    // "Live now" highlight
    $nowDay  = date('l');
    $nowTime = date('H:i:s');

} catch (Throwable $e) {
    $uiError = 'Could not load the timetable right now. Please try again later.';
    error_log('Timetable error: '.$e->getMessage());
}
?>

<!-- Futuristic Banner with gradient + glow -->
<div class="relative mb-10">
  <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 via-cyan-400/10 to-purple-500/10 blur-3xl rounded-3xl"></div>
  <div class="relative w-full h-64 rounded-3xl overflow-hidden border border-white/10 shadow-[0_0_60px_rgba(16,185,129,0.15)]">
    <img src="../img/time.jpg" alt="Sports Timetable Banner" class="w-full h-full object-cover opacity-70">
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/60 to-black/80"></div>
    <div class="absolute inset-x-0 bottom-0 p-6 md:p-8">
      <h1 class="text-2xl md:text-4xl font-extrabold tracking-tight">
        Weekly Sports Timetable
      </h1>
      <p class="text-sm md:text-base text-gray-300 mt-2">
        Real-time schedule • Neon dark mode • Coach assignments
      </p>
    </div>
  </div>
</div>

<section class="max-w-7xl mx-auto px-4 pb-16">
  <!-- Controls -->
  <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div class="flex items-center gap-3">
      <span class="inline-flex items-center gap-2 rounded-full bg-emerald-500/10 ring-1 ring-emerald-400/30 px-3 py-1 text-emerald-200 text-xs md:text-sm">
        <span class="relative flex h-2 w-2">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
        </span>
        Live highlights enabled
      </span>
      <?php if ($uiError): ?>
        <span class="inline-flex items-center gap-2 rounded-full bg-red-500/10 ring-1 ring-red-400/30 px-3 py-1 text-red-200 text-xs md:text-sm">
          ⚠ <?= htmlspecialchars($uiError) ?>
        </span>
      <?php endif; ?>
    </div>

    <div class="flex items-center gap-3">
      <label for="dayFilter" class="text-sm text-gray-300">Filter by day</label>
      <select id="dayFilter" class="bg-gray-900/70 border border-white/10 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/40">
        <option value="All">All days</option>
        <?php foreach ($days as $d): ?>
          <option value="<?= htmlspecialchars($d) ?>"><?= htmlspecialchars($d) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- Table card (glass + neon) -->
<div class="rounded-3xl border border-white/10 bg-[#0B0F14]/70 backdrop-blur-xl shadow-[0_0_40px_rgba(0,255,200,0.08)] overflow-hidden">
        <div class="overflow-x-auto">
        <table id="timetable" class="min-w-full text-sm md:text-base">
            <thead class="sticky top-0 z-20">
                <tr class="bg-gray-900/80 backdrop-blur supports-[backdrop-filter]:bg-gray-900/60">
                    <th class="sticky left-0 z-30 bg-gray-900/90 backdrop-blur px-4 py-3 font-semibold text-left border-b border-white/10">
                    ⏱ Time
                    </th>
                    <?php foreach ($days as $d): ?>
                    <th data-day="<?= htmlspecialchars($d) ?>" class="px-4 py-3 font-semibold text-left border-b border-white/10">
                        <?= htmlspecialchars($d) ?>
                    </th>
                    <?php endforeach; ?>
                </tr>
                </thead>

                <tbody class="divide-y divide-white/5">
                <?php if (empty($slots)): ?>
                <tr>
                    <td colspan="<?= 1 + count($days) ?>" class="px-6 py-10 text-center text-gray-400">
                    No sessions scheduled yet.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($slots as $slotKey => $se): ?>
                    <tr class="hover:bg-white/5 transition-colors">
                    <!-- Sticky time column -->
                    <td class="sticky left-0 z-10 bg-gray-900/70 px-4 py-3 font-semibold whitespace-nowrap border-b border-white/5">
                        <span class="inline-flex items-center gap-2">
                        <span class="text-gray-300"><?= htmlspecialchars($fmt($se['start'])) ?></span>
                        <span class="text-gray-500">–</span>
                        <span class="text-gray-300"><?= htmlspecialchars($fmt($se['end'])) ?></span>
                        </span>
                    </td>

                    <?php foreach ($days as $d): ?>
                        <?php
                        $isLive = ($d === $nowDay && $nowTime >= $se['start'] && $nowTime < $se['end']);
                        $cellClasses = $isLive
                            ? 'bg-emerald-500/10 ring-1 ring-emerald-400/30 shadow-[0_0_24px_rgba(16,185,129,0.25)] animate-pulse'
                            : 'bg-transparent';
                        ?>
                        <td
                        data-day="<?= htmlspecialchars($d) ?>"
                        class="align-top px-4 py-3 border-b border-white/5 <?= $cellClasses ?>">

                        <?php if (!empty($grid[$slotKey][$d])): ?>
                            <div class="flex flex-col gap-2">
                            <?php foreach ($grid[$slotKey][$d] as $item): ?>
                                <?php
                                $icon = $iconFor($item['sport']);
                                ?>
                                <div class="group flex items-center gap-2 rounded-xl bg-white/5 border border-white/10 px-3 py-2 hover:border-emerald-400/40 hover:bg-white/10 transition">
                                <span class="text-lg" aria-hidden="true"><?= $icon ?></span>
                                <div class="min-w-0">
                                    <div class="font-medium leading-tight">
                                    <?= htmlspecialchars($item['sport']) ?>
                                    </div>
                                    <div class="text-xs text-gray-400">
                                    Coach: <span class="text-gray-300"><?= htmlspecialchars($item['coach']) ?></span>
                                    </div>
                                </div>
                                </div>
                            <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <span class="text-gray-500">—</span>
                        <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
    </div>

    <!-- Legend -->
    <div class="px-4 md:px-6 py-4 flex flex-wrap items-center gap-4 border-t border-white/10 bg-gradient-to-r from-gray-900/60 to-gray-900/40">
    <span class="inline-flex items-center gap-2 text-xs text-gray-300">
        <span class="h-3 w-3 rounded ring-1 ring-emerald-400/30 bg-emerald-500/20"></span> Live slot
    </span>
    <span class="text-xs text-gray-400">Sticky header & first column • Scroll horizontally on mobile</span>
    </div>
</div>
</section>

<!-- Day filter script -->
<script>
    (function() {
        const sel = document.getElementById('dayFilter');
        if (!sel) return;
        sel.addEventListener('change', () => {
        const day = sel.value;
        const table = document.getElementById('timetable');
        if (!table) return;

        // Show all
        const allTh = table.querySelectorAll('thead th[data-day]');
        const allTds = table.querySelectorAll('tbody td[data-day]');
        allTh.forEach(th => th.classList.remove('hidden'));
        allTds.forEach(td => td.classList.remove('hidden'));

        if (day === 'All') return;

        // Hide columns not matching the selected day
        allTh.forEach(th => {
            if (th.dataset.day !== day) th.classList.add('hidden');
        });
        allTds.forEach(td => {
            if (td.dataset.day !== day) td.classList.add('hidden');
        });
        });
    })();
</script>

<?php include('../partials/footer.php'); ?>