<?php include("../partials/header.php"); ?>

<!-- Hero Section -->
<header class="relative h-[60vh] bg-cover bg-center flex items-center justify-center text-white text-center" style="background-image: url('../img/cntct.jpg');">
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>
    <div class="relative z-10 max-w-3xl mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Contact Us</h1>
        <p class="text-lg md:text-xl">We’re here to help with bookings, feedback, or any questions you have.</p>
    </div>
</header>

<!-- Contact Info Section -->
<section class="max-w-6xl mx-auto px-4 my-12 text-white">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <div>
            <h3 class="text-2xl font-semibold mb-4">📍 Visit Us</h3>
            <p class="mb-6 leading-relaxed">
                Indoor Sports Club Complex<br>
                123 Arena Road, Colombo, Sri Lanka<br>
                Open: 8:00 AM – 10:00 PM (Mon–Sun)
            </p>

            <h3 class="text-2xl font-semibold mb-4">📞 Call Us</h3>
            <p class="mb-6 leading-relaxed">+94 77 123 4567</p>

            <h3 class="text-2xl font-semibold mb-4">📧 Email</h3>
            <p>
                <a href="mailto:info@sportsclub.lk" class="text-yellow-400 hover:underline">info@striveclub.lk</a>
            </p>
        </div>

        <div>
            <h3 class="text-2xl font-semibold mb-6">Send Us a Message</h3>
            <form>
                <div class="mb-5">
                    <label for="name" class="block mb-2 font-medium">Your Name</label>
                    <input type="text" id="name" placeholder="Bruce Wayne" class="w-full p-3 rounded bg-gray-800 text-white border border-gray-600 focus:border-yellow-400 focus:outline-none transition" />
                </div>
                <div class="mb-5">
                    <label for="email" class="block mb-2 font-medium">Your Email</label>
                    <input type="email" id="email" placeholder="you@example.com" class="w-full p-3 rounded bg-gray-800 text-white border border-gray-600 focus:border-yellow-400 focus:outline-none transition" />
                </div>
                <div class="mb-6">
                    <label for="message" class="block mb-2 font-medium">Your Message</label>
                    <textarea id="message" rows="4" placeholder="Type your message here..." class="w-full p-3 rounded bg-gray-800 text-white border border-gray-600 focus:border-yellow-400 focus:outline-none transition resize-none"></textarea>
                </div>
                <button type="submit" class="bg-yellow-400 text-black px-6 py-3 rounded-full font-semibold hover:bg-yellow-500 transition">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</section>

<?php include("../partials/footer.php"); ?>
