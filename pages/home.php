<?php include('../partials/header.php'); ?>

<!-- HERO / Futuristic header -->
<header
  class="relative h-[85vh] md:h-[90vh] flex items-center justify-center overflow-hidden rounded-b-3xl"
  style="background-image: url('<?= $BASE_URL ?>/img/hero101.jpg'); background-size: cover; background-position: center;"
>
  <!-- Ambient gradient glows -->
  <div class="pointer-events-none absolute inset-0">
    <div class="absolute -top-20 -left-32 w-[38rem] h-[38rem] bg-emerald-500/10 blur-3xl rounded-full"></div>
    <div class="absolute -bottom-24 -right-24 w-[44rem] h-[44rem] bg-purple-500/10 blur-3xl rounded-full"></div>
  </div>

  <!-- Dark overlay -->
  <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/60 to-black/80"></div>

  <!-- Content -->
  <div class="relative z-10 text-center max-w-4xl px-6">
    <div class="inline-flex items-center gap-2 rounded-full bg-emerald-500/10 ring-1 ring-emerald-400/30 px-3 py-1 text-emerald-200 text-xs md:text-sm mb-4">
      <span class="relative flex h-2 w-2">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
      </span>
      Indoor sessions open — book now
    </div>

    <h1 class="text-4xl md:text-6xl font-extrabold leading-[1.1] tracking-tight">
      Welcome to the <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-300 via-cyan-300 to-purple-300">Strive Sports Club</span>
    </h1>

    <p class="text-base md:text-lg text-gray-300 mt-5">
      Book your favorite indoor sport sessions online and stay fit while having fun.
    </p>

    <div class="mt-8 flex flex-wrap justify-center gap-4">
      <a href="<?= $BASE_URL ?>/pages/register.php"
         class="group relative inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-8 py-3 text-white backdrop-blur transition hover:border-emerald-400/50 hover:bg-white/10">
        <span class="absolute inset-0 -z-10 rounded-full bg-emerald-500/0 shadow-[0_0_60px_rgba(16,185,129,0)] group-hover:bg-emerald-500/10 group-hover:shadow-[0_0_60px_rgba(16,185,129,0.35)] transition"></span>
        Join Now
      </a>
      <a href="<?= $BASE_URL ?>/pages/login.php"
         class="group relative inline-flex items-center gap-2 rounded-full bg-white text-black px-8 py-3 transition hover:shadow-[0_0_30px_rgba(250,204,21,0.45)]">
        <span class="absolute inset-0 -z-10 rounded-full border border-yellow-400/40"></span>
        Login
      </a>
      <a href="<?= $BASE_URL ?>/pages/timetable.php"
         class="group relative inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-8 py-3 text-white backdrop-blur transition hover:border-cyan-400/50 hover:bg-white/10">
        View Timetable
      </a>
    </div>
  </div>
</header>

<!-- Quick Actions (glass tiles) -->
<section class="max-w-7xl mx-auto px-4 -mt-10 md:-mt-14">
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <?php
      $actions = [
        ['title' => 'Timetable',    'icon' => '🗓', 'href' => $BASE_URL.'/pages/timetable.php', 'sub' => 'Plan your week'],
        ['title' => 'Book Session', 'icon' => '⚡', 'href' => $BASE_URL.'/pages/book.php',             'sub' => 'Reserve a slot'],
        ['title' => 'About Us',     'icon' => '🏟', 'href' => $BASE_URL.'/pages/about.php',            'sub' => 'Our facilities'],
        ['title' => 'Contact',      'icon' => '✉️', 'href' => $BASE_URL.'/pages/contact.php',          'sub' => 'Get support'],
      ];
    ?>
    <?php foreach ($actions as $a): ?>
      <a href="<?= htmlspecialchars($a['href']) ?>" class="reveal block rounded-2xl border border-white/10 bg-[#0B0F14]/70 p-5 backdrop-blur-xl hover:border-emerald-400/40 hover:bg-white/10 transition group">
        <div class="flex items-center gap-3">
          <span class="text-2xl"><?= $a['icon'] ?></span>
          <div>
            <div class="font-semibold"><?= htmlspecialchars($a['title']) ?></div>
            <div class="text-xs text-gray-400"><?= htmlspecialchars($a['sub']) ?></div>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- Quote Banner (glass + accent) -->
<section class="my-12 px-4">
  <div class="max-w-5xl mx-auto relative">
    <div class="absolute -inset-1 rounded-2xl bg-gradient-to-r from-emerald-400/20 via-cyan-400/20 to-purple-400/20 blur-2xl"></div>
    <div class="relative rounded-2xl bg-white/5 border border-white/10 p-6 md:p-8 text-center">
      <p class="text-xl md:text-2xl font-bold italic">
        “You miss 100% of the shots you don’t take.” <span class="not-italic font-medium text-gray-300">— Wayne Gretzky</span>
      </p>
    </div>
  </div>
</section>

<!-- Featured Sports (neon glass cards) -->
<section class="max-w-7xl mx-auto px-4 my-12">
  <h2 class="reveal text-center text-3xl md:text-5xl font-extrabold tracking-tight mb-8">
    <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-300 via-cyan-300 to-purple-300">
      Featured Sports
    </span>
  </h2>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <?php
      $sports = [
        ['name' => 'Badminton',  'img' => 'img/badminton.jpg',  'desc' => 'Smash, drop, and play! Book your next badminton session now.'],
        ['name' => 'Basketball', 'img' => 'img/basketball.jpg', 'desc' => 'Shoot hoops and play 3-on-3 with your friends indoors.'],
        ['name' => 'Futsal',     'img' => 'img/futsal.jpg',     'desc' => 'Enjoy fast-paced 5-a-side action in our premium indoor courts.'],
      ];
      $icons = [
        'badminton'  => '🏸',
        'basketball' => '🏀',
        'futsal'     => '⚽',
      ];
    ?>
    <?php foreach ($sports as $s): ?>
      <?php
        $k      = strtolower($s['name']);
        $icon   = isset($icons[$k]) ? $icons[$k] : '🎯';
        $imgUrl = $BASE_URL . '/' . ltrim($s['img'], '/');
      ?>
      <div class="reveal group relative overflow-hidden rounded-3xl border border-white/10 bg-[#0B0F14]/70 backdrop-blur-xl shadow-[0_0_40px_rgba(0,255,200,0.08)] transition hover:border-emerald-400/40">
        <div class="absolute inset-0 bg-gradient-to-b from-black/0 via-black/30 to-black/70 z-10 pointer-events-none"></div>
        <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($s['name']) ?>" class="h-56 w-full object-cover opacity-80 transition group-hover:scale-105 duration-700" />
        <div class="relative z-20 p-5 flex flex-col gap-3">
          <div class="inline-flex items-center gap-2 text-sm text-emerald-200">
            <span class="text-lg"><?= $icon ?></span>
            <span>Indoor • Available</span>
          </div>
          <h3 class="text-2xl font-semibold"><?= htmlspecialchars($s['name']) ?></h3>
          <p class="text-gray-300"><?= htmlspecialchars($s['desc']) ?></p>
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

<!-- Stats / Highlights -->
<section class="max-w-7xl mx-auto px-4 my-16">
  <div class="reveal grid grid-cols-2 md:grid-cols-4 gap-4">
    <?php
      $stats = [
        ['label' => 'Courts',      'value' => '8'],
        ['label' => 'Coaches',     'value' => '14'],
        ['label' => 'Daily Slots', 'value' => '40+'],
        ['label' => 'Members',     'value' => '1.2k+'],
      ];
    ?>
    <?php foreach ($stats as $st): ?>
      <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur p-5 text-center hover:border-emerald-400/40 transition">
        <div class="text-3xl md:text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-emerald-300 to-cyan-300"><?= htmlspecialchars($st['value']) ?></div>
        <div class="text-xs md:text-sm text-gray-400 mt-1"><?= htmlspecialchars($st['label']) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Sub-CTA -->
<section class="max-w-6xl mx-auto px-4 my-16">
  <div class="reveal relative rounded-3xl overflow-hidden border border-white/10 bg-gradient-to-r from-gray-900/70 to-gray-900/40 backdrop-blur-xl">
    <div class="absolute inset-0 bg-[radial-gradient(50%_60%_at_50%_0%,rgba(16,185,129,0.12),transparent_70%)]"></div>
    <div class="relative p-8 md:p-10 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
      <div>
        <h3 class="text-2xl md:text-3xl font-bold">Ready to get moving?</h3>
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

<?php include('../partials/footer.php'); ?>