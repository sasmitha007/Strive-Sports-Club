</main>

<footer class="relative mt-auto">
  <!-- Ambient gradient glows behind the footer -->
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute -top-10 -left-24 w-80 h-80 bg-emerald-500/10 blur-3xl rounded-full"></div>
    <div class="absolute -bottom-16 -right-24 w-96 h-96 bg-cyan-500/10 blur-3xl rounded-full"></div>
  </div>

  <!-- Footer card -->
  <div class="border-t border-white/10 bg-[#0B0F14]/70 backdrop-blur-xl">
    <div class="max-w-7xl mx-auto px-4 py-10">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Brand / Tagline -->
        <div>
          <div class="text-2xl font-extrabold tracking-tight">🏸 Strive Sports Club</div>
          <p class="text-gray-300 mt-2">
            Premium indoor courts • Real-time schedule • Pro coaches.
          </p>
          <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-emerald-500/10 ring-1 ring-emerald-400/30 px-3 py-1 text-emerald-200 text-xs">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
            </span>
            Open for bookings
          </div>
        </div>

        <!-- Quick Links -->
        <div class="md:justify-self-center">
          <div class="font-semibold mb-3">Quick Links</div>
          <ul class="space-y-2 text-gray-300">
            <li><a href="<?= $BASE_URL ?>/pages/home.php" class="hover:text-white hover:underline underline-offset-4">Home</a></li>
            <li><a href="<?= $BASE_URL ?>/pages/sports_timetable.php" class="hover:text-white hover:underline underline-offset-4">Timetable</a></li>
            <li><a href="<?= $BASE_URL ?>/pages/book.php" class="hover:text-white hover:underline underline-offset-4">Book Session</a></li>
            <li><a href="<?= $BASE_URL ?>/pages/about.php" class="hover:text-white hover:underline underline-offset-4">About Us</a></li>
            <li><a href="<?= $BASE_URL ?>/pages/contact.php" class="hover:text-white hover:underline underline-offset-4">Contact Us</a></li>
          </ul>
        </div>

        <!-- Contact / Social -->
        <div class="md:justify-self-end">
          <div class="font-semibold mb-3">Contact</div>
          <ul class="space-y-2 text-gray-300">
            <li>📍 Colombo, Sri Lanka</li>
            <li>✉️ info@strivesports.club</li>
            <li>☎️ +94 71 000 0000</li>
          </ul>
          <div class="mt-4 flex items-center gap-2">
            <a href="#" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 hover:border-emerald-400/40 hover:bg-white/10 transition" aria-label="Facebook">𝔽</a>
            <a href="#" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 hover:border-emerald-400/40 hover:bg-white/10 transition" aria-label="Instagram">◎</a>
            <a href="#" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 hover:border-emerald-400/40 hover:bg-white/10 transition" aria-label="X">𝕏</a>
          </div>
        </div>
      </div>

      <!-- Divider -->
      <div class="mt-8 h-px bg-white/10"></div>

      <!-- Bottom bar -->
      <div class="mt-4 flex flex-col md:flex-row items-center justify-between gap-3 text-sm text-gray-400">
        <p>&copy; <?= date('Y') ?> Indoor Sports Club. All rights reserved.</p>
        <p class="text-gray-500">Built with ❤️ • Neon dark mode</p>
      </div>
    </div>
  </div>
</footer>

<?php if (!isset($BASE_URL)) { $BASE_URL = '/Strive%20Club'; } ?>
<script src="<?= $BASE_URL ?>/js/script.js?v=4"></script>

<!-- Back to Top Button (neon glass) -->
<button id="backToTopBtn"
  class="fixed bottom-6 right-6 opacity-0 translate-y-2 pointer-events-none
         bg-white text-black rounded-full px-4 py-2 shadow-[0_0_24px_rgba(250,204,21,0.35)]
         border border-yellow-400/40 transition-all duration-300 z-50 hover:shadow-[0_0_32px_rgba(250,204,21,0.55)]"
  aria-label="Back to top">
  ↑ Top
</button>

<script>
  // Back to Top show/hide + smooth scroll (no dependency)
  (function () {
    const btn = document.getElementById('backToTopBtn');
    if (!btn) return;

    const onScroll = () => {
      const show = window.scrollY > 200;
      btn.classList.toggle('opacity-0', !show);
      btn.classList.toggle('translate-y-2', !show);
      btn.classList.toggle('pointer-events-none', !show);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    btn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
      // subtle pulse after click
      btn.classList.add('animate-pulse');
      setTimeout(() => btn.classList.remove('animate-pulse'), 600);
    });
  })();
</script>

</body>
</html>
