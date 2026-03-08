<?php
if (!isset($baseUrl)) {
    $baseUrl = rtrim((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';
}
?>
<!--footer section begins -->
<footer class="footer-section">
        <div class="container">
            <div class="footer-cta pt-5 pb-5">
                <div class="row">
                    <div class="col-xl-4 col-md-4 mb-30">
                        <div class="single-cta">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="cta-text">
                                <h4>Find us</h4>
                                <span>Old Mahabalipuram Road, Chennai</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 mb-30">
                        <div class="single-cta">
                            <i class="fas fa-phone"></i>
                            <div class="cta-text">
                                <h4>Call us</h4>
                                <span>9445088028</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 mb-30">
                        <div class="single-cta">
                            <i class="far fa-envelope-open"></i>
                            <div class="cta-text">
                                <h4>Mail us</h4>
                                <span>myomrnews@gmail.com</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-content pt-5 pb-5">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 mb-50">
                        <div class="footer-widget">
                            <div class="footer-logo">
                                <a href="<?php echo $baseUrl; ?>index.php"><img src="<?php echo $baseUrl; ?>My-OMR-Idhu-Namma-OMR-Logo.jpg" class="img-fluid" alt="My-Omr-Idhu-Namma-OMR-Logo" width="100"></a>
                            </div>
                            <div class="footer-text">
                                <p> online news portal and community website of OMR (old Mahabalipuram Road). We present you with news, events and happening in and around OMR. Areas covered are SRP Tools, Kandhanchavadi, Mettukuppam, Dollar Stop OMR, IT corridor, TidelPark, Madhya kailash, thazhambur. 
</p>
                            </div>
                            <div class="footer-social-icon">
                                <span>Follow us</span>
                                <a href="https://www.facebook.com/myomrCommunity" target="_blank" rel="noopener" aria-label="Follow MyOMR on Facebook"><i class="fab fa-facebook-f facebook-bg"></i></a>
                                <a href="https://twitter.com/MyomrNews" target="_blank" rel="noopener" aria-label="Follow MyOMR on Twitter"><i class="fab fa-twitter twitter-bg"></i></a>
                                <a href="https://www.instagram.com/myomrcommunity/" target="_blank" rel="noopener" aria-label="Follow MyOMR on Instagram"><i class="fab fa-instagram instagram-bg"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6 mb-30">
                        <div class="footer-widget">
                            <div class="footer-widget-heading">
                                <h3>Useful Links</h3>
                            </div>
                            <ul>
                                <li><a href="<?php echo $baseUrl; ?>index.php">Home</a></li>
                                <li><a href="<?php echo $baseUrl; ?>omr-road-database-list.php">OMR Database</a></li>
                                <li><a href="<?php echo $baseUrl; ?>contact-my-omr-team.php">Contact us</a></li>
                                <li><a href="<?php echo $baseUrl; ?>local-news/news-highlights-from-omr-road.php">Latest News</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 mb-30">
                        <div class="footer-widget">
                            <div class="footer-widget-heading">
                                <h3>Jobs by Location</h3>
                            </div>
                            <ul>
                                <li><a href="<?php echo $baseUrl; ?>jobs-in-omr-chennai.php">Jobs in OMR Chennai</a></li>
                                <li><a href="<?php echo $baseUrl; ?>jobs-in-perungudi-omr.php">Jobs in Perungudi</a></li>
                                <li><a href="<?php echo $baseUrl; ?>jobs-in-sholinganallur-omr.php">Jobs in Sholinganallur</a></li>
                                <li><a href="<?php echo $baseUrl; ?>jobs-in-navalur-omr.php">Jobs in Navalur</a></li>
                                <li><a href="<?php echo $baseUrl; ?>jobs-in-thoraipakkam-omr.php">Jobs in Thoraipakkam</a></li>
                                <li><a href="<?php echo $baseUrl; ?>jobs-in-kelambakkam-omr.php">Jobs in Kelambakkam</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 mb-30">
                        <div class="footer-widget">
                            <div class="footer-widget-heading">
                                <h3>Jobs by Industry</h3>
                            </div>
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
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6 mb-50">
                        <div class="footer-widget">
                            <div class="footer-widget-heading">
                                <h3>Subscribe</h3>
                            </div>
                            <div class="footer-text mb-25">
                                <p>Don't miss to subscribe to our new feeds, kindly fill the form below.</p>
                            </div>
                            <div class="subscribe-form">
                                <form action="#">
                                    <input type="text" placeholder="Email Address">
                                    <button><i class="fab fa-telegram-plane"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-area">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 text-center text-lg-left">
                        <div class="copyright-text">
                            <p>Copyright &copy; 2022, All Right Reserved <a href="https://www.myomr.in">My OMR</a></p>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 d-none d-lg-block text-right">
                        <div class="footer-menu">
                            <ul>
                                <li><a href="<?php echo $baseUrl; ?>terms-and-conditions-my-omr.php">Terms</a></li>
                                <li><a href="<?php echo $baseUrl; ?>website-privacy-policy-of-my-omr.php">Privacy</a></li>
                                <li><a href="<?php echo $baseUrl; ?>general-data-policy-of-my-omr.php">Policy</a></li>
                                <li><a href="<?php echo $baseUrl; ?>webmaster-contact-my-omr.php">Contact Webmaster</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
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
