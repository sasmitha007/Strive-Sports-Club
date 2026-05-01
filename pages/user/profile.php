<?php
include __DIR__ . '/../../partials/header.php';
require_once __DIR__ . '/../../core/db.php';

requireLogin(); // protect the page

$roleId = (int)($_SESSION['user']['role_id'] ?? 3);
$dashHref = '../dashboard.php';
if ($roleId === 1) $dashHref = '../admin/admindash.php';
elseif ($roleId === 2) $dashHref = '../couch/couchdash.php';

$db = Database::connect();
$userId = $_SESSION['user']['id'] ?? null;

// Load current user data
$stmt = $db->prepare("SELECT full_name, email, contact FROM users WHERE id = :id");
$stmt->execute(['id' => $userId]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$u) {
    $_SESSION['profile_error'] = "Could not load your profile.";
    header("Location: ../dashboard.php");
    exit();
}

// Split full name into first/last (fallbacks)
$first_name = '';
$last_name  = '';
if (!empty($u['full_name'])) {
    $parts = preg_split('/\s+/', trim($u['full_name']), 2);
    $first_name = $parts[0] ?? '';
    $last_name  = $parts[1] ?? '';
}

// Show errors/success (flash)
if (isset($_SESSION['profile_error'])) {
    echo "<div class='bg-red-600 text-white text-center py-2 px-4 font-medium mb-4'>"
        . htmlspecialchars($_SESSION['profile_error']) . "</div>";
    unset($_SESSION['profile_error']);
}
if (isset($_SESSION['profile_success'])) {
    echo "<div class='bg-green-600 text-white text-center py-2 px-4 font-medium mb-4'>"
        . htmlspecialchars($_SESSION['profile_success']) . "</div>";
    unset($_SESSION['profile_success']);
}
?>

<section class="max-w-md mx-auto px-4 py-10 min-h-[60vh]">
    <h1 class="text-3xl font-bold mb-6 text-center">Edit Your Profile</h1>

    <!-- EXACT same structure as register.php, just pre-filled + button text 'Update' -->
    <form action="../../controllers/usercontroller.php" method="POST" class="bg-gray-800 p-6 rounded-lg shadow-lg" autocomplete="off">
        <!-- We’ll detect this in the controller -->
        <input type="hidden" name="update_profile" value="1">

        <!-- First Name -->
        <div class="mb-4">
            <label for="first_name" class="block text-sm font-medium mb-2">First Name</label>
            <input type="text" name="first_name" id="first_name" required
                value="<?= htmlspecialchars($first_name) ?>"
                class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                placeholder="Your first name">
        </div>

        <!-- Last Name -->
        <div class="mb-4">
            <label for="last_name" class="block text-sm font-medium mb-2">Last Name</label>
            <input type="text" name="last_name" id="last_name" required
                value="<?= htmlspecialchars($last_name) ?>"
                class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                placeholder="Your last name">
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" id="email" required
                value="<?= htmlspecialchars($u['email']) ?>"
                class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                placeholder="example@email.com">
        </div>

        <!-- Contact Number -->
        <div class="mb-4">
            <label for="contact" class="block text-sm font-medium mb-2">Contact Number</label>
            <input type="text" name="contact" id="contact" required
                value="<?= htmlspecialchars($u['contact']) ?>"
                class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                placeholder="+94 7XXXXXXXX">
        </div>

        <!-- Password (optional here). Keep the same fields, but leave blank. -->
        <div class="mb-4 relative">
            <label for="password" class="block text-sm font-medium mb-2">Password (optional)</label>
            <input type="password" name="password" id="password" minlength="6"
                oninput="checkStrength && checkStrength(this.value)"
                class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 pr-12"
                placeholder="••••••••">
            <button type="button" onclick="togglePassword && togglePassword('password')" class="absolute right-3 top-9 text-gray-400 hover:text-yellow-400 focus:outline-none">
                👁️
            </button>
            <div id="strength-text" class="mt-2 text-sm font-medium"></div>
            <div id="strength-bar" class="h-2 w-full mt-1 rounded bg-gray-600">
                <div id="strength-fill" class="h-2 rounded transition-all duration-300"></div>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="mb-4 relative">
            <label for="password_confirm" class="block text-sm font-medium mb-2">Confirm Password</label>
            <input type="password" name="password_confirm" id="password_confirm" minlength="6"
                oninput="checkMatch && checkMatch()"
                class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 pr-12"
                placeholder="••••••••">
            <button type="button" onclick="togglePassword && togglePassword('password_confirm')" class="absolute right-3 top-9 text-gray-400 hover:text-yellow-400 focus:outline-none">
                👁️
            </button>
            <div id="match-text" class="mt-2 text-sm font-medium flex items-center space-x-2 transition duration-200 ease-in-out"></div>
        </div>

        <!-- Terms & Conditions (kept for visual parity; not enforced server-side) -->
        <div class="mb-4 flex items-center">
            <input type="checkbox" id="terms"
                class="w-4 h-4 text-yellow-400 bg-gray-700 border-gray-600 rounded focus:ring-yellow-400">
            <label for="terms" class="ml-2 text-sm text-gray-300">
                I agree to the
                <a href="../terms.php" target="_blank" class="text-yellow-400 hover:underline">Terms & Conditions</a>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="text-center mt-6">
            <button type="submit"
                class="bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-6 py-2 rounded-full shadow transition">
                Update
            </button>
        </div>

        <div class="text-sm text-gray-400 mt-4 text-center">
            <a href="<?= htmlspecialchars($dashHref) ?>" class="text-yellow-400 hover:underline">Back to dashboard</a>

        </div>
    </form>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>