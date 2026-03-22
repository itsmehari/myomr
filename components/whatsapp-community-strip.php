<?php
/**
 * Site-wide minimal promo strip → WhatsApp community landing page.
 * Included from footer.php once per page. ~50px height, full width.
 */
if (!empty($GLOBALS['omr_wa_strip_rendered'])) {
    return;
}
$GLOBALS['omr_wa_strip_rendered'] = true;

$reqUri = $_SERVER['REQUEST_URI'] ?? '';
if (strpos($reqUri, 'join-omr-whatsapp-group-myomr.php') !== false) {
    return;
}

$stripPath = '/join-omr-whatsapp-group-myomr.php';
$stripQuery = [
    'utm_source'   => 'site_strip',
    'utm_medium'   => 'banner',
    'utm_campaign' => 'omr_whatsapp_global',
];
$stripHref = $stripPath . '?' . http_build_query($stripQuery, '', '&', PHP_QUERY_RFC3986);
?>
<style>
.omr-wa-strip {
  --omr-wa-strip-h: 50px;
  background: linear-gradient(90deg, #075e54 0%, #128c7e 55%, #1fa896 100%);
  color: #fff;
  min-height: var(--omr-wa-strip-h);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 0.75rem;
  font-family: 'Poppins', system-ui, sans-serif;
  font-size: 0.875rem;
  line-height: 1.25;
  box-shadow: 0 -1px 0 rgba(0,0,0,0.08) inset;
}
.omr-wa-strip__inner {
  max-width: 1280px;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.65rem;
  flex-wrap: wrap;
}
.omr-wa-strip__icon {
  flex-shrink: 0;
  width: 22px;
  height: 22px;
  opacity: 0.95;
  display: flex;
  align-items: center;
  justify-content: center;
}
.omr-wa-strip__icon svg {
  display: block;
  width: 20px;
  height: 20px;
  fill: currentColor;
}
.omr-wa-strip__text {
  flex: 1 1 auto;
  min-width: 0;
  text-align: center;
}
@media (max-width: 576px) {
  .omr-wa-strip__text .omr-wa-strip__long { display: none; }
  .omr-wa-strip__text .omr-wa-strip__short { display: inline; }
}
@media (min-width: 577px) {
  .omr-wa-strip__text .omr-wa-strip__short { display: none; }
}
.omr-wa-strip__btn {
  flex-shrink: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: 34px;
  padding: 0.35rem 1rem;
  background: #25d366;
  color: #05302c !important;
  font-weight: 600;
  text-decoration: none !important;
  border-radius: 999px;
  font-size: 0.8125rem;
  white-space: nowrap;
}
.omr-wa-strip__btn:hover,
.omr-wa-strip__btn:focus {
  background: #20bd5a;
  color: #042a26 !important;
}
</style>
<aside class="omr-wa-strip" aria-label="Join MyOMR WhatsApp community">
  <div class="omr-wa-strip__inner">
    <span class="omr-wa-strip__icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" role="presentation"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.881 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg></span>
    <p class="omr-wa-strip__text mb-0">
      <span class="omr-wa-strip__long">OMR updates, jobs &amp; local chat — join the official MyOMR WhatsApp community.</span>
      <span class="omr-wa-strip__short">MyOMR on WhatsApp — tap Join.</span>
    </p>
    <a class="omr-wa-strip__btn omr-wa-strip-cta"
       href="<?php echo htmlspecialchars($stripHref, ENT_QUOTES, 'UTF-8'); ?>">Join</a>
  </div>
</aside>
<script>
(function () {
  var a = document.querySelector('.omr-wa-strip-cta');
  if (!a) return;
  a.addEventListener('click', function () {
    if (typeof gtag !== 'function') return;
    gtag('event', 'whatsapp_strip_click', {
      link_url: a.getAttribute('href') || '',
      page_path: window.location.pathname
    });
  });
})();
</script>
