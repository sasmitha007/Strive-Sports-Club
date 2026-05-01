<?php include('../partials/header.php'); ?>

<!-- Timetable Banner Image with Dark Overlay -->
<div class="relative w-full h-64 mb-8 rounded-lg overflow-hidden shadow-lg">
    <!-- Image -->
    <img src="../img/time.jpg" alt="Sports Timetable Banner" class="w-full h-full object-cover">

    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>
</div>

<!-- Grid layout -->
<section class="max-w-6xl mx-auto px-4 py-10 min-h-[60vh] grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Timetable -->
    <div class="md:col-span-2">
        <h1 class="text-3xl font-bold mb-8 text-center">Weekly Sports Timetable</h1>

        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse border border-gray-600">
                <thead class="bg-gray-700 text-white">
                    <tr>
                        <th class="border border-gray-600 px-4 py-2">Time</th>
                        <th class="border border-gray-600 px-4 py-2">Tuesday</th>
                        <th class="border border-gray-600 px-4 py-2">Wednesday</th>
                        <th class="border border-gray-600 px-4 py-2">Friday</th>
                        <th class="border border-gray-600 px-4 py-2">Saturday</th>
                        <th class="border border-gray-600 px-4 py-2">Sunday</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 text-white">
                    <tr>
                        <td class="border border-gray-700 px-4 py-2 font-semibold">8:00 AM – 10:00 AM</td>
                        <td class="border border-gray-700 px-4 py-2">Futsal</td>
                        <td class="border border-gray-700 px-4 py-2">Table Tennis</td>
                        <td class="border border-gray-700 px-4 py-2">Basketball</td>
                        <td class="border border-gray-700 px-4 py-2">Volleyball</td>
                        <td class="border border-gray-700 px-4 py-2">Badminton</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-700 px-4 py-2 font-semibold">10:00 AM – 12:00 PM</td>
                        <td class="border border-gray-700 px-4 py-2">Boxing</td>
                        <td class="border border-gray-700 px-4 py-2">Badminton</td>
                        <td class="border border-gray-700 px-4 py-2">Volleyball</td>
                        <td class="border border-gray-700 px-4 py-2">Basketball</td>
                        <td class="border border-gray-700 px-4 py-2">Futsal</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-700 px-4 py-2 font-semibold">2:00 PM – 4:00 PM</td>
                        <td class="border border-gray-700 px-4 py-2">Table Tennis</td>
                        <td class="border border-gray-700 px-4 py-2">Climbing</td>
                        <td class="border border-gray-700 px-4 py-2">Boxing</td>
                        <td class="border border-gray-700 px-4 py-2">Futsal</td>
                        <td class="border border-gray-700 px-4 py-2">Volleyball</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-700 px-4 py-2 font-semibold">4:00 PM – 6:00 PM</td>
                        <td class="border border-gray-700 px-4 py-2">Climbing</td>
                        <td class="border border-gray-700 px-4 py-2">Basketball</td>
                        <td class="border border-gray-700 px-4 py-2">Badminton</td>
                        <td class="border border-gray-700 px-4 py-2">Boxing</td>
                        <td class="border border-gray-700 px-4 py-2">Table Tennis</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6 text-center text-sm text-gray-400">
            * Timetable may be updated weekly by the admin.
        </div>
    </div>

    <!-- Side image -->
    <div class="hidden md:block">
        <img src="../img/sideimg.jpg" alt="Indoor Sport" class="rounded-lg shadow-lg h-full object-cover">
    </div>
</section>

<?php include('../partials/footer.php'); ?>