<?php
/**
 * Printed-flyer QR landing.
 * Canonical: https://myomr.in/welcome
 */
$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__;
require_once $root . '/core/include-path.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once ROOT_PATH . '/core/welcome-flyer-config.php';
require_once ROOT_PATH . '/core/site-contact.php';
require_once ROOT_PATH . '/core/omr-pulse-stats.php';
require_once ROOT_PATH . '/core/schema-faq.php';
require_once ROOT_PATH . '/components/page-bootstrap.php';

$canonical_url = myomr_welcome_page_url();
$page_title = 'Welcome to MyOMR | OMR news, jobs, listings and community';
$page_description = 'You scanned the MyOMR flyer. Start here for local news, nearby jobs, businesses, events and the official WhatsApp community along Old Mahabalipuram Road, Chennai.';
$page_keywords = 'MyOMR welcome, OMR flyer, OMR Chennai news jobs events, Sholinganallur Navalur Perungudi WhatsApp community';
$og_type = 'website';
$og_title = $page_title;
$og_description = $page_description;
$og_image = 'https://myomr.in/assets/images/home/hero-omr-corridor.webp';
$og_url = $canonical_url;
$twitter_title = $page_title;
$twitter_description = $page_description;
$twitter_image = $og_image;
$page_nav = 'main';
$omr_css_bootstrap5 = true;
$ga_content_group = 'Flyer Welcome';
$ga_custom_params = ['campaign' => 'welcome_qr'];
$GLOBALS['omr_hide_whatsapp_float'] = true;
$GLOBALS['omr_hide_wa_strip'] = true;

$destinations = myomr_welcome_destinations();
$more_links = myomr_welcome_more_links();
$audiences = myomr_welcome_audiences();
$localities = myomr_welcome_localities();
$faq = myomr_welcome_faq();
$counts = [
    'jobs' => 0,
    'events' => 0,
    'news' => 0,
    'classifieds' => 0,
    'rentals' => 0,
];
try {
    $counts = omr_pulse_stats();
} catch (Throwable $e) {
    error_log('welcome pulse stats skipped: ' . $e->getMessage());
}
$contact_email = defined('MYOMR_CONTACT_EMAIL') ? MYOMR_CONTACT_EMAIL : 'myomrnews@gmail.com';
$wa_hero = myomr_welcome_whatsapp_url('hero');
$wa_dock = myomr_welcome_whatsapp_url('sticky');
$wa_panel = myomr_welcome_whatsapp_url('panel');
$subscribed = isset($_GET['subscribed']);
$sub_error = isset($_GET['subscribe_error']);

$css_path = ROOT_PATH . '/assets/css/welcome-flyer.css';
$css_ver = is_file($css_path) ? (string) filemtime($css_path) : '1';
$json_flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
$faq_jsonld = myomr_faq_page_jsonld($faq);

$count_labels = [
    'news' => 'stories',
    'jobs' => 'open jobs',
    'classifieds' => 'listings',
    'events' => 'events',
];
?>
<!DOCTYPE html>
<html lang="en-IN">
<head>
<?php require_once ROOT_PATH . '/components/meta.php'; ?>
<?php require_once ROOT_PATH . '/components/head-includes.php'; ?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,560;1,9..144,560&family=Source+Sans+3:wght@400;600;700;800&display=swap">
<link rel="stylesheet" href="/assets/css/welcome-flyer.css?v=<?php echo htmlspecialchars($css_ver, ENT_QUOTES, 'UTF-8'); ?>">
<script type="application/ld+json"><?php
echo json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    'name' => $page_title,
    'description' => $page_description,
    'url' => $canonical_url,
    'inLanguage' => 'en-IN',
    'about' => 'MyOMR printed flyer landing for the OMR corridor, Chennai',
    'isPartOf' => [
        '@type' => 'WebSite',
        'name' => 'MyOMR',
        'url' => 'https://myomr.in',
    ],
], $json_flags);
?></script>
<?php if (is_array($faq_jsonld)): ?>
<script type="application/ld+json"><?php echo json_encode($faq_jsonld, $json_flags); ?></script>
<?php endif; ?>
</head>
<body class="wel-page">
<?php require_once ROOT_PATH . '/components/skip-link.php'; ?>
<?php omr_nav('main'); ?>

<main id="main-content">
  <section class="wel-hero" aria-labelledby="wel-title">
    <div class="wel-wrap wel-hero__grid">
      <div>
        <p class="wel-kicker"><i></i> You scanned the flyer</p>
        <h1 id="wel-title">Explore. Engage. <em>&amp; Grow</em></h1>
        <p class="wel-lead">One platform for the OMR corridor. Local news, nearby jobs, businesses, events and community updates — without hunting across ten apps.</p>
        <ul class="wel-places" aria-label="Areas MyOMR covers">
          <?php foreach ($localities as $place): ?>
            <li><?php echo htmlspecialchars($place, ENT_QUOTES, 'UTF-8'); ?></li>
          <?php endforeach; ?>
        </ul>
        <div class="wel-actions">
          <a class="wel-btn wel-btn--lime" href="<?php echo htmlspecialchars($wa_hero, ENT_QUOTES, 'UTF-8'); ?>" rel="noopener noreferrer" onclick="if(typeof gtag==='function') gtag('event','flyer_welcome_cta',{event_category:'Flyer','event_label':'whatsapp_hero'});">Join WhatsApp</a>
          <a class="wel-btn wel-btn--ghost" href="#start" onclick="if(typeof gtag==='function') gtag('event','flyer_welcome_cta',{event_category:'Flyer','event_label':'start_here'});">Start here</a>
        </div>
        <p class="wel-note">News, jobs, events and listings are free to browse. Paid plans are for businesses who want extra visibility.</p>
      </div>
      <div class="wel-media" aria-hidden="true">
        <div class="wel-badge">
          <div>
            <span>Just</span>
            <strong>start</strong>
            <span>free</span>
          </div>
        </div>
        <div class="wel-media__ring">
          <picture>
            <source srcset="/assets/images/home/hero-omr-corridor.webp" type="image/webp">
            <img src="/assets/images/home/hero-omr-twilight.png" width="640" height="640" alt="">
          </picture>
        </div>
      </div>
    </div>
  </section>

  <section class="wel-section wel-section--shift" id="start" aria-labelledby="start-title">
    <div class="wel-wrap">
      <h2 id="start-title">Pick what you came for</h2>
      <p class="wel-intro">These four tiles match the flyer. One tap takes you into the live section — not a brochure mock-up.</p>
      <div class="wel-tiles">
        <?php foreach ($destinations as $tile):
            $key = $tile['count_key'];
            $n = (int) ($counts[$key] ?? 0);
            $unit = $count_labels[$key] ?? '';
            ?>
          <a class="wel-tile" href="<?php echo htmlspecialchars($tile['href'], ENT_QUOTES, 'UTF-8'); ?>" onclick="if(typeof gtag==='function') gtag('event','flyer_welcome_cta',{event_category:'Flyer','event_label':'<?php echo htmlspecialchars($tile['id'], ENT_QUOTES, 'UTF-8'); ?>'});">
            <?php if ($n > 0 && $unit !== ''): ?>
              <span class="wel-tile__count"><?php echo htmlspecialchars(number_format($n) . ' ' . $unit, ENT_QUOTES, 'UTF-8'); ?></span>
            <?php endif; ?>
            <h3><?php echo htmlspecialchars($tile['label'], ENT_QUOTES, 'UTF-8'); ?></h3>
            <p><?php echo htmlspecialchars($tile['blurb'], ENT_QUOTES, 'UTF-8'); ?></p>
            <span class="wel-tile__cta"><?php echo htmlspecialchars($tile['cta'], ENT_QUOTES, 'UTF-8'); ?> →</span>
          </a>
        <?php endforeach; ?>
      </div>
      <ul class="wel-more">
        <?php foreach ($more_links as $link): ?>
          <li><a href="<?php echo htmlspecialchars($link['href'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8'); ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <section class="wel-section" aria-labelledby="who-title">
    <div class="wel-wrap">
      <h2 id="who-title">Who just scanned this?</h2>
      <p class="wel-intro">Same flyer, three different next steps.</p>
      <div class="wel-audiences">
        <?php foreach ($audiences as $aud): ?>
          <article class="wel-aud">
            <h3><?php echo htmlspecialchars($aud['label'], ENT_QUOTES, 'UTF-8'); ?></h3>
            <p><?php echo htmlspecialchars($aud['blurb'], ENT_QUOTES, 'UTF-8'); ?></p>
            <a href="<?php echo htmlspecialchars($aud['href'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($aud['cta'], ENT_QUOTES, 'UTF-8'); ?> →</a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="wel-section" id="join" aria-labelledby="join-title">
    <div class="wel-wrap wel-split">
      <div class="wel-panel wel-panel--dark">
        <h2 id="join-title">Stay in the OMR loop</h2>
        <p class="wel-intro">The WhatsApp community is the fastest way to get neighbourhood news, jobs and civic notes after you pocket the flyer.</p>
        <div class="wel-actions">
          <a class="wel-btn wel-btn--wa" href="<?php echo htmlspecialchars($wa_panel, ENT_QUOTES, 'UTF-8'); ?>" rel="noopener noreferrer" onclick="if(typeof gtag==='function') gtag('event','flyer_welcome_cta',{event_category:'Flyer','event_label':'whatsapp_panel'});">Join WhatsApp community</a>
          <a class="wel-btn wel-btn--ghost" href="/omr-whatsapp-group">Read the rules first</a>
        </div>
      </div>
      <div class="wel-panel">
        <h2>Email digest</h2>
        <p class="wel-intro">Jobs, news and events in your inbox. No daily spam.</p>
        <?php if ($subscribed): ?>
          <p class="wel-alert wel-alert--ok" role="status">You are subscribed. Welcome to the OMR list.</p>
        <?php elseif ($sub_error): ?>
          <p class="wel-alert wel-alert--err" role="alert">Please enter a valid email address.</p>
        <?php endif; ?>
        <?php if (!$subscribed): ?>
        <form class="wel-form" method="post" action="/components/subscribe.php" onsubmit="if(typeof gtag==='function') gtag('event','subscribe',{event_category:'Newsletter',event_label:'welcome_flyer'});">
          <input type="hidden" name="source_url" value="/welcome">
          <label for="wel-email">Email</label>
          <input type="email" id="wel-email" name="email" required autocomplete="email" inputmode="email" placeholder="you@example.com">
          <button class="wel-btn wel-btn--ink" type="submit">Send me updates</button>
        </form>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="wel-section wel-faq" id="faq" aria-labelledby="faq-title">
    <div class="wel-wrap">
      <h2 id="faq-title">Before you wander off</h2>
      <?php foreach ($faq as $item): ?>
        <details>
          <summary><?php echo htmlspecialchars($item['q'], ENT_QUOTES, 'UTF-8'); ?></summary>
          <p><?php echo htmlspecialchars($item['a'], ENT_QUOTES, 'UTF-8'); ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="wel-section" aria-labelledby="contact-title">
    <div class="wel-wrap">
      <h2 id="contact-title">Contact MyOMR</h2>
      <p class="wel-intro">Same details as the flyer, minus a dead QR.</p>
      <ul class="wel-contact">
        <li><a href="https://myomr.in/">myomr.in</a></li>
        <li><a href="<?php echo htmlspecialchars(myomr_contact_mailto_href(), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($contact_email, ENT_QUOTES, 'UTF-8'); ?></a></li>
        <li><a href="/contact-my-omr-team.php">Write to the team</a></li>
        <li><a href="/advertise">Advertise on MyOMR</a></li>
      </ul>
    </div>
  </section>
</main>

<div class="wel-dock" role="navigation" aria-label="Quick actions">
  <a class="wel-btn wel-btn--wa" href="<?php echo htmlspecialchars($wa_dock, ENT_QUOTES, 'UTF-8'); ?>" rel="noopener noreferrer">WhatsApp</a>
  <a class="wel-btn wel-btn--lime" href="/omr-local-job-listings/jobs-hub-omr.php">Jobs</a>
</div>

<?php omr_footer(); ?>
</body>
</html>
