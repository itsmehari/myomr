<?php
/**
 * Site-wide CTA modals: Hire on OMR, Employer Pack, Subscribe / Job alerts, Top News.
 * Premium campaign layout — styles in modal-cta-campaign.css.
 * Triggers: data-omr-cta="post-job" | "employer-pack" | "subscribe" | "top-news"
 * Hire modal auto-triggers: modal-hire-on-omr.js
 * Interleaved news + Employer/Subscribe rotation: inline script below.
 */
if (!defined('ROOT_PATH')) {
    $ROOT_PATH = $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__, 2);
} else {
    $ROOT_PATH = ROOT_PATH;
}
$jobs_base = '/omr-local-job-listings';

require_once $ROOT_PATH . '/core/modal-top-news-config.php';
$omr_top_news_items = myomr_modal_top_news_items();
$omr_top_news_first = $omr_top_news_items[0] ?? [
    'slug' => '',
    'title' => 'OMR Local News',
    'summary' => 'Civic updates, traffic notes and useful reads for Old Mahabalipuram Road.',
    'category' => 'Local News',
    'image' => '/assets/images/modals/subscribe-omr-metro-evening.jpg',
];
$omr_top_news_first_url = myomr_modal_top_news_url((string) ($omr_top_news_first['slug'] ?? ''));
// Saturangam 360 chess promo: 10 days from 28 Oct 2026, hidden from 7 Nov 2026 00:00 IST.
$chess_modal_until = new DateTimeImmutable('2026-11-07 00:00:00', new DateTimeZone('Asia/Kolkata'));
$chess_modal_active = (new DateTimeImmutable('now', new DateTimeZone('Asia/Kolkata'))) < $chess_modal_until;
$chess_modal_image = '/assets/images/modals/saturangam-360-chess-2026.jpg';
$chess_register_url = 'https://www.saturangam360.com';

$omr_top_news_json = [];
foreach ($omr_top_news_items as $item) {
    $slug = trim((string) ($item['slug'] ?? ''));
    $omr_top_news_json[] = [
        'slug' => $slug,
        'title' => (string) ($item['title'] ?? ''),
        'summary' => (string) ($item['summary'] ?? ''),
        'category' => (string) ($item['category'] ?? 'Local News'),
        'image' => (string) ($item['image'] ?? '/assets/images/modals/subscribe-omr-metro-evening.jpg'),
        'objectPosition' => (string) ($item['object_position'] ?? 'center center'),
        'url' => myomr_modal_top_news_url($slug),
    ];
}
?>
<!-- Hire on OMR -->
<div class="modal fade omr-campaign-modal" id="omrCtaPostJob" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="omrCtaPostJobLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered omr-campaign-modal__dialog">
        <div class="modal-content omr-campaign-modal__content">
            <button type="button" class="omr-campaign-modal__close" data-bs-dismiss="modal" aria-label="Close modal">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
            <div class="omr-campaign-modal__grid">
                <div class="omr-campaign-modal__image-panel" aria-hidden="true">
                    <img class="omr-campaign-modal__image" src="/assets/images/modals/hire-omr-it-corridor.jpg" alt="" width="440" height="540" loading="lazy" decoding="async">
                    <div class="omr-campaign-modal__image-overlay"></div>
                    <span class="omr-campaign-modal__trust-badge"><i class="fas fa-shield-alt" aria-hidden="true"></i> Trusted by employers across OMR</span>
                </div>
                <div class="omr-campaign-modal__content-panel">
                    <span class="omr-campaign-modal__badge">Hire on OMR</span>
                    <h2 class="omr-campaign-modal__headline" id="omrCtaPostJobLabel">Reach Chennai&rsquo;s IT Corridor Talent</h2>
                    <p class="omr-campaign-modal__description">Post your vacancy on MyOMR and connect with a focused local audience across OMR &mdash; from Taramani to Siruseri.</p>
                    <ul class="omr-campaign-modal__benefits">
                        <li class="omr-campaign-modal__benefit"><span class="omr-campaign-modal__benefit-icon" aria-hidden="true"><i class="fas fa-map-marker-alt"></i></span><span>OMR-focused audience</span></li>
                        <li class="omr-campaign-modal__benefit"><span class="omr-campaign-modal__benefit-icon" aria-hidden="true"><i class="fas fa-comments"></i></span><span>Direct candidate enquiries</span></li>
                        <li class="omr-campaign-modal__benefit"><span class="omr-campaign-modal__benefit-icon" aria-hidden="true"><i class="fas fa-star"></i></span><span>Featured visibility with Employer Pack</span></li>
                    </ul>
                    <div class="omr-campaign-modal__micro-badges" aria-label="Listing highlights">
                        <span class="omr-campaign-modal__micro-badge"><i class="fas fa-check" aria-hidden="true"></i> Free Basic Listing</span>
                        <span class="omr-campaign-modal__micro-badge"><i class="fas fa-location-dot" aria-hidden="true"></i> Local Reach</span>
                        <span class="omr-campaign-modal__micro-badge"><i class="fas fa-bolt" aria-hidden="true"></i> Fast Setup</span>
                    </div>
                    <div class="omr-campaign-modal__cta-group">
                        <a href="<?php echo htmlspecialchars($jobs_base . '/post-job-omr.php', ENT_QUOTES, 'UTF-8'); ?>" class="omr-campaign-modal__cta-primary">
                            <i class="fas fa-briefcase omr-campaign-modal__cta-icon--left" aria-hidden="true"></i>
                            <span>Post a Job Free</span>
                            <i class="fas fa-arrow-right omr-campaign-modal__cta-arrow" aria-hidden="true"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($jobs_base . '/employer-pack-landing-omr.php', ENT_QUOTES, 'UTF-8'); ?>" class="omr-campaign-modal__cta-secondary">
                            <i class="fas fa-crown" aria-hidden="true"></i><span>View Employer Pack</span>
                        </a>
                        <button type="button" class="omr-campaign-modal__dismiss" data-bs-dismiss="modal">Maybe Later</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Employer Pack -->
<div class="modal fade omr-campaign-modal" id="omrCtaEmployerPack" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="omrCtaEmployerPackLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered omr-campaign-modal__dialog">
        <div class="modal-content omr-campaign-modal__content">
            <button type="button" class="omr-campaign-modal__close" data-bs-dismiss="modal" aria-label="Close modal">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
            <div class="omr-campaign-modal__grid">
                <div class="omr-campaign-modal__image-panel" aria-hidden="true">
                    <img class="omr-campaign-modal__image" src="/assets/images/modals/employer-omr-campus.jpg" alt="" width="440" height="540" loading="lazy" decoding="async">
                    <div class="omr-campaign-modal__image-overlay"></div>
                    <span class="omr-campaign-modal__trust-badge"><i class="fas fa-award" aria-hidden="true"></i> Built for OMR hiring teams</span>
                </div>
                <div class="omr-campaign-modal__content-panel">
                    <span class="omr-campaign-modal__badge">Employer Pack</span>
                    <h2 class="omr-campaign-modal__headline" id="omrCtaEmployerPackLabel">10 Featured Jobs Every Month</h2>
                    <p class="omr-campaign-modal__description">Stand out to OMR talent with priority placement and faster moderation &mdash; one simple plan for companies hiring regularly along the corridor.</p>
                    <ul class="omr-campaign-modal__benefits">
                        <li class="omr-campaign-modal__benefit"><span class="omr-campaign-modal__benefit-icon" aria-hidden="true"><i class="fas fa-star"></i></span><span>All listings featured on MyOMR</span></li>
                        <li class="omr-campaign-modal__benefit"><span class="omr-campaign-modal__benefit-icon" aria-hidden="true"><i class="fas fa-bolt"></i></span><span>Priority moderation &amp; visibility</span></li>
                        <li class="omr-campaign-modal__benefit"><span class="omr-campaign-modal__benefit-icon" aria-hidden="true"><i class="fas fa-file-invoice"></i></span><span>One plan, one monthly invoice</span></li>
                    </ul>
                    <div class="omr-campaign-modal__micro-badges" aria-label="Pack highlights">
                        <span class="omr-campaign-modal__micro-badge"><i class="fas fa-briefcase" aria-hidden="true"></i> 10 Jobs/Month</span>
                        <span class="omr-campaign-modal__micro-badge"><i class="fas fa-eye" aria-hidden="true"></i> Featured Listings</span>
                        <span class="omr-campaign-modal__micro-badge"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> OMR Reach</span>
                    </div>
                    <div class="omr-campaign-modal__cta-group">
                        <a href="<?php echo htmlspecialchars($jobs_base . '/employer-pack-landing-omr.php', ENT_QUOTES, 'UTF-8'); ?>" class="omr-campaign-modal__cta-primary">
                            <i class="fas fa-crown omr-campaign-modal__cta-icon--left" aria-hidden="true"></i>
                            <span>View Employer Pack</span>
                            <i class="fas fa-arrow-right omr-campaign-modal__cta-arrow" aria-hidden="true"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($jobs_base . '/post-job-omr.php', ENT_QUOTES, 'UTF-8'); ?>" class="omr-campaign-modal__cta-secondary">
                            <i class="fas fa-briefcase" aria-hidden="true"></i><span>Post a Job Free</span>
                        </a>
                        <button type="button" class="omr-campaign-modal__dismiss" data-bs-dismiss="modal">Maybe Later</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Subscribe / Job alerts -->
<div class="modal fade omr-campaign-modal" id="omrCtaSubscribe" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="omrCtaSubscribeLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered omr-campaign-modal__dialog">
        <div class="modal-content omr-campaign-modal__content">
            <button type="button" class="omr-campaign-modal__close" data-bs-dismiss="modal" aria-label="Close modal">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
            <div class="omr-campaign-modal__grid">
                <div class="omr-campaign-modal__image-panel" aria-hidden="true">
                    <img class="omr-campaign-modal__image" src="/assets/images/modals/subscribe-omr-metro-evening.jpg" alt="" width="440" height="540" loading="lazy" decoding="async">
                    <div class="omr-campaign-modal__image-overlay"></div>
                    <span class="omr-campaign-modal__trust-badge"><i class="fas fa-users" aria-hidden="true"></i> Join thousands on OMR</span>
                </div>
                <div class="omr-campaign-modal__content-panel">
                    <span class="omr-campaign-modal__badge">Stay Updated</span>
                    <h2 class="omr-campaign-modal__headline" id="omrCtaSubscribeLabel">OMR News, Events &amp; Job Alerts</h2>
                    <p class="omr-campaign-modal__description">Get useful local updates for Old Mahabalipuram Road in your inbox &mdash; civic news, weekend events, and fresh job posts. No spam.</p>
                    <ul class="omr-campaign-modal__benefits">
                        <li class="omr-campaign-modal__benefit"><span class="omr-campaign-modal__benefit-icon" aria-hidden="true"><i class="fas fa-newspaper"></i></span><span>Local news &amp; civic updates</span></li>
                        <li class="omr-campaign-modal__benefit"><span class="omr-campaign-modal__benefit-icon" aria-hidden="true"><i class="fas fa-bell"></i></span><span>Job alerts for OMR roles</span></li>
                        <li class="omr-campaign-modal__benefit"><span class="omr-campaign-modal__benefit-icon" aria-hidden="true"><i class="fas fa-calendar-alt"></i></span><span>Weekend events round-up</span></li>
                    </ul>
                    <div class="omr-campaign-modal__micro-badges" aria-label="Subscribe highlights">
                        <span class="omr-campaign-modal__micro-badge"><i class="fas fa-check" aria-hidden="true"></i> Free</span>
                        <span class="omr-campaign-modal__micro-badge"><i class="fas fa-location-dot" aria-hidden="true"></i> OMR Local</span>
                        <span class="omr-campaign-modal__micro-badge"><i class="fas fa-envelope-open" aria-hidden="true"></i> Unsubscribe Anytime</span>
                    </div>
                    <div class="omr-campaign-modal__cta-group">
                        <a href="#footer-subscribe" class="omr-campaign-modal__cta-primary" data-bs-dismiss="modal">
                            <i class="fas fa-bell omr-campaign-modal__cta-icon--left" aria-hidden="true"></i>
                            <span>Go to Subscribe</span>
                            <i class="fas fa-arrow-right omr-campaign-modal__cta-arrow" aria-hidden="true"></i>
                        </a>
                        <a href="/local-news/" class="omr-campaign-modal__cta-secondary">
                            <i class="fas fa-newspaper" aria-hidden="true"></i><span>Browse Local News</span>
                        </a>
                        <button type="button" class="omr-campaign-modal__dismiss" data-bs-dismiss="modal">Maybe Later</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top OMR News (single shell; JS fills from curated pool) -->
<div class="modal fade omr-campaign-modal omr-campaign-modal--news" id="omrCtaTopNews" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="omrCtaTopNewsLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered omr-campaign-modal__dialog">
        <div class="modal-content omr-campaign-modal__content">
            <button type="button" class="omr-campaign-modal__close" data-bs-dismiss="modal" aria-label="Close modal">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
            <div class="omr-campaign-modal__grid">
                <div class="omr-campaign-modal__image-panel" aria-hidden="true">
                    <img class="omr-campaign-modal__image" id="omrCtaTopNewsImage" src="<?php echo htmlspecialchars((string) $omr_top_news_first['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="" width="440" height="540" loading="lazy" decoding="async" style="object-position: <?php echo htmlspecialchars((string) ($omr_top_news_first['object_position'] ?? 'center center'), ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="omr-campaign-modal__image-overlay"></div>
                    <span class="omr-campaign-modal__trust-badge" id="omrCtaTopNewsTrust"><i class="fas fa-newspaper" aria-hidden="true"></i> Top OMR news</span>
                </div>
                <div class="omr-campaign-modal__content-panel">
                    <span class="omr-campaign-modal__badge">Top OMR News</span>
                    <h2 class="omr-campaign-modal__headline" id="omrCtaTopNewsLabel"><?php echo htmlspecialchars((string) $omr_top_news_first['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p class="omr-campaign-modal__description" id="omrCtaTopNewsDesc"><?php echo htmlspecialchars((string) $omr_top_news_first['summary'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <ul class="omr-campaign-modal__benefits">
                        <li class="omr-campaign-modal__benefit"><span class="omr-campaign-modal__benefit-icon" aria-hidden="true"><i class="fas fa-map-marker-alt"></i></span><span>Local OMR context</span></li>
                        <li class="omr-campaign-modal__benefit"><span class="omr-campaign-modal__benefit-icon" aria-hidden="true"><i class="fas fa-clock"></i></span><span>Quick read for residents</span></li>
                        <li class="omr-campaign-modal__benefit"><span class="omr-campaign-modal__benefit-icon" aria-hidden="true"><i class="fas fa-link"></i></span><span>Verified MyOMR reporting</span></li>
                    </ul>
                    <div class="omr-campaign-modal__micro-badges" aria-label="Article highlights">
                        <span class="omr-campaign-modal__micro-badge" id="omrCtaTopNewsCategory"><i class="fas fa-tag" aria-hidden="true"></i> <?php echo htmlspecialchars((string) $omr_top_news_first['category'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <span class="omr-campaign-modal__micro-badge"><i class="fas fa-location-dot" aria-hidden="true"></i> OMR Local</span>
                        <span class="omr-campaign-modal__micro-badge"><i class="fas fa-star" aria-hidden="true"></i> Editor pick</span>
                    </div>
                    <div class="omr-campaign-modal__cta-group">
                        <a href="<?php echo htmlspecialchars($omr_top_news_first_url, ENT_QUOTES, 'UTF-8'); ?>" class="omr-campaign-modal__cta-primary" id="omrCtaTopNewsCta">
                            <i class="fas fa-book-open omr-campaign-modal__cta-icon--left" aria-hidden="true"></i>
                            <span>Read article</span>
                            <i class="fas fa-arrow-right omr-campaign-modal__cta-arrow" aria-hidden="true"></i>
                        </a>
                        <a href="/local-news/" class="omr-campaign-modal__cta-secondary">
                            <i class="fas fa-newspaper" aria-hidden="true"></i><span>More local news</span>
                        </a>
                        <button type="button" class="omr-campaign-modal__dismiss" data-bs-dismiss="modal">Maybe Later</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($chess_modal_active): ?>
<!-- Saturangam 360 chess tournament — auto-removed after 6 Nov 2026 IST -->
<div class="modal fade omr-campaign-modal" id="omrCtaChess" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="omrCtaChessLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered omr-campaign-modal__dialog">
        <div class="modal-content omr-campaign-modal__content">
            <button type="button" class="omr-campaign-modal__close" data-bs-dismiss="modal" aria-label="Close modal">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
            <div class="omr-campaign-modal__grid">
                <div class="omr-campaign-modal__image-panel" aria-hidden="true">
                    <img class="omr-campaign-modal__image" src="<?php echo htmlspecialchars($chess_modal_image, ENT_QUOTES, 'UTF-8'); ?>" alt="" width="440" height="540" loading="lazy" decoding="async" style="object-position: center center;">
                    <div class="omr-campaign-modal__image-overlay"></div>
                    <span class="omr-campaign-modal__trust-badge"><i class="fas fa-chess-king" aria-hidden="true"></i> Saturangam 360 Academy · Since 1947</span>
                </div>
                <div class="omr-campaign-modal__content-panel">
                    <span class="omr-campaign-modal__badge">Perungudi · 15 Nov 2026</span>
                    <h2 class="omr-campaign-modal__headline" id="omrCtaChessLabel">4th Tamil Nadu State Level Children&rsquo;s &amp; Open Chess Tournament</h2>
                    <p class="omr-campaign-modal__description">Sunday, 15 November 2026 at BVM Global, 144 Corporation Road, Seevaram, Perungudi (next to Perungudi D Mart). Age groups Under 7, 9, 11, 13, 17 and Open. Entry fee &#8377;500.</p>
                    <ul class="omr-campaign-modal__benefits">
                        <li class="omr-campaign-modal__benefit"><span class="omr-campaign-modal__benefit-icon" aria-hidden="true"><i class="fas fa-medal"></i></span><span>Participation medal and printed certificate for every player</span></li>
                        <li class="omr-campaign-modal__benefit"><span class="omr-campaign-modal__benefit-icon" aria-hidden="true"><i class="fas fa-trophy"></i></span><span>150+ trophies for winners, boys and girls</span></li>
                        <li class="omr-campaign-modal__benefit"><span class="omr-campaign-modal__benefit-icon" aria-hidden="true"><i class="fas fa-rupee-sign"></i></span><span>Open category cash prizes up to &#8377;25,000</span></li>
                    </ul>
                    <div class="omr-campaign-modal__micro-badges" aria-label="Tournament highlights">
                        <span class="omr-campaign-modal__micro-badge"><i class="fas fa-calendar-day" aria-hidden="true"></i> Sun 15 Nov</span>
                        <span class="omr-campaign-modal__micro-badge"><i class="fas fa-ticket" aria-hidden="true"></i> Entry &#8377;500</span>
                        <span class="omr-campaign-modal__micro-badge"><i class="fas fa-child" aria-hidden="true"></i> Under 7 to Open</span>
                    </div>
                    <div class="omr-campaign-modal__cta-group">
                        <a href="<?php echo htmlspecialchars($chess_register_url, ENT_QUOTES, 'UTF-8'); ?>" class="omr-campaign-modal__cta-primary" target="_blank" rel="noopener noreferrer">
                            <i class="fas fa-chess omr-campaign-modal__cta-icon--left" aria-hidden="true"></i>
                            <span>Register now</span>
                            <i class="fas fa-arrow-right omr-campaign-modal__cta-arrow" aria-hidden="true"></i>
                        </a>
                        <a href="tel:+916380606143" class="omr-campaign-modal__cta-secondary">
                            <i class="fas fa-phone" aria-hidden="true"></i><span>Ms. B. Vidya · 63806 06143</span>
                        </a>
                        <p class="omr-campaign-modal__description" style="margin:0;">Ms. Jayanthi K · 90256 01304. Also register at easypaychess.com, chessentry.com, chessfee.com, bookmyfee.com, or signinchess.com.</p>
                        <button type="button" class="omr-campaign-modal__dismiss" data-bs-dismiss="modal">Maybe Later</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script type="application/json" id="omr-top-news-data"><?php echo json_encode($omr_top_news_json, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS); ?></script>
<script>
(function() {
    var NEWS_MODAL_ID = 'omrCtaTopNews';
    var CHESS_ACTIVE = <?php echo $chess_modal_active ? 'true' : 'false'; ?>;
    var ROTATION_SLOTS = CHESS_ACTIVE
        ? ['omrCtaChess', 'news', 'omrCtaEmployerPack', 'news', 'omrCtaSubscribe']
        : ['news', 'omrCtaEmployerPack', 'news', 'omrCtaSubscribe'];
    var CAMPAIGN_IDS = ['omrCtaPostJob', 'omrCtaEmployerPack', 'omrCtaSubscribe', NEWS_MODAL_ID];
    if (CHESS_ACTIVE) CAMPAIGN_IDS.push('omrCtaChess');
    var FIRST_VISIT_KEY = 'myomr_cta_first_visit_shown';
    var ROTATION_INDEX_KEY = 'myomr_cta_rotation_index';
    var NEWS_INDEX_KEY = 'myomr_cta_news_index';
    var FIRST_VISIT_DELAY_MS = 1500;
    var ROTATION_INTERVAL_MS = 2 * 60 * 1000;
    var FALLBACK_IMAGE = '/assets/images/modals/subscribe-omr-metro-evening.jpg';

    var newsItems = [];
    try {
        var dataEl = document.getElementById('omr-top-news-data');
        if (dataEl && dataEl.textContent) {
            newsItems = JSON.parse(dataEl.textContent) || [];
        }
    } catch (e) {
        newsItems = [];
    }

    function tagBackdrop() {
        document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
            backdrop.classList.add('omr-campaign-backdrop');
        });
    }

    function closeCtaModal(modalEl) {
        if (!modalEl) return;
        if (typeof bootstrap !== 'undefined') {
            var inst = bootstrap.Modal.getInstance(modalEl);
            if (inst) inst.hide(); else new bootstrap.Modal(modalEl).hide();
        }
    }

    function showCtaModalById(modalId) {
        var modal = document.getElementById(modalId);
        if (modal && typeof bootstrap !== 'undefined') {
            bootstrap.Modal.getOrCreateInstance(modal).show();
            return true;
        }
        return false;
    }

    function closeAnyOpenRotationModal() {
        CAMPAIGN_IDS.forEach(function(id) {
            if (id === 'omrCtaPostJob') return;
            var el = document.getElementById(id);
            if (el && el.classList.contains('show')) closeCtaModal(el);
        });
    }

    function getStoredIndex(key, mod) {
        try {
            var idx = parseInt(sessionStorage.getItem(key), 10);
            if (isNaN(idx) || mod < 1) return 0;
            return ((idx % mod) + mod) % mod;
        } catch (e) {
            return 0;
        }
    }

    function setStoredIndex(key, value) {
        try {
            sessionStorage.setItem(key, String(value));
        } catch (e) {}
    }

    function currentPathname() {
        try {
            return (window.location.pathname || '').replace(/\/+$/, '') || '/';
        } catch (e) {
            return '/';
        }
    }

    function articlePathFromItem(item) {
        if (!item || !item.url) return '';
        try {
            var u = new URL(item.url, window.location.origin);
            return (u.pathname || '').replace(/\/+$/, '');
        } catch (e) {
            return String(item.url || '').replace(/\/+$/, '');
        }
    }

    function isCurrentArticle(item) {
        var path = currentPathname();
        var articlePath = articlePathFromItem(item);
        if (!articlePath) return false;
        return path === articlePath || path.indexOf(articlePath) === 0;
    }

    function getNewsIndex() {
        return getStoredIndex(NEWS_INDEX_KEY, Math.max(newsItems.length, 1));
    }

    function setNewsIndex(idx) {
        setStoredIndex(NEWS_INDEX_KEY, idx);
    }

    function pickNewsItem(preferredIndex) {
        if (!newsItems.length) return null;
        var start = typeof preferredIndex === 'number' ? preferredIndex : getNewsIndex();
        var i;
        for (i = 0; i < newsItems.length; i++) {
            var idx = (start + i) % newsItems.length;
            if (!isCurrentArticle(newsItems[idx])) {
                return { item: newsItems[idx], index: idx };
            }
        }
        return { item: newsItems[start % newsItems.length], index: start % newsItems.length };
    }

    var lastShownNews = null;

    function fillNewsModal(item) {
        if (!item) return;
        lastShownNews = item;
        var img = document.getElementById('omrCtaTopNewsImage');
        var title = document.getElementById('omrCtaTopNewsLabel');
        var desc = document.getElementById('omrCtaTopNewsDesc');
        var cat = document.getElementById('omrCtaTopNewsCategory');
        var cta = document.getElementById('omrCtaTopNewsCta');
        if (img) {
            img.src = item.image || FALLBACK_IMAGE;
            img.style.objectPosition = item.objectPosition || 'center center';
            img.onerror = function() {
                this.onerror = null;
                this.style.objectPosition = 'center center';
                this.src = FALLBACK_IMAGE;
            };
        }
        if (title) title.textContent = item.title || 'OMR Local News';
        if (desc) desc.textContent = item.summary || '';
        if (cat) {
            cat.innerHTML = '<i class="fas fa-tag" aria-hidden="true"></i> ' + (item.category || 'Local News');
        }
        if (cta && item.url) cta.setAttribute('href', item.url);
    }

    function trackNewsEvent(action, item) {
        if (typeof gtag !== 'function' || !item) return;
        try {
            gtag('event', action, {
                event_category: 'modal_top_news',
                article_slug: item.slug || '',
                article_title: item.title || ''
            });
        } catch (e) {}
    }

    function showNewsModal(preferredIndex, advance) {
        var picked = pickNewsItem(preferredIndex);
        if (!picked || !picked.item) return false;
        fillNewsModal(picked.item);
        var shown = showCtaModalById(NEWS_MODAL_ID);
        if (shown) {
            trackNewsEvent('modal_top_news_view', picked.item);
            if (advance !== false) {
                setNewsIndex((picked.index + 1) % newsItems.length);
            }
        }
        return shown;
    }

    function showSlot(slot, options) {
        options = options || {};
        if (slot === 'news') {
            return showNewsModal(options.newsIndex, options.advanceNews !== false);
        }
        return showCtaModalById(slot);
    }

    function getRotationIndex() {
        return getStoredIndex(ROTATION_INDEX_KEY, ROTATION_SLOTS.length);
    }

    function setRotationIndex(idx) {
        setStoredIndex(ROTATION_INDEX_KEY, idx % ROTATION_SLOTS.length);
    }

    function showNextInRotation() {
        closeAnyOpenRotationModal();
        var idx = getRotationIndex();
        var slot = ROTATION_SLOTS[idx];
        showSlot(slot);
        setRotationIndex(idx + 1);
    }

    function showRandomFirstVisit() {
        if (CHESS_ACTIVE && document.getElementById('omrCtaChess')) {
            showCtaModalById('omrCtaChess');
            setRotationIndex(1);
            return;
        }
        var choices = [];
        if (newsItems.length) choices.push('news');
        choices.push('omrCtaEmployerPack');
        choices.push('omrCtaSubscribe');
        var pick = choices[Math.floor(Math.random() * choices.length)];
        if (pick === 'news') {
            var randomNews = Math.floor(Math.random() * newsItems.length);
            showNewsModal(randomNews, true);
            setRotationIndex(1);
            return;
        }
        showCtaModalById(pick);
        setRotationIndex(pick === 'omrCtaEmployerPack' ? 2 : 0);
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-omr-cta]').forEach(function(el) {
            el.addEventListener('click', function(e) {
                var id = el.getAttribute('data-omr-cta');
                if (id === 'post-job') return;
                if (id === 'top-news') {
                    e.preventDefault();
                    showNewsModal(getNewsIndex(), true);
                    return;
                }
                var modalId = id === 'employer-pack' ? 'omrCtaEmployerPack' : (id === 'subscribe' ? 'omrCtaSubscribe' : null);
                if (modalId) {
                    e.preventDefault();
                    showCtaModalById(modalId);
                }
            });
        });

        CAMPAIGN_IDS.forEach(function(id) {
            var modal = document.getElementById(id);
            if (!modal) return;
            /* Tag on show (before paint) so backdrop is never stuck under header z-index. */
            modal.addEventListener('show.bs.modal', function() {
                window.requestAnimationFrame(tagBackdrop);
                window.setTimeout(tagBackdrop, 0);
            });
            modal.addEventListener('shown.bs.modal', function() {
                tagBackdrop();
                var closeBtn = modal.querySelector('.omr-campaign-modal__close');
                if (closeBtn) closeBtn.focus();
            });
            modal.addEventListener('hidden.bs.modal', function() {
                document.querySelectorAll('.modal-backdrop.omr-campaign-backdrop').forEach(function (backdrop) {
                    backdrop.classList.remove('omr-campaign-backdrop');
                });
            });
        });

        var newsCta = document.getElementById('omrCtaTopNewsCta');
        if (newsCta) {
            newsCta.addEventListener('click', function() {
                if (lastShownNews) trackNewsEvent('modal_top_news_click', lastShownNews);
            });
        }

        /**
         * Suppress auto popups on long-read / detail pages so Bootstrap
         * modal-open (overflow:hidden) cannot trap scroll on first load.
         * Covers both /omr-local-events/event/ and /local-events/event/ hubs.
         */
        function shouldSkipAutoModal() {
            try {
                var path = window.location.pathname || '';
                var body = document.body;
                if (body) {
                    if (body.classList.contains('event-detail-page')) return true;
                    if (body.classList.contains('job-detail-page')) return true;
                    if (body.classList.contains('article-page')) return true;
                    if (body.classList.contains('ew-page')) return true;
                }
                if (/\/(omr-local-events|local-events)\/event\//.test(path)) return true;
                if (/\/local-news\/[^/?#]+/.test(path) && path.indexOf('news-highlights') === -1) return true;
                return false;
            } catch (err) {
                return false;
            }
        }

        function restoreScrollIfLockedWithoutVisibleModal() {
            try {
                var openModal = document.querySelector('.modal.show, .omr-campaign-modal.show');
                if (openModal) return;
                if (document.body.classList.contains('modal-open')) {
                    document.body.classList.remove('modal-open');
                    document.body.style.removeProperty('overflow');
                    document.body.style.removeProperty('padding-right');
                }
                document.querySelectorAll('.modal-backdrop').forEach(function (el) {
                    el.parentNode && el.parentNode.removeChild(el);
                });
            } catch (err) {}
        }

        var skipAutoModal = shouldSkipAutoModal();

        if (skipAutoModal) {
            /* Belt-and-suspenders: never leave a stuck campaign layer on detail pages. */
            closeAnyOpenRotationModal();
            CAMPAIGN_IDS.forEach(function (id) {
                var el = document.getElementById(id);
                if (el) closeCtaModal(el);
            });
            restoreScrollIfLockedWithoutVisibleModal();
            window.setTimeout(restoreScrollIfLockedWithoutVisibleModal, 2000);
        } else {
            try {
                if (!sessionStorage.getItem(FIRST_VISIT_KEY)) {
                    setTimeout(function () {
                        if (shouldSkipAutoModal()) return;
                        showRandomFirstVisit();
                        sessionStorage.setItem(FIRST_VISIT_KEY, '1');
                    }, FIRST_VISIT_DELAY_MS);
                }
            } catch (e) {}
            setInterval(function () {
                if (shouldSkipAutoModal()) return;
                showNextInRotation();
            }, ROTATION_INTERVAL_MS);
        }
    });
})();
</script>
