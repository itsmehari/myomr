<?php
require_once __DIR__ . '/../core/site-navigation.php';
$hubLinks = myomr_get_primary_hub_links();
$rootUrl = '/';
if (function_exists('omr_flash_message')) {
    omr_flash_message();
}
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
                <p class="footer-brand__tagline">Chennai's IT corridor – jobs first. Your local news portal and community hub for OMR — SRP Tools, Kandhanchavadi, Mettukuppam, Dollar Stop, IT corridor, Tidel Park, Madhya Kailash, Thazhambur & beyond.</p>
                <div class="footer-social">
                    <span class="footer-social__label">Follow us</span>
                    <div class="footer-social__links">
                        <a href="https://www.facebook.com/myomrCommunity" target="_blank" rel="noopener" aria-label="Facebook" class="footer-social__link footer-social__link--fb"><i class="fab fa-facebook-f"></i></a>
                        <?php $fb_group = defined('MYOMR_FACEBOOK_GROUP_URL') ? MYOMR_FACEBOOK_GROUP_URL : 'https://www.facebook.com/groups/416854920508620'; ?>
                        <a href="<?php echo htmlspecialchars($fb_group); ?>" target="_blank" rel="noopener" aria-label="Join our Facebook group" class="footer-social__link footer-social__link--fb-group" title="Join our Facebook group"><i class="fas fa-users"></i></a>
                        <a href="https://twitter.com/MyomrNews" target="_blank" rel="noopener" aria-label="Twitter" class="footer-social__link footer-social__link--tw"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/myomrcommunity/" target="_blank" rel="noopener" aria-label="Instagram" class="footer-social__link footer-social__link--ig"><i class="fab fa-instagram"></i></a>
                        <?php $wa_group = defined('MYOMR_WHATSAPP_GROUP_URL') ? MYOMR_WHATSAPP_GROUP_URL : 'https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi'; ?>
                        <a href="<?php echo htmlspecialchars($wa_group); ?>" target="_blank" rel="noopener" aria-label="Join our WhatsApp group for updates" class="footer-social__link footer-social__link--wa" title="Join WhatsApp group"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <nav class="footer-nav">
                <div class="footer-nav__col">
                    <h4 class="footer-nav__title">Useful Links</h4>
                    <ul>
                        <?php foreach ($hubLinks as $hub): ?>
                            <li><a href="<?php echo htmlspecialchars($hub['path']); ?>"><?php echo htmlspecialchars($hub['label']); ?></a></li>
                        <?php endforeach; ?>
                        <li><a href="/omr-rent-lease/">Rent & Lease</a></li>
                        <li><a href="/omr-buy-sell/guidelines.php">Buy & Sell Guidelines</a></li>
                        <li><a href="/omr-classified-ads/">OMR Classified Ads</a></li>
                        <li><a href="/omr-classified-ads/guidelines.php">Classified Ads Guidelines</a></li>
                    </ul>
                </div>
                <div class="footer-nav__col">
                    <h4 class="footer-nav__title">Jobs by Location</h4>
                    <ul>
                        <li><a href="/jobs-in-omr-chennai.php">Jobs in OMR Chennai</a></li>
                        <li><a href="/jobs-in-perungudi-omr.php">Jobs in Perungudi</a></li>
                        <li><a href="/jobs-in-sholinganallur-omr.php">Jobs in Sholinganallur</a></li>
                        <li><a href="/jobs-in-navalur-omr.php">Jobs in Navalur</a></li>
                        <li><a href="/jobs-in-thoraipakkam-omr.php">Jobs in Thoraipakkam</a></li>
                        <li><a href="/jobs-in-kelambakkam-omr.php">Jobs in Kelambakkam</a></li>
                    </ul>
                </div>
                <div class="footer-nav__col">
                    <h4 class="footer-nav__title">Jobs by Industry</h4>
                    <ul>
                        <li><a href="/omr-local-job-listings/companies-hiring-omr.php">Companies hiring on OMR</a></li>
                        <li><a href="/it-jobs-omr-chennai.php">IT Jobs in OMR</a></li>
                        <li><a href="/teaching-jobs-omr-chennai.php">Teaching Jobs</a></li>
                        <li><a href="/healthcare-jobs-omr-chennai.php">Healthcare Jobs</a></li>
                        <li><a href="/retail-jobs-omr-chennai.php">Retail Jobs</a></li>
                        <li><a href="/hospitality-jobs-omr-chennai.php">Hospitality Jobs</a></li>
                        <li><a href="/fresher-jobs-omr-chennai.php">Fresher Jobs</a></li>
                        <li><a href="/part-time-jobs-omr-chennai.php">Part-Time Jobs</a></li>
                    </ul>
                </div>
            </nav>
            <div class="footer-subscribe" id="footer-subscribe">
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
                <a href="/terms-and-conditions-my-omr.php">Terms</a>
                <a href="/website-privacy-policy-of-my-omr.php">Privacy</a>
                <a href="/general-data-policy-of-my-omr.php">Policy</a>
                <a href="/webmaster-contact-my-omr.php">Contact</a>
            </nav>
        </div>
    </div>
</footer>
    <!--footer section ends -->
<?php
/* Bootstrap 5 for CTA modals: CSS + JS so modal-cta works in all browsers */
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="/assets/css/modal-cta-adobe.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php
if (file_exists(__DIR__ . '/modal-cta.php')) {
    require_once __DIR__ . '/modal-cta.php';
}
?>
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
