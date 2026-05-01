<?php include("../partials/header.php"); ?>

<!-- HERO -->
<header class="relative overflow-hidden rounded-b-3xl">
  <!-- Ambient gradient glows -->
  <div class="pointer-events-none absolute inset-0">
    <div class="absolute -top-24 -left-28 w-[42rem] h-[42rem] bg-emerald-500/10 blur-3xl rounded-full"></div>
    <div class="absolute -bottom-24 -right-24 w-[46rem] h-[46rem] bg-purple-500/10 blur-3xl rounded-full"></div>
  </div>

  <div class="relative h-[60vh] flex items-center justify-center text-center">
    <img src="<?= $BASE_URL ?>/img/abtus.jpg" class="absolute inset-0 w-full h-full object-cover opacity-80" alt="About Us Background">
    <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/60 to-black/80"></div>
    <div class="relative z-10 max-w-3xl mx-auto px-6">
      <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">About Us</h1>
      <p class="text-lg md:text-xl text-gray-300 mt-3">Your favorite place to play, grow, and compete indoors.</p>
      <div class="mt-5 inline-flex items-center gap-2 rounded-full bg-emerald-500/10 ring-1 ring-emerald-400/30 px-3 py-1 text-emerald-200 text-xs">
        <span class="relative flex h-2 w-2">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
        </span>
        Open for bookings • Pro coaches
      </div>
    </div>
  </div>
</header>

<!-- VISION -->
<section class="max-w-7xl mx-auto px-4 py-12">
  <div class="reveal relative rounded-3xl border border-white/10 bg-[#0B0F14]/70 backdrop-blur-xl overflow-hidden">
    <div class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-emerald-400/15 via-cyan-400/15 to-purple-400/15 blur-2xl"></div>
    <div class="relative p-6 md:p-10">
      <div class="text-center max-w-4xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">
          Our Vision
        </h2>
        <p class="text-lg text-gray-300 mt-4 leading-relaxed">
          At Strive Sports Club, we promote health, friendship, and teamwork through sport. Whether you’re a seasoned athlete or just starting out, our modern indoor facilities make it easy and fun to stay active—rain or shine.
        </p>

        <!-- Vision feature cards (now hoverable) -->
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4 text-left">
          <div class="rounded-2xl border border-white/10 bg-white/5 p-4 transition-all duration-300 transform hover:-translate-y-1 hover:border-emerald-400/40 hover:bg-white/10 hover:shadow-[0_20px_50px_rgba(16,185,129,0.15)]">
            <div class="text-2xl">🏟️</div>
            <div class="font-semibold mt-2">Premium Facilities</div>
            <p class="text-sm text-gray-400 mt-1">Climate-controlled indoor courts across multiple sports.</p>
          </div>
          <div class="rounded-2xl border border-white/10 bg-white/5 p-4 transition-all duration-300 transform hover:-translate-y-1 hover:border-emerald-400/40 hover:bg-white/10 hover:shadow-[0_20px_50px_rgba(16,185,129,0.15)]">
            <div class="text-2xl">🤝</div>
            <div class="font-semibold mt-2">Inclusive Community</div>
            <p class="text-sm text-gray-400 mt-1">All skill levels welcome—learn, play, and grow together.</p>
          </div>
          <div class="rounded-2xl border border-white/10 bg-white/5 p-4 transition-all duration-300 transform hover:-translate-y-1 hover:border-emerald-400/40 hover:bg-white/10 hover:shadow-[0_20px_50px_rgba(16,185,129,0.15)]">
            <div class="text-2xl">⚡</div>
            <div class="font-semibold mt-2">Easy Booking</div>
            <p class="text-sm text-gray-400 mt-1">Reserve your slot in seconds from your phone.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- WHAT WE OFFER -->
<section class="max-w-7xl mx-auto px-4 py-8">
  <h3 class="reveal text-center text-3xl md:text-4xl font-extrabold tracking-tight mb-8">
    <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-300 via-cyan-300 to-purple-300">
      What We Offer
    </span>
  </h3>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
    <?php
      $sports = [
        ['name' => 'Badminton',       'img' => 'img/badminton.jpg', 'desc' => 'High-quality indoor courts for singles and doubles with flexible bookings.'],
        ['name' => 'Basketball',      'img' => 'img/basketball.jpg','desc' => 'Friendly matches, skill drills, and mini 3-on-3 tournaments.'],
        ['name' => 'Futsal',          'img' => 'img/futsal.jpg',    'desc' => 'Fast-paced 5-a-side action on premium indoor turf.'],
        ['name' => 'Table Tennis',    'img' => 'img/tblt.jpg',      'desc' => 'Professional-grade tables for rapid rallies and fun leagues.'],
        ['name' => 'Volleyball',      'img' => 'img/vol.jpg',       'desc' => 'Spacious indoor court designed for teams and training.'],
        ['name' => 'Boxing',          'img' => 'img/boxing.jpg',    'desc' => 'Build strength and stamina with coach-led sessions.'],
        ['name' => 'Indoor Climbing', 'img' => 'img/climbing.jpg',  'desc' => 'Scale our wall for a full-body challenge at your pace.'],
      ];
      $icons = [
        'badminton'  => '🏸',
        'basketball' => '🏀',
        'futsal'     => '⚽',
        'table tennis' => '🏓',
        'volleyball' => '🏐',
        'boxing'     => '🥊',
        'indoor climbing' => '🧗',
      ];
    ?>
    <?php foreach ($sports as $s): ?>
      <?php
        $k = strtolower($s['name']);
        $icon = $icons[$k] ?? '🎯';
        $imgUrl = $BASE_URL . '/' . ltrim($s['img'], '/');
      ?>
      <div class="reveal group relative overflow-hidden rounded-3xl border border-white/10 bg-[#0B0F14]/70 backdrop-blur-xl shadow-[0_0_40px_rgba(0,255,200,0.08)] transition hover:border-emerald-400/40">
        <div class="absolute inset-0 bg-gradient-to-b from-black/0 via-black/30 to-black/70 z-10 pointer-events-none"></div>
        <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($s['name']) ?>" class="h-56 w-full object-cover opacity-80 transition group-hover:scale-105 duration-700" />
        <div class="relative z-20 p-5 flex flex-col gap-2">
          <div class="inline-flex items-center gap-2 text-sm text-emerald-200">
            <span class="text-lg"><?= $icon ?></span>
            <span>Indoor • Available</span>
          </div>
          <h5 class="text-2xl font-semibold"><?= htmlspecialchars($s['name']) ?></h5>
          <p class="text-sm text-gray-300"><?= htmlspecialchars($s['desc']) ?></p>
          <div class="mt-3 flex gap-3">
            <a href="<?= $BASE_URL ?>/pages/book.php"
               class="inline-flex items-center gap-2 rounded-full bg-white text-black px-5 py-2 text-sm font-medium transition hover:shadow-[0_0_24px_rgba(250,204,21,0.45)]">
              Book Now
            </a>
            <a href="<?= $BASE_URL ?>/pages/sports_timetable.php"
               class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-5 py-2 text-sm text-white backdrop-blur transition hover:border-cyan-400/50 hover:bg-white/10">
              View Timetable
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- CORE VALUES -->
<section class="max-w-7xl mx-auto px-4 py-12">
  <div class="reveal relative rounded-3xl border border-white/10 bg-[#0B0F14]/70 backdrop-blur-xl p-6 md:p-10 text-center">
    <h3 class="text-2xl md:text-3xl font-extrabold tracking-tight">Our Core Values</h3>
    <p class="text-lg text-gray-300 mt-3 max-w-3xl mx-auto">
      We aim to build a vibrant, inclusive community where passion for sport fuels a healthy lifestyle. Respect, commitment, and fun are at the heart of everything we do.
    </p>

    <!-- Values cards (now hoverable) -->
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4 text-left">
      <div class="rounded-2xl border border-white/10 bg-white/5 p-5 transition-all duration-300 transform hover:-translate-y-1 hover:border-emerald-400/40 hover:bg-white/10 hover:shadow-[0_20px_50px_rgba(16,185,129,0.15)]">
        <div class="text-2xl">💚</div>
        <div class="font-semibold mt-2">Respect</div>
        <p class="text-sm text-gray-400 mt-1">We celebrate sportsmanship and lift each other up.</p>
      </div>
      <div class="rounded-2xl border border-white/10 bg-white/5 p-5 transition-all duration-300 transform hover:-translate-y-1 hover:border-emerald-400/40 hover:bg-white/10 hover:shadow-[0_20px_50px_rgba(16,185,129,0.15)]">
        <div class="text-2xl">🏅</div>
        <div class="font-semibold mt-2">Commitment</div>
        <p class="text-sm text-gray-400 mt-1">We show up, improve, and support our teammates.</p>
      </div>
      <div class="rounded-2xl border border-white/10 bg-white/5 p-5 transition-all duration-300 transform hover:-translate-y-1 hover:border-emerald-400/40 hover:bg-white/10 hover:shadow-[0_20px_50px_rgba(16,185,129,0.15)]">
        <div class="text-2xl">🎉</div>
        <div class="font-semibold mt-2">Fun</div>
        <p class="text-sm text-gray-400 mt-1">We keep the game joyful—competition with a smile.</p>
      </div>
    </div>
  </div>
</section>

<!-- SUB-CTA -->
<section class="max-w-6xl mx-auto px-4 pb-16">
  <div class="reveal relative rounded-3xl overflow-hidden border border-white/10 bg-gradient-to-r from-gray-900/70 to-gray-900/40 backdrop-blur-xl">
    <div class="absolute inset-0 bg-[radial-gradient(50%_60%_at_50%_0%,rgba(16,185,129,0.12),transparent_70%)]"></div>
    <div class="relative p-8 md:p-10 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
      <div>
        <h4 class="text-2xl md:text-3xl font-bold">Ready to experience Strive?</h4>
        <p class="text-gray-300 mt-1">Reserve a slot in seconds and see what’s live this week.</p>
      </div>
      <div class="flex gap-3">
        <a href="<?= $BASE_URL ?>/pages/book.php"
           class="inline-flex items-center gap-2 rounded-full bg-white text-black px-6 py-3 text-sm font-medium transition hover:shadow-[0_0_30px_rgba(250,204,21,0.45)]">
          Book a Session
        </a>
        <a href="<?= $BASE_URL ?>/pages/sports_timetable.php"
           class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-6 py-3 text-sm text-white backdrop-blur transition hover:border-emerald-400/50 hover:bg-white/10">
          View Timetable
        </a>
      </div>
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

<?php include("../partials/footer.php"); ?>