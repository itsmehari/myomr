<?php
$page_title       = 'Get Started with MyOMR | OMR Chennai Community Portal';
$page_description = 'New to MyOMR? Learn how to explore local news, post listings, find jobs, and connect with the OMR community in just a few easy steps.';
$canonical_url    = 'https://myomr.in/discover-myomr/getting-started.php';
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
            <section class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-green-800 mb-4">
                    Get Started with Discover MyOMR
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-6">
                    Join the OMR community in just a few easy steps. Connect, discover, and thrive with local news, jobs, events, and more!
                </p>
            </section>
            <section class="grid md:grid-cols-3 gap-8 mb-12">
                <div class="bg-white p-6 rounded-lg shadow-sm flex flex-col items-center">
                    <span class="text-3xl text-green-700 font-bold mb-2">1</span>
                    <h3 class="text-xl font-semibold mb-2 text-green-700">Create Your Account</h3>
                    <p class="text-gray-600">Sign up to unlock all features and personalize your experience.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm flex flex-col items-center">
                    <span class="text-3xl text-green-700 font-bold mb-2">2</span>
                    <h3 class="text-xl font-semibold mb-2 text-green-700">Explore Features</h3>
                    <p class="text-gray-600">Browse news, jobs, events, business listings, and more tailored for OMR.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm flex flex-col items-center">
                    <span class="text-3xl text-green-700 font-bold mb-2">3</span>
                    <h3 class="text-xl font-semibold mb-2 text-green-700">Engage & Connect</h3>
                    <p class="text-gray-600">Post your own listings, join discussions, and connect with the community.</p>
                </div>
            </section>
            <section class="text-center bg-green-50 rounded-lg p-8 mb-12">
                <h2 class="text-2xl font-bold text-green-800 mb-4">Ready to Join?</h2>
                <p class="text-gray-600 mb-6">Sign up now and start your journey with Discover MyOMR.</p>
                <a href="/discover-myomr/pricing.php" class="inline-block bg-green-700 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-800 transition-colors">
                    View Plans & Pricing
                </a>
            </section>
            <!-- Areas Covered Section -->
            <section class="bg-green-50 rounded-lg p-6 mb-12">
                <h2 class="text-2xl font-bold text-green-700 mb-2">Areas We Cover</h2>
                <p class="text-gray-700 mb-4">MyOMR serves the entire OMR corridor, including Perungudi, Thuraipakkam, Karapakkam, Kandhanchavadi, Mettukuppam, Sholinganallur, Dollar Stop, IT Corridor, Tidel Park, Madhya Kailash, Navalur, Thazhambur, Kelambakkam, and more.</p>
                <a href="/discover-myomr/areas-covered.php" class="inline-block bg-green-700 text-white px-6 py-2 rounded font-medium hover:bg-green-800 transition-colors">See All Areas</a>
            </section>
        </main>

        <!-- Footer (Same as overview.php) -->
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

        <!-- Mobile Menu (Hidden by default) -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden" id="mobile-menu">
            <div class="bg-white h-full w-64 p-6">
                <div class="flex justify-between items-center mb-6">
                    <a href="/" class="text-2xl font-bold text-blue-600">MyOMR.in</a>
                    <button class="text-gray-600" onclick="toggleMobileMenu()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <nav class="space-y-4">
                    <a href="/info/onboarding/overview.php" class="block text-blue-600 font-medium">Overview</a>
                    <a href="/info/onboarding/getting-started.php" class="block text-gray-600">Getting Started</a>
                    <a href="/info/onboarding/features.php" class="block text-gray-600">Features</a>
                    <a href="/info/onboarding/community.php" class="block text-gray-600">Community</a>
                    <a href="/info/onboarding/support.php" class="block text-gray-600">Support</a>
                </nav>
            </div>
        </div>

        <!-- JavaScript -->
        <script>
            function toggleMobileMenu() {
                const mobileMenu = document.getElementById('mobile-menu');
                mobileMenu.classList.toggle('hidden');
            }
        </script>
    </body>
</html> 
