<?php include('../partials/header.php'); ?>

<!-- Hero Section -->
<header class="relative bg-cover bg-center h-[90vh] flex items-center justify-center" style="background-image: url('../img/hero101.jpg');">
    <div class="relative z-10 text-center max-w-3xl">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-5 leading-tight">Welcome to the Strive<br>Sports Club</h1>

        <p class="text-lg md:text-xl mb-6">Book your favorite indoor sport sessions online and stay fit while having fun!</p>
        
        <div class="flex justify-center space-x-4">
            <a href="../pages/register.php" class="text-white border border-white rounded-full px-8 py-3 text-lg font-medium transition-all duration-700 ease-in-out hover:bg-white hover:text-black hover:shadow-[0_0_15px_3px_rgb(250,204,21)] hover:border-yellow-400">Join Now</a> 
            <a href="../pages/login.php" class="bg-white text-black rounded-full px-8 py-3 text-lg font-medium transition-all duration-700 ease-in-out hover:bg-white hover:text-black hover:shadow-[0_0_15px_3px_rgb(250,204,21)] hover:border-yellow-400">Login</a>
        </div>
    </div>
</header>

<!-- Quote Banner -->
<section class="my-12 px-4">
    <div class="max-w-5xl mx-auto bg-white/10 backdrop-blur p-6 border-l-4 border-yellow-500 italic text-center text-2xl md:text-3xl font-bold">
        "You miss 100% of the shots you don't take." <br> – Wayne Gretzky
    </div>
</section>

<!-- Featured Sports -->
<section class="max-w-7xl mx-auto px-4 my-12">
    <h2 class="text-5xl text-center mb-8">Featured Sports</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php
        $sports = [
            ["Badminton", 'img/badminton.jpg', "Smash, drop, and play! Book your next badminton session now."],
            ["Basketball", 'img/basketball.jpg', "Shoot hoops and play 3-on-3 with your friends indoors."],
            ["Futsal", 'img/futsal.jpg', "Enjoy fast-paced 5-a-side action in our premium indoor courts."]
        ];

        foreach ($sports as $sport) {
            echo "
            <div class='bg-gray-800 border border-white rounded-lg shadow-lg overflow-hidden transform transform transition-all duration-500 ease-in-out hover:scale-105 flex flex-col'>
                <img src='../{$sport[1]}' alt='{$sport[0]}' class='w-full h-70 object-cover grayscale hover:grayscale-0 transition-all duration-800 ease-in-out' />
                <div class='flex flex-col justify-between p-4 flex-grow'>
                    <div>
                        <h3 class='text-2xl font-semibold mb-3'group-hover:text-yellow-400 transition-all duration-300 ease-in-out'>{$sport[0]}</h3>
                        <p class='text-base mb-4'>{$sport[2]}</p>
                    </div>
                    <a href='../pages/book.php' class='bg-yellow-400 text-black px-4 py-2 rounded-full text-center transition-all duration-500 ease-in-out hover:bg-yellow-500 transition w-full'>Book Now</a>
                </div>
            </div>";
        }
        ?>
    </div>
</section>

<?php include('../partials/footer.php'); ?>