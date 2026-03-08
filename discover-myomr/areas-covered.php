<?php
$page_title       = 'Areas Covered by MyOMR | Old Mahabalipuram Road Communities';
$page_description = 'Discover all the neighborhoods and localities covered by MyOMR along the OMR corridor, from Perungudi to Kelambakkam, Chennai.';
$canonical_url    = 'https://myomr.in/discover-myomr/areas-covered.php';
$og_image         = 'https://myomr.in/My-OMR-Logo.jpg';
$og_title         = $page_title;
$og_description   = $page_description;
$og_url           = $canonical_url;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include '../components/meta.php'; ?>
<?php include '../components/analytics.php'; ?>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/components/discover-nav.php'; ?>
    <main class="container mx-auto px-4 py-8">
        <section class="bg-green-50 rounded-lg p-8 mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-green-700 mb-4">Areas We Cover</h1>
            <p class="text-lg text-gray-700 mb-6">Discover MyOMR serves the entire Old Mahabalipuram Road (OMR) corridor and its vibrant neighborhoods. We connect residents, businesses, and communities across all these areas:</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded shadow p-4 text-center font-semibold text-green-800">Perungudi</div>
                <div class="bg-white rounded shadow p-4 text-center font-semibold text-green-800">Thuraipakkam</div>
                <div class="bg-white rounded shadow p-4 text-center font-semibold text-green-800">Karapakkam</div>
                <div class="bg-white rounded shadow p-4 text-center font-semibold text-green-800">Kandhanchavadi</div>
                <div class="bg-white rounded shadow p-4 text-center font-semibold text-green-800">Mettukuppam</div>
                <div class="bg-white rounded shadow p-4 text-center font-semibold text-green-800">Sholinganallur</div>
                <div class="bg-white rounded shadow p-4 text-center font-semibold text-green-800">Dollar Stop</div>
                <div class="bg-white rounded shadow p-4 text-center font-semibold text-green-800">IT Corridor</div>
                <div class="bg-white rounded shadow p-4 text-center font-semibold text-green-800">Tidel Park</div>
                <div class="bg-white rounded shadow p-4 text-center font-semibold text-green-800">Madhya Kailash</div>
                <div class="bg-white rounded shadow p-4 text-center font-semibold text-green-800">Navalur</div>
                <div class="bg-white rounded shadow p-4 text-center font-semibold text-green-800">Thazhambur</div>
                <div class="bg-white rounded shadow p-4 text-center font-semibold text-green-800">Kelambakkam</div>
                <div class="bg-white rounded shadow p-4 text-center font-semibold text-green-800">SRP Tools</div>
                <div class="bg-white rounded shadow p-4 text-center font-semibold text-green-800">Other OMR Neighborhoods</div>
            </div>
            <p class="text-gray-600 mt-8">If your area is not listed, let us know! We're always expanding our coverage to serve the entire OMR community.</p>
        </section>
    </main>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer.php'; ?>
    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }
    </script>
</body>
</html> 
