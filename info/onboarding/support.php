<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support | MyOMR.in Onboarding</title>
    <meta name="description" content="Need help with MyOMR.in? Find answers to common questions or contact our support team for assistance.">
    <meta name="keywords" content="MyOMR, support, help, FAQ, contact, onboarding">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-bold text-blue-600">MyOMR.in</a>
                <div class="hidden md:flex space-x-6">
                    <a href="/info/onboarding/overview.php" class="text-gray-600 hover:text-blue-600">Overview</a>
                    <a href="/info/onboarding/getting-started.php" class="text-gray-600 hover:text-blue-600">Getting Started</a>
                    <a href="/info/onboarding/features.php" class="text-gray-600 hover:text-blue-600">Features</a>
                    <a href="/info/onboarding/community.php" class="text-gray-600 hover:text-blue-600">Community</a>
                    <a href="/info/onboarding/support.php" class="text-blue-600 font-medium">Support</a>
                </div>
                <button class="md:hidden text-gray-600" onclick="toggleMobileMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-4 py-8">
        <!-- Hero Section (Inspired by reference) -->
        <section class="flex flex-col md:flex-row items-center bg-white rounded-lg shadow-md p-8 mb-12">
            <img src="https://via.placeholder.com/160x160.png?text=MyOMR" alt="Support" class="w-40 h-40 object-cover rounded-full mb-6 md:mb-0 md:mr-10 border-4 border-blue-100">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-blue-700 mb-2">Support & Help</h2>
                <p class="text-lg text-gray-600 mb-4">We're here to help you succeed on MyOMR.<br>Find answers, get in touch, and make the most of your onboarding experience.</p>
                <a href="/info/onboarding/overview.php" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors">Back to Overview</a>
            </div>
        </section>
        <!-- Four Feature Grid -->
        <section class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-blue-50 p-6 rounded-lg text-center shadow-sm">
                <div class="flex justify-center mb-4"><svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path d="M12 8v4l3 3" stroke-width="2"/></svg></div>
                <h4 class="font-semibold text-blue-700 mb-2">FAQs</h4>
                <p class="text-gray-600 text-sm">Browse frequently asked questions for quick solutions.</p>
            </div>
            <div class="bg-blue-50 p-6 rounded-lg text-center shadow-sm">
                <div class="flex justify-center mb-4"><svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2" stroke-width="2"/><path d="M8 12h8" stroke-width="2"/></svg></div>
                <h4 class="font-semibold text-blue-700 mb-2">Contact</h4>
                <p class="text-gray-600 text-sm">Reach out to our support team for personalized help.</p>
            </div>
            <div class="bg-blue-50 p-6 rounded-lg text-center shadow-sm">
                <div class="flex justify-center mb-4"><svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 20l9-5-9-5-9 5 9 5z" stroke-width="2"/><path d="M12 12V4" stroke-width="2"/></svg></div>
                <h4 class="font-semibold text-blue-700 mb-2">Guides</h4>
                <p class="text-gray-600 text-sm">Step-by-step guides to help you navigate MyOMR features.</p>
            </div>
            <div class="bg-blue-50 p-6 rounded-lg text-center shadow-sm">
                <div class="flex justify-center mb-4"><svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path d="M8 12h8" stroke-width="2"/></svg></div>
                <h4 class="font-semibold text-blue-700 mb-2">Community</h4>
                <p class="text-gray-600 text-sm">Connect with other users and share your experiences.</p>
            </div>
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