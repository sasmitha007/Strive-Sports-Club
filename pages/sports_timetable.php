<?php include('../partials/header.php'); ?>

<?php
// --- Data prep (kept above the HTML so we can show a nice UI error box) ---
$uiError = null;
$rows = $days = $slots = $grid = [];

try {
    // Use your shared DB if available, else create a direct PDO connection.
    if (!isset($pdo)) {
        // If you have a central DB file, uncomment the next line:
        // require_once __DIR__ . '/../config/db.php';

        if (!isset($pdo)) {
            $dsn = 'pgsql:host=localhost;port=5432;dbname=isports_club';
            $pdo = new PDO($dsn, 'postgres', 'UOG0723002', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        }
    }

    // Match the exact columns used in timetable.php
    $wantedDays = ['Tuesday','Wednesday','Friday','Saturday','Sunday'];
    // Create a safe IN (...) list
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

    // Fixed column order to mirror timetable.php
    $days = $wantedDays;

    // Build slots and grid
    $slots = [];   // key => ['start'=>..., 'end'=>...]
    $grid  = [];   // $grid[$slotKey][$day] = [ 'Sport — Coach', ... ]

    foreach ($rows as $r) {
        $slotKey = $r['start_time'].'|'.$r['end_time'];
        if (!isset($slots[$slotKey])) {
            $slots[$slotKey] = ['start'=>$r['start_time'], 'end'=>$r['end_time']];
        }
        $label = $r['sport_name'] . ' — ' . $r['coach'];
        $grid[$slotKey][$r['day_of_week']][] = $label;
    }

    // Sort slots by start time to keep rows tidy
    uasort($slots, fn($a,$b)=>strcmp($a['start'], $b['start']));

    // Helper to format "8:00 AM – 10:00 AM"
    $fmt = function($t) { return date('g:i A', strtotime($t)); };

} catch (Throwable $e) {
    $uiError = 'Could not load the timetable right now. Please try again later.';
    error_log('Timetable error: '.$e->getMessage());
    // Keep $days empty so the table shows a friendly message
}
?>

<!-- Timetable Banner Image with Dark Overlay -->
<div class="relative w-full h-64 mb-8 rounded-lg overflow-hidden shadow-lg">
    <!-- Image -->
    <img src="../img/time.jpg" alt="Sports Timetable Banner" class="w-full h-full object-cover">

    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>
</div>

<!-- Grid layout -->
<section class="max-w-6xl mx-auto px-4 py-10 min-h-[60vh] grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Timetable -->
    <div class="md:col-span-2">
        <h1 class="text-3xl font-bold mb-4 text-center">Weekly Sports Timetable</h1>

        <?php if ($uiError): ?>
            <div class="mb-6 rounded-lg border border-red-500 bg-red-600/20 text-red-200 px-4 py-3">
                <?= htmlspecialchars($uiError) ?>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse border border-gray-600">
                <thead class="bg-gray-700 text-white">
                    <tr>
                        <th class="border border-gray-600 px-4 py-2">Time</th>
                        <?php foreach ($days as $d): ?>
                            <th class="border border-gray-600 px-4 py-2"><?= htmlspecialchars($d) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 text-white">
                    <?php if (empty($slots)): ?>
                        <tr>
                            <td colspan="<?= 1 + count($days) ?>" class="border border-gray-700 px-4 py-6 text-center text-gray-300">
                                No sessions scheduled yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($slots as $slotKey => $se): ?>
                            <tr>
                                <td class="border border-gray-700 px-4 py-2 font-semibold">
                                    <?= htmlspecialchars($fmt($se['start']).' – '.$fmt($se['end'])) ?>
                                </td>
                                <?php foreach ($days as $d): ?>
                                    <td class="border border-gray-700 px-4 py-2 align-top">
                                        <?php if (!empty($grid[$slotKey][$d])): ?>
                                            <?php foreach ($grid[$slotKey][$d] as $line): ?>
                                                <div><?= htmlspecialchars($line) ?></div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-gray-400">—</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
