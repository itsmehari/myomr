<?php
$page_title       = 'Support & Help | MyOMR OMR Chennai Community Portal';
$page_description = 'Need help with MyOMR? Find answers to common questions or contact our support team for assistance with listings, events, and more.';
$canonical_url    = 'https://myomr.in/discover-myomr/support.php';
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
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Support & Help</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                We're here to help! Find answers to common questions or contact our team for assistance.
            </p>
        </section>
        <!-- Areas Covered Section -->
        <section class="bg-green-50 rounded-lg p-6 mb-12">
            <h2 class="text-2xl font-bold text-green-700 mb-2">Areas We Cover</h2>
            <p class="text-gray-700 mb-4">MyOMR serves the entire OMR corridor, including Perungudi, Thuraipakkam, Karapakkam, Kandhanchavadi, Mettukuppam, Sholinganallur, Dollar Stop, IT Corridor, Tidel Park, Madhya Kailash, Navalur, Thazhambur, Kelambakkam, and more.</p>
            <a href="/discover-myomr/areas-covered.php" class="inline-block bg-green-700 text-white px-6 py-2 rounded font-medium hover:bg-green-800 transition-colors">See All Areas</a>
        </section>
        <section class="max-w-3xl mx-auto mb-12 space-y-8">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-semibold mb-2 text-blue-600">Frequently Asked Questions</h3>
                <ul class="list-disc list-inside text-gray-600 space-y-2">
                    <li>How do I create an account?</li>
                    <li>How can I update my profile?</li>
                    <li>How do I report a civic issue?</li>
                    <li>How do I join a community group?</li>
                    <li>Who do I contact for technical support?</li>
                </ul>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-semibold mb-2 text-blue-600">Contact Support</h3>
                <p class="text-gray-600 mb-2">If you need further assistance, please reach out:</p>
                <ul class="text-gray-600">
                    <li>Email: <a href="mailto:support@myomr.in" class="text-blue-600 underline">support@myomr.in</a></li>
                    <li>Phone: +91 XXXXXXXXXX</li>
                    <li>Or use our <a href="/contact-my-omr-team.php" class="text-blue-600 underline">Contact Form</a></li>
                </ul>
            </div>
        </section>
        <section class="text-center bg-blue-50 rounded-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Back to Onboarding</h2>
            <a href="/info/onboarding/overview.php" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                Overview
            </a>
        </section>
    </main>
    <!-- Footer (same as others) -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">MyOMR.in</h3>
                    <p class="text-gray-400">Your community platform for the OMR area.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="/info/onboarding/overview.php" class="text-gray-400 hover:text-white">Overview</a></li>
                        <li><a href="/info/onboarding/getting-started.php" class="text-gray-400 hover:text-white">Getting Started</a></li>
                        <li><a href="/info/onboarding/features.php" class="text-gray-400 hover:text-white">Features</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Resources</h4>
                    <ul class="space-y-2">
                        <li><a href="/info/onboarding/community.php" class="text-gray-400 hover:text-white">Community</a></li>
                        <li><a href="/info/onboarding/support.php" class="text-gray-400 hover:text-white">Support</a></li>
                        <li><a href="/info/website-privacy-policy-of-my-omr.php" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2">
                        <li class="text-gray-400">Email: support@myomr.in</li>
                        <li class="text-gray-400">Phone: +91 XXXXXXXXXX</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> MyOMR.in. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <!-- Mobile Menu -->
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
                <a href="/info/onboarding/overview.php" class="block text-gray-600">Overview</a>
                <a href="/info/onboarding/getting-started.php" class="block text-gray-600">Getting Started</a>
                <a href="/info/onboarding/features.php" class="block text-gray-600">Features</a>
                <a href="/info/onboarding/community.php" class="block text-gray-600">Community</a>
                <a href="/info/onboarding/support.php" class="block text-blue-600 font-medium">Support</a>
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
