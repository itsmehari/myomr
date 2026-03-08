<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OMR Database | Discover MyOMR</title>
    <meta name="description" content="Explore the complete OMR Database: schools, IT companies, banks, hospitals, restaurants, and more along Old Mahabalipuram Road.">
    <meta name="keywords" content="OMR, database, listings, schools, IT companies, banks, hospitals, restaurants, Chennai">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/components/discover-nav.php'; ?>
    <main class="container mx-auto px-4 py-8">
        <section class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-green-800 mb-4">OMR Database</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-6">Find everything you need along Old Mahabalipuram Road: schools, IT companies, banks, hospitals, restaurants, hostels, coworking spaces, and more. Click a category to explore detailed listings.</p>
        </section>
        <section class="grid md:grid-cols-3 gap-8 mb-12">
            <a href="/it-parks" class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center hover:bg-green-50 transition">
                <span class="text-3xl text-green-700 mb-2"><i class="fas fa-building"></i></span>
                <h3 class="text-xl font-semibold mb-2 text-green-800">IT Parks</h3>
                <p class="text-gray-600 text-center">Major IT parks and SEZ campuses along OMR.</p>
            </a>
            <a href="/omr-listings/schools.php" class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center hover:bg-green-50 transition">
                <span class="text-3xl text-green-700 mb-2"><i class="fas fa-school"></i></span>
                <h3 class="text-xl font-semibold mb-2 text-green-800">Schools</h3>
                <p class="text-gray-600 text-center">Comprehensive list of schools in the OMR corridor.</p>
            </a>
            <a href="/omr-listings/best-schools.php" class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center hover:bg-green-50 transition">
                <span class="text-3xl text-green-700 mb-2"><i class="fas fa-star"></i></span>
                <h3 class="text-xl font-semibold mb-2 text-green-800">Best Schools</h3>
                <p class="text-gray-600 text-center">Top-rated schools for quality education in OMR.</p>
            </a>
            <a href="/omr-listings/it-companies.php" class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center hover:bg-green-50 transition">
                <span class="text-3xl text-green-700 mb-2"><i class="fas fa-laptop-code"></i></span>
                <h3 class="text-xl font-semibold mb-2 text-green-800">IT Companies</h3>
                <p class="text-gray-600 text-center">Major IT and tech companies located on OMR.</p>
            </a>
            <a href="/omr-listings/industries.php" class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center hover:bg-green-50 transition">
                <span class="text-3xl text-green-700 mb-2"><i class="fas fa-industry"></i></span>
                <h3 class="text-xl font-semibold mb-2 text-green-800">Industries</h3>
                <p class="text-gray-600 text-center">Key industries and manufacturing units in OMR.</p>
            </a>
            <a href="/omr-listings/restaurants.php" class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center hover:bg-green-50 transition">
                <span class="text-3xl text-green-700 mb-2"><i class="fas fa-utensils"></i></span>
                <h3 class="text-xl font-semibold mb-2 text-green-800">Restaurants</h3>
                <p class="text-gray-600 text-center">Popular restaurants and eateries along OMR.</p>
            </a>
            <a href="/omr-listings/government-offices.php" class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center hover:bg-green-50 transition">
                <span class="text-3xl text-green-700 mb-2"><i class="fas fa-university"></i></span>
                <h3 class="text-xl font-semibold mb-2 text-green-800">Government Offices</h3>
                <p class="text-gray-600 text-center">Important government offices and services in OMR.</p>
            </a>
            <a href="/omr-listings/atms.php" class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center hover:bg-green-50 transition">
                <span class="text-3xl text-green-700 mb-2"><i class="fas fa-credit-card"></i></span>
                <h3 class="text-xl font-semibold mb-2 text-green-800">ATMs</h3>
                <p class="text-gray-600 text-center">ATM locations for all major banks in OMR.</p>
            </a>
            <a href="/omr-listings/parks.php" class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center hover:bg-green-50 transition">
                <span class="text-3xl text-green-700 mb-2"><i class="fas fa-tree"></i></span>
                <h3 class="text-xl font-semibold mb-2 text-green-800">Parks</h3>
                <p class="text-gray-600 text-center">Green spaces and parks for recreation in OMR.</p>
            </a>
            <a href="/omr-listings/banks.php" class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center hover:bg-green-50 transition">
                <span class="text-3xl text-green-700 mb-2"><i class="fas fa-university"></i></span>
                <h3 class="text-xl font-semibold mb-2 text-green-800">Banks</h3>
                <p class="text-gray-600 text-center">All major banks and branches along OMR.</p>
            </a>
            <a href="/omr-listings/hospitals.php" class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center hover:bg-green-50 transition">
                <span class="text-3xl text-green-700 mb-2"><i class="fas fa-hospital"></i></span>
                <h3 class="text-xl font-semibold mb-2 text-green-800">Hospitals</h3>
                <p class="text-gray-600 text-center">Hospitals and healthcare centers in the OMR region.</p>
            </a>
            <a href="/omr-hostels-pgs/" class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center hover:bg-green-50 transition">
                <span class="text-3xl text-green-700 mb-2"><i class="fas fa-bed"></i></span>
                <h3 class="text-xl font-semibold mb-2 text-green-800">Hostels & PGs</h3>
                <p class="text-gray-600 text-center">Find safe and affordable accommodation in OMR for students and professionals.</p>
            </a>
            <a href="/omr-coworking-spaces/" class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center hover:bg-green-50 transition">
                <span class="text-3xl text-green-700 mb-2"><i class="fas fa-building"></i></span>
                <h3 class="text-xl font-semibold mb-2 text-green-800">Coworking Spaces</h3>
                <p class="text-gray-600 text-center">Discover professional workspaces, hot desks, and meeting rooms in OMR.</p>
            </a>
        </section>
    </main>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer.php'; ?>
    <script src="https://kit.fontawesome.com/4e9b2b1c0a.js" crossorigin="anonymous"></script>
    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }
    </script>
</body>
</html> 