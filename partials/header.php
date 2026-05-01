<?php require_once __DIR__ . '/../core/sessions.php';
$BASE_URL = '/Indoor%20sports%20club';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Indoor Sports Club | Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= $BASE_URL ?>/style.css">
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col" style="font-family: 'Playfair Display', serif;">

<!-- NAVBAR -->
<nav class="bg-gray-800 shadow-md">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <!-- LOGO LEFT -->
        <div class="flex items-center">
            <a href="/" class="text-2xl font-bold text-white">🏸 Strive Sports Club</a>
        </div>

        <!-- NAV LINKS RIGHT -->
        <div class="hidden md:flex space-x-6">
            <a href="<?= $BASE_URL ?>/pages/home.php">Home</a>
            <a href="<?= $BASE_URL ?>/pages/about.php">About Us</a>
            <a href="<?= $BASE_URL ?>/pages/contact.php">Contact Us</a>
            <a href="<?= $BASE_URL ?>/pages/timetable.php">Timetable</a>
            <a href="<?= $BASE_URL ?>/pages/book.php">Book Session</a>

            <?php if (isset($_SESSION['user'])): ?>
                <a href="<?= $BASE_URL ?>/pages/dashboard.php">Dashboard</a>
                <a href="<?= $BASE_URL ?>/controllers/authcontroller.php?logout=1">Logout</a>
            <?php else: ?>
                <a href="<?= $BASE_URL ?>/pages/login.php">Login</a>
                <a href="<?= $BASE_URL ?>/pages/register.php">Register</a>
            <?php endif; ?>

        </div>
    </div>
</nav>

<main class="flex-grow">