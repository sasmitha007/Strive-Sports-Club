<?php include("../partials/header.php"); ?>

<!-- HERO -->
<header class="relative overflow-hidden rounded-b-3xl">
  <!-- Ambient gradient glows -->
  <div class="pointer-events-none absolute inset-0">
    <div class="absolute -top-24 -left-28 w-[42rem] h-[42rem] bg-emerald-500/10 blur-3xl rounded-full"></div>
    <div class="absolute -bottom-24 -right-24 w-[46rem] h-[46rem] bg-purple-500/10 blur-3xl rounded-full"></div>
  </div>

  <div class="relative h-[60vh] flex items-center justify-center text-center">
    <img src="<?= $BASE_URL ?>/img/cntct.jpg" class="absolute inset-0 w-full h-full object-cover opacity-80" alt="Contact Background">
    <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/60 to-black/80"></div>
    <div class="relative z-10 max-w-3xl mx-auto px-6">
      <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">Contact Us</h1>
      <p class="text-lg md:text-xl text-gray-300 mt-3">
        We’re here to help with bookings, feedback, or any questions you have.
      </p>
      <div class="mt-5 inline-flex items-center gap-2 rounded-full bg-emerald-500/10 ring-1 ring-emerald-400/30 px-3 py-1 text-emerald-200 text-xs">
        <span class="relative flex h-2 w-2">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
        </span>
        Fast response • 8:00 AM – 10:00 PM
      </div>
    </div>
  </div>
</header>

<!-- CONTACT CONTENT -->
<section class="max-w-7xl mx-auto px-4 py-12">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

    <!-- Left: Info cards -->
    <div class="space-y-4">
      <div class="reveal rounded-2xl border border-white/10 bg-white/5 backdrop-blur p-5 transition-all duration-300 transform hover:-translate-y-1 hover:border-emerald-400/40 hover:bg-white/10 hover:shadow-[0_20px_50px_rgba(16,185,129,0.15)]">
        <div class="flex items-start gap-3">
          <div class="text-2xl">📍</div>
          <div>
            <h3 class="text-xl font-semibold mb-1">Visit Us</h3>
            <p class="text-gray-300 leading-relaxed">
              Indoor Sports Club Complex<br>
              123 Arena Road, Colombo, Sri Lanka<br>
              <span class="text-gray-400">Open: 8:00 AM – 10:00 PM (Mon–Sun)</span>
            </p>
            <a href="https://maps.google.com/?q=123+Arena+Road+Colombo+Sri+Lanka" target="_blank" class="inline-block mt-2 text-sm text-emerald-300 hover:underline underline-offset-4">View on Maps →</a>
          </div>
        </div>
      </div>

      <div class="reveal rounded-2xl border border-white/10 bg-white/5 backdrop-blur p-5 transition-all duration-300 transform hover:-translate-y-1 hover:border-emerald-400/40 hover:bg-white/10 hover:shadow-[0_20px_50px_rgba(16,185,129,0.15)]">
        <div class="flex items-start gap-3">
          <div class="text-2xl">📞</div>
          <div>
            <h3 class="text-xl font-semibold mb-1">Call Us</h3>
            <p class="text-gray-300 leading-relaxed">
              <a href="tel:+94771234567" class="hover:underline underline-offset-4">+94 77 123 4567</a>
            </p>
            <p class="text-xs text-gray-400">Best time: 9:00 AM – 6:00 PM</p>
          </div>
        </div>
      </div>

      <div class="reveal rounded-2xl border border-white/10 bg-white/5 backdrop-blur p-5 transition-all duration-300 transform hover:-translate-y-1 hover:border-emerald-400/40 hover:bg-white/10 hover:shadow-[0_20px_50px_rgba(16,185,129,0.15)]">
        <div class="flex items-start gap-3">
          <div class="text-2xl">📧</div>
          <div>
            <h3 class="text-xl font-semibold mb-1">Email</h3>
            <p class="text-gray-300 leading-relaxed">
              <a href="mailto:info@striveclub.lk" class="text-emerald-300 hover:underline underline-offset-4">info@striveclub.lk</a>
            </p>
            <p class="text-xs text-gray-400">We typically reply within a few hours.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Right: Form card -->
    <div class="reveal relative rounded-3xl border border-white/10 bg-[#0B0F14]/70 backdrop-blur-xl shadow-[0_0_40px_rgba(0,255,200,0.08)] overflow-hidden">
      <div class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-emerald-400/15 via-cyan-400/15 to-purple-400/15 blur-2xl"></div>

      <div class="relative p-6 md:p-8">
        <h3 class="text-2xl font-semibold mb-6">Send Us a Message</h3>

        <!-- Success toast (hidden by default) -->
        <div id="toast"
             class="hidden mb-4 rounded-xl border border-emerald-400/40 bg-emerald-500/10 text-emerald-200 px-4 py-3">
          ✅ Message sent! We’ll get back to you shortly.
        </div>

        <form id="contactForm" class="space-y-5" novalidate>
          <div>
            <label for="name" class="block mb-2 text-sm text-gray-300">Your Name</label>
            <input type="text" id="name" name="name" placeholder="Bruce Wayne"
                   class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder-gray-500
                          focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400/40 transition" required>
          </div>

          <div>
            <label for="email" class="block mb-2 text-sm text-gray-300">Your Email</label>
            <input type="email" id="email" name="email" placeholder="you@example.com"
                   class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder-gray-500
                          focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400/40 transition" required>
          </div>

          <div>
            <label for="message" class="block mb-2 text-sm text-gray-300">Your Message</label>
            <textarea id="message" name="message" rows="5" placeholder="Type your message here..."
                      class="w-full rounded-xl bg-white/5 border border-white/10 px-4 py-3 text-white placeholder-gray-500
                             focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400/40 transition resize-none" required></textarea>
            <div class="mt-1 text-xs text-gray-500"><span id="charCount">0</span>/500</div>
          </div>

          <div class="flex items-center justify-between gap-3">
            <label class="inline-flex items-center gap-2 text-xs text-gray-400">
              <input type="checkbox" id="copyMe" class="rounded border-white/20 bg-transparent">
              Send me a copy
            </label>

            <button type="submit"
                    class="group relative inline-flex items-center gap-2 rounded-full bg-white text-black px-6 py-3 text-sm font-medium
                           transition hover:shadow-[0_0_30px_rgba(250,204,21,0.45)]">
              <span class="absolute inset-0 -z-10 rounded-full border border-yellow-400/40"></span>
              Send Message
            </button>
          </div>
        </form>

        <p class="mt-6 text-xs text-gray-500">
          By contacting us you agree to our privacy policy. We’ll only use your data to respond to your message.
        </p>
      </div>
    </div>

  </div>
</section>

<!-- Reveal + lightweight client-side UX -->
<script>
  // Reveal-on-scroll (matches other pages)
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

  // Form niceties (no backend; shows a toast)
  (function () {
    const form = document.getElementById('contactForm');
    const toast = document.getElementById('toast');
    const msg = document.getElementById('message');
    const count = document.getElementById('charCount');

    if (msg && count) {
      const max = 500;
      msg.addEventListener('input', () => {
        const len = Math.min(msg.value.length, max);
        count.textContent = len;
        if (len > max) msg.value = msg.value.slice(0, max);
      });
    }

    if (form && toast) {
      form.addEventListener('submit', (e) => {
        e.preventDefault(); // simulate a successful submission
        toast.classList.remove('hidden');
        // simple reset for demo
        form.reset();
        count.textContent = '0';
        // auto-hide after a while
        setTimeout(() => toast.classList.add('hidden'), 4000);
      });
    }
  })();
</script>

<?php include("../partials/footer.php"); ?>
