<?php
$page_title       = 'OMR Community | Connect with Neighbors on Old Mahabalipuram Road';
$page_description = 'Join the MyOMR community. Connect with neighbors, participate in local groups, events, and forums across the OMR corridor, Chennai.';
$canonical_url    = 'https://myomr.in/discover-myomr/community.php';
$og_image         = 'https://myomr.in/My-OMR-Logo.png';
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
        <section class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-green-800 mb-4">
                Join the Discover MyOMR Community
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-6">
                Connect with neighbors, participate in forums and events, and help shape the future of the OMR corridor.
            </p>
        </section>
        <!-- Areas Covered Section -->
        <section class="bg-green-50 rounded-lg p-6 mb-12">
            <h2 class="text-2xl font-bold text-green-700 mb-2">Areas We Cover</h2>
            <p class="text-gray-700 mb-4">MyOMR serves the entire OMR corridor, including Perungudi, Thuraipakkam, Karapakkam, Kandhanchavadi, Mettukuppam, Sholinganallur, Dollar Stop, IT Corridor, Tidel Park, Madhya Kailash, Navalur, Thazhambur, Kelambakkam, and more.</p>
            <a href="/discover-myomr/areas-covered.php" class="inline-block bg-green-700 text-white px-6 py-2 rounded font-medium hover:bg-green-800 transition-colors">See All Areas</a>
        </section>
        <section class="grid md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-semibold mb-2 text-green-700">Community Forums</h3>
                <p class="text-gray-600">Join discussions, ask questions, and share your thoughts with fellow residents.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-semibold mb-2 text-green-700">Events & Meetups</h3>
                <p class="text-gray-600">Participate in local events, meetups, and activities to connect in person.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-semibold mb-2 text-green-700">Volunteering</h3>
                <p class="text-gray-600">Get involved in community projects and make a positive impact in OMR.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-semibold mb-2 text-green-700">Local Groups</h3>
                <p class="text-gray-600">Find and join groups based on your interests, location, or profession.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-semibold mb-2 text-green-700">Feedback & Suggestions</h3>
                <p class="text-gray-600">Share your ideas and feedback to help us improve the platform and the community.</p>
            </div>
        </section>
        <section class="text-center bg-green-50 rounded-lg p-8 mb-12">
            <h2 class="text-2xl font-bold text-green-800 mb-4">Ready to Get Involved?</h2>
            <p class="text-gray-600 mb-6">Sign up, join a group, or participate in an event today!</p>
            <a href="/discover-myomr/getting-started.php" class="inline-block bg-green-700 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-800 transition-colors">
                Get Started
            </a>
        </section>
    </main>
    <!-- Footer (same as others) -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Discover MyOMR</h3>
                    <p class="text-gray-400">Your local gateway to the OMR corridor.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="/discover-myomr/overview.php" class="text-gray-400 hover:text-white">Overview</a></li>
                        <li><a href="/discover-myomr/getting-started.php" class="text-gray-400 hover:text-white">Get Started</a></li>
                        <li><a href="/discover-myomr/features.php" class="text-gray-400 hover:text-white">Features</a></li>
                        <li><a href="/discover-myomr/pricing.php" class="text-gray-400 hover:text-white">Pricing</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Resources</h4>
                    <ul class="space-y-2">
                        <li><a href="/discover-myomr/community.php" class="text-gray-400 hover:text-white">Community</a></li>
                        <li><a href="/discover-myomr/support.php" class="text-gray-400 hover:text-white">Support</a></li>
                        <li><a href="/info/website-privacy-policy-of-my-omr.php" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2">
                        <li class="text-gray-400">Email: myomrnews@gmail.com</li>
                        <li class="text-gray-400">Phone: +91-98847 85845</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> Discover MyOMR. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <!-- Mobile Menu -->
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden" id="mobile-menu">
        <div class="bg-white h-full w-64 p-6">
            <div class="flex justify-between items-center mb-6">
                <a href="/" class="text-2xl font-bold text-green-700">Discover MyOMR</a>
                <button class="text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <nav class="space-y-4">
                <a href="/discover-myomr/overview.php" class="block text-gray-600">Overview</a>
                <a href="/discover-myomr/getting-started.php" class="block text-gray-600">Get Started</a>
                <a href="/discover-myomr/features.php" class="block text-gray-600">Features</a>
                <a href="/discover-myomr/pricing.php" class="block text-gray-600">Pricing</a>
                <a href="/discover-myomr/community.php" class="block text-gray-600">Community</a>
                <a href="/discover-myomr/support.php" class="block text-gray-600">Support</a>
            </nav>
        </div>
    </div>
    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }
    </script>
</body>
</html> 
