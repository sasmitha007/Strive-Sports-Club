<?php
require_once __DIR__ . '/../core/sessions.php';
$BASE_URL = '/Strive%20club';
$BASE = rtrim($BASE_URL, '/');

// figure out current path for active state
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);

// helper to mark active link
function is_active_path($target, $currentPath) {
    // accept encoded and decoded versions
    return $currentPath === $target || $currentPath === urldecode($target);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Indoor Sports Club | Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= $BASE ?>/style.css">
    <meta name="theme-color" content="#0b0f14">
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col" style="font-family: 'Playfair Display', serif;">

<!-- NAVBAR (glass + neon, sticky) -->
<nav class="sticky top-0 z-50 border-b border-white/10 backdrop-blur-xl bg-[#0B0F14]/70 shadow-[0_0_40px_rgba(0,255,200,0.08)]">
  <div class="relative">
    <!-- Ambient glow -->
    <div class="pointer-events-none absolute inset-0">
      <div class="absolute -top-8 -left-24 w-72 h-72 bg-emerald-500/10 blur-3xl rounded-full"></div>
      <div class="absolute -bottom-10 -right-24 w-80 h-80 bg-cyan-500/10 blur-3xl rounded-full"></div>
    </div>

    <div class="relative container mx-auto px-4 py-4 flex items-center justify-between">
      <!-- LOGO LEFT -->
      <div class="flex items-center gap-3">
        <a href="<?= $BASE ?>/pages/home.php" class="text-2xl font-bold tracking-tight">
          <span class="align-middle">🏸</span>
          <span class="align-middle">Strive Club</span>
        </a>
      </div>

      <!-- DESKTOP NAV -->
      <?php
        $links = [
          ['label' => 'Home',        'href' => $BASE.'/pages/home.php'],
          ['label' => 'About Us',    'href' => $BASE.'/pages/about.php'],
          ['label' => 'Contact Us',  'href' => $BASE.'/pages/contact.php'],
          // keep your current target; change to sports_timetable.php if that’s the page you use
          ['label' => 'Timetable',   'href' => $BASE.'/pages/timetable.php'],
          ['label' => 'Book Session','href' => $BASE.'/pages/book.php'],
        ];
      ?>
      <div class="hidden md:flex items-center gap-2">
        <?php foreach ($links as $ln): ?>
          <?php
            $active = is_active_path(parse_url($ln['href'], PHP_URL_PATH), $currentPath);
            $cls = $active
              ? 'text-emerald-300 border-emerald-400/60'
              : 'text-gray-300 border-transparent hover:text-white hover:border-emerald-400/40';
          ?>
          <a href="<?= htmlspecialchars($ln['href']) ?>"
             class="px-3 py-2 rounded-xl border transition <?= $cls ?>">
            <?= htmlspecialchars($ln['label']) ?>
          </a>
        <?php endforeach; ?>

        <?php if (isset($_SESSION['user'])): ?>
          <a href="<?= $BASE ?>/pages/dashboard.php"
             class="px-3 py-2 rounded-xl border border-white/10 bg-white/5 hover:border-cyan-400/50 hover:bg-white/10 transition">
            Dashboard
          </a>
          <a href="<?= $BASE ?>/controllers/authcontroller.php?logout=1"
             class="px-3 py-2 rounded-xl bg-white text-black hover:shadow-[0_0_24px_rgba(250,204,21,0.45)] transition">
            Logout
          </a>
        <?php else: ?>
          <a href="<?= $BASE ?>/pages/login.php"
             class="px-3 py-2 rounded-xl border border-white/10 bg-white/5 hover:border-cyan-400/50 hover:bg-white/10 transition">
            Login
          </a>
          <a href="<?= $BASE ?>/pages/register.php"
             class="px-3 py-2 rounded-xl bg-white text-black hover:shadow-[0_0_24px_rgba(250,204,21,0.45)] transition">
            Register
          </a>
        <?php endif; ?>
      </div>

      <!-- MOBILE TOGGLE -->
      <button id="navToggle"
              class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10"
              aria-expanded="false" aria-controls="mobileNav">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>

    <!-- MOBILE NAV -->
    <div id="mobileNav" class="md:hidden hidden border-t border-white/10 bg-[#0B0F14]/80 backdrop-blur-xl">
      <div class="px-4 py-3 flex flex-col gap-2">
        <?php foreach ($links as $ln): ?>
          <?php
            $active = is_active_path(parse_url($ln['href'], PHP_URL_PATH), $currentPath);
            $cls = $active
              ? 'text-emerald-300 border-emerald-400/60'
              : 'text-gray-300 border-white/10 hover:text-white hover:border-emerald-400/40';
          ?>
          <a href="<?= htmlspecialchars($ln['href']) ?>"
             class="px-3 py-2 rounded-xl border <?= $cls ?>">
            <?= htmlspecialchars($ln['label']) ?>
          </a>
        <?php endforeach; ?>

        <div class="h-px bg-white/10 my-1"></div>

        <?php if (isset($_SESSION['user'])): ?>
          <a href="<?= $BASE ?>/pages/dashboard.php"
             class="px-3 py-2 rounded-xl border border-white/10 bg-white/5">Dashboard</a>
          <a href="<?= $BASE ?>/controllers/authcontroller.php?logout=1"
             class="px-3 py-2 rounded-xl bg-white text-black">Logout</a>
        <?php else: ?>
          <a href="<?= $BASE ?>/pages/login.php"
             class="px-3 py-2 rounded-xl border border-white/10 bg-white/5">Login</a>
          <a href="<?= $BASE ?>/pages/register.php"
             class="px-3 py-2 rounded-xl bg-white text-black">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<script>
  // Mobile menu toggle
  (function () {
    const btn = document.getElementById('navToggle');
    const menu = document.getElementById('mobileNav');
    if (!btn || !menu) return;
    btn.addEventListener('click', () => {
      const open = menu.classList.toggle('hidden') === false;
      btn.setAttribute('aria-expanded', String(open));
    });
  })();
</script>

<main class="flex-grow">