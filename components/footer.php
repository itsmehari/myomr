<?php
if (!isset($baseUrl)) {
    $base = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    $base = ($base === '/' || $base === '\\') ? '' : rtrim(str_replace('\\', '/', $base), '/');
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? '') . $base . '/';
}
$rootUrl = '/';
?>
<!--footer section begins -->
<footer class="footer-section" role="contentinfo">
    <div class="footer-cta">
        <div class="footer-cta__inner">
            <a href="https://www.google.com/maps/search/Old+Mahabalipuram+Road+Chennai" target="_blank" rel="noopener" class="footer-cta__item">
                <span class="footer-cta__icon"><i class="fas fa-map-marker-alt"></i></span>
                <div class="footer-cta__text">
                    <strong>Find us</strong>
                    <span>Old Mahabalipuram Road, Chennai</span>
                </div>
            </a>
            <a href="tel:9445088028" class="footer-cta__item">
                <span class="footer-cta__icon"><i class="fas fa-phone"></i></span>
                <div class="footer-cta__text">
                    <strong>Call us</strong>
                    <span>9445088028</span>
                </div>
            </a>
            <a href="mailto:myomrnews@gmail.com" class="footer-cta__item">
                <span class="footer-cta__icon"><i class="far fa-envelope-open"></i></span>
                <div class="footer-cta__text">
                    <strong>Mail us</strong>
                    <span>myomrnews@gmail.com</span>
                </div>
            </a>
        </div>
    </div>
    <div class="footer-main">
        <div class="footer-main__inner">
            <div class="footer-brand">
                <a href="<?php echo htmlspecialchars($rootUrl); ?>" class="footer-brand__logo">
                    <img src="<?php echo htmlspecialchars($rootUrl); ?>My-OMR-Idhu-Namma-OMR-Logo.jpg" alt="MyOMR - OMR Community" width="120" height="auto" loading="lazy">
                </a>
                <p class="footer-brand__tagline">Your local news portal and community hub for OMR — SRP Tools, Kandhanchavadi, Mettukuppam, Dollar Stop, IT corridor, Tidel Park, Madhya Kailash, Thazhambur & beyond.</p>
                <div class="footer-social">
                    <span class="footer-social__label">Follow us</span>
                    <div class="footer-social__links">
                        <a href="https://www.facebook.com/myomrCommunity" target="_blank" rel="noopener" aria-label="Facebook" class="footer-social__link footer-social__link--fb"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com/MyomrNews" target="_blank" rel="noopener" aria-label="Twitter" class="footer-social__link footer-social__link--tw"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/myomrcommunity/" target="_blank" rel="noopener" aria-label="Instagram" class="footer-social__link footer-social__link--ig"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <nav class="footer-nav">
                <div class="footer-nav__col">
                    <h4 class="footer-nav__title">Useful Links</h4>
                    <ul>
                        <li><a href="<?php echo $baseUrl; ?>index.php">Home</a></li>
                        <li><a href="<?php echo $baseUrl; ?>omr-road-database-list.php">OMR Database</a></li>
                        <li><a href="<?php echo $baseUrl; ?>contact-my-omr-team.php">Contact us</a></li>
                        <li><a href="<?php echo $baseUrl; ?>local-news/news-highlights-from-omr-road.php">Latest News</a></li>
                        <li><a href="<?php echo $baseUrl; ?>omr-rent-lease/">Rent & Lease</a></li>
                    </ul>
                </div>
                <div class="footer-nav__col">
                    <h4 class="footer-nav__title">Jobs by Location</h4>
                    <ul>
                        <li><a href="<?php echo $baseUrl; ?>jobs-in-omr-chennai.php">Jobs in OMR Chennai</a></li>
                        <li><a href="<?php echo $baseUrl; ?>jobs-in-perungudi-omr.php">Jobs in Perungudi</a></li>
                        <li><a href="<?php echo $baseUrl; ?>jobs-in-sholinganallur-omr.php">Jobs in Sholinganallur</a></li>
                        <li><a href="<?php echo $baseUrl; ?>jobs-in-navalur-omr.php">Jobs in Navalur</a></li>
                        <li><a href="<?php echo $baseUrl; ?>jobs-in-thoraipakkam-omr.php">Jobs in Thoraipakkam</a></li>
                        <li><a href="<?php echo $baseUrl; ?>jobs-in-kelambakkam-omr.php">Jobs in Kelambakkam</a></li>
                    </ul>
                </div>
                <div class="footer-nav__col">
                    <h4 class="footer-nav__title">Jobs by Industry</h4>
                    <ul>
                        <li><a href="<?php echo $baseUrl; ?>it-jobs-omr-chennai.php">IT Jobs in OMR</a></li>
                        <li><a href="<?php echo $baseUrl; ?>teaching-jobs-omr-chennai.php">Teaching Jobs</a></li>
                        <li><a href="<?php echo $baseUrl; ?>healthcare-jobs-omr-chennai.php">Healthcare Jobs</a></li>
                        <li><a href="<?php echo $baseUrl; ?>retail-jobs-omr-chennai.php">Retail Jobs</a></li>
                        <li><a href="<?php echo $baseUrl; ?>hospitality-jobs-omr-chennai.php">Hospitality Jobs</a></li>
                        <li><a href="<?php echo $baseUrl; ?>fresher-jobs-omr-chennai.php">Fresher Jobs</a></li>
                        <li><a href="<?php echo $baseUrl; ?>part-time-jobs-omr-chennai.php">Part-Time Jobs</a></li>
                    </ul>
                </div>
            </nav>
            <div class="footer-subscribe">
                <h4 class="footer-subscribe__title">Stay updated</h4>
                <p class="footer-subscribe__desc">Get OMR news, events & job alerts in your inbox.</p>
                <form class="footer-subscribe__form" action="#" method="post">
                    <input type="email" name="footer-email" placeholder="Your email" required aria-label="Email address">
                    <button type="submit" aria-label="Subscribe"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="footer-bottom__inner">
            <p class="footer-bottom__copy">© <?php echo date('Y'); ?> <a href="https://www.myomr.in">My OMR</a>. All rights reserved.</p>
            <nav class="footer-bottom__links" aria-label="Footer legal links">
                <a href="<?php echo $baseUrl; ?>terms-and-conditions-my-omr.php">Terms</a>
                <a href="<?php echo $baseUrl; ?>website-privacy-policy-of-my-omr.php">Privacy</a>
                <a href="<?php echo $baseUrl; ?>general-data-policy-of-my-omr.php">Policy</a>
                <a href="<?php echo $baseUrl; ?>webmaster-contact-my-omr.php">Contact</a>
            </nav>
        </div>
    </div>
</footer>
    <!--footer section ends -->
<script>
/* MyOMR GA4 — CTA click tracker (phone, WhatsApp, directions) */
(function () {
  if (typeof gtag !== 'function') return;
  document.addEventListener('click', function (e) {
    var a = e.target.closest('a[href]');
    if (!a) return;
    var h = a.href || '';
    if (/^tel:/i.test(h)) {
      gtag('event', 'phone_call', {
        'phone_number': h.replace(/^tel:/i, '').trim(),
        'listing_name': (a.closest('[data-listing-name]') || {}).dataset
          ? (a.closest('[data-listing-name]') || document.body).dataset.listingName || ''
          : ''
      });
    } else if (/wa\.me|api\.whatsapp\.com/i.test(h)) {
      gtag('event', 'whatsapp_click', {
        'destination': h.split('?')[0]
      });
    } else if (/maps\.google\.com|google\.com\/maps/i.test(h)) {
      gtag('event', 'get_directions', {
        'destination': a.textContent.trim().substring(0, 100)
      });
    }
  }, true);
})();
</script>
