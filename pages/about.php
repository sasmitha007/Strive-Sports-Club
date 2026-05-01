<?php include("../partials/header.php"); ?>

<!-- Hero Section -->
<header class="relative h-[60vh] flex items-center justify-center text-white text-center overflow-hidden">
    <!-- Grayscale Image -->
    <img src="../img/abtus.jpg" class="absolute inset-0 w-full h-full object-cover" alt="About Us Background">
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>
    <div class="relative z-10 max-w-3xl mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">About Us</h1>
        <p class="text-lg md:text-xl">Your favorite place to play, grow, and compete indoors!</p>
    </div>
</header>

<!-- Vision Section -->
<section class="max-w-6xl mx-auto px-4 text-white my-12">
    <div class="border border-white rounded-xl p-6 mb-12">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-semibold mb-4">Our Vision</h2>
            <p class="text-lg max-w-3xl mx-auto leading-relaxed">
                At Strive Club, we believe in promoting health, friendship, and teamwork through sport. Whether you’re a seasoned athlete or just starting out, our doors are open to everyone who loves to play. We offer modern, well-equipped indoor facilities that make it easy and fun to stay active—rain or shine.
            </p>
        </div>
    </div>

    <h3 class="text-2xl font-semibold text-center mb-8">What We Offer</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php
        $sports = [
            ["Badminton", "../img/badminton.jpg", "From singles to doubles, enjoy our high-quality indoor badminton courts with flexible booking options."],
            ["Basketball", "../img/basketball.jpg", "Challenge your friends to a friendly match or join our indoor basketball tournaments for all skill levels."],
            ["Futsal", "../img/futsal.jpg", "Experience the thrill of 5-a-side football in a safe and climate-controlled indoor environment."],
            ["Table Tennis", "../img/tblt.jpg", "Play solo or compete in fast-paced rallies on professional-grade tables in our table tennis zone."],
            ["Volleyball", "../img/vol.jpg", "Join indoor volleyball games in our spacious court designed for fun, fitness, and team spirit."],
            ["Boxing", "../img/boxing.jpg", "Build strength and stamina with our indoor boxing facilities — whether you’re training or sparring."],
            ["Indoor Climbing", "../img/climbing.jpg", "Scale our indoor climbing wall for a full-body workout that challenges your strength and agility."]
        ];

        foreach ($sports as $sport) {
            echo "
            <div class='bg-gray-900 rounded-xl border border-white overflow-hidden shadow-md hover:shadow-lg transition-transform transform transition-all duration-500 hover:scale-105 duration-500 ease-in-out'>
                <img src='{$sport[1]}' alt='{$sport[0]}' class='w-full h-70 object-cover grayscale hover:grayscale-0 transition duration-800 ease-in-out' />
                <div class='p-5 flex flex-col gap-2'>
                    <h5 class='text-2xl font-semibold'>{$sport[0]}</h5>
                    <p class='text-sm text-gray-300'>{$sport[2]}</p>
                </div>
            </div>";
        }
        ?>
    </div>
</section>

<!-- Core Values Section -->
<section class="text-white text-center my-12 px-4">
    <div class="border border-white rounded-xl p-6 max-w-5xl mx-auto">
        <div class="max-w-3xl mx-auto">
            <h3 class="text-2xl font-semibold mb-4">Our Core Values</h3>
            <p class="text-lg leading-relaxed">
                We aim to build a vibrant, inclusive community where passion for sport fuels a healthy lifestyle. Respect, commitment, and fun are at the heart of everything we do.
            </p>
        </div>
    </div>
</section>

<?php include("../partials/footer.php"); ?>
