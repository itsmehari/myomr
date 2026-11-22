<?php
/**
 * Publish: SriSon Naturopathy and Yoga Centre, Nanganallur —
 * 2nd National Yogasana Championship, SRM IST Acharapakkam (22 Nov 2026).
 *
 * Sources: centre note and photographs (22 Nov 2026); championship notice
 * from Directorate of Sports, SRM IST / Easy Yoga Studioz / Padmam Yoga;
 * Institution Honour plaque dated 22 November 2026.
 *
 * Usage (PowerShell):
 *   $env:DB_HOST='myomr.in'; php dev-tools/sql/run-srison-yoga-nanganallur-srm-acharapakkam-yogasana-championship-2026.php
 */
declare(strict_types=1);

require_once __DIR__ . '/../../core/omr-connect.php';
require_once __DIR__ . '/../../core/publishing/news-article-publisher.php';

$slug = 'srison-yoga-nanganallur-srm-acharapakkam-yogasana-championship-2026';

$title = 'SriSon Yoga Students Win Championship Honours at SRM Acharapakkam Meet';

$summary = 'Students from SriSon Naturopathy and Yoga Centre, Nanganallur, took part in the 2nd National Yogasana Championship at SRM IST Acharapakkam on 22 November 2026.';

$imgStudents = '/local-news/omr-news-images/srison-yoga-nanganallur-srm-acharapakkam-yogasana-championship-2026-students.jpg';
$imgTrophies = '/local-news/omr-news-images/srison-yoga-nanganallur-srm-acharapakkam-yogasana-championship-2026-trophies.jpg';
$imgCertificates = '/local-news/omr-news-images/srison-yoga-nanganallur-srm-acharapakkam-yogasana-championship-2026-certificates.jpg';
$imgHonour = '/local-news/omr-news-images/srison-yoga-nanganallur-srm-acharapakkam-yogasana-championship-2026-institution-honour.jpg';
$imagePath = $imgStudents;

$category = 'Local News / Sports / Community';
$author = 'MyOMR Editorial Team';
$status = 'published';
$publishedDate = '2026-11-22 18:30:00';

$tags = 'SriSon Naturopathy and Yoga Centre, Nanganallur, yogasana, yoga championship, SRM IST Acharapakkam, Chengalpattu, Padmam Yoga, Easy Yoga Studioz, student sports, Chennai yoga, MyOMR News';

$eventsUrl = 'https://myomr.in/omr-local-events/';
$newsUrl = 'https://myomr.in/local-news/';

$faqItems = [
    [
        'q' => 'When and where was the 2nd National Yogasana Championship 2026 held?',
        'a' => 'The championship was held on Sunday, 22 November 2026, at the SRM Institute of Science and Technology campus in Acharapakkam, Chengalpattu district.',
    ],
    [
        'q' => 'Which Chennai yoga centre took part?',
        'a' => 'Students from SriSon Naturopathy and Yoga Centre, Nanganallur, Chennai 600 061, took part. The centre later received an Institution Honour plaque from the organisers.',
    ],
    [
        'q' => 'Which SriSon students won championship honours?',
        'a' => 'The centre said Adarsh, Elandeeran and Sri Raman won championship honours. A full official result list was not available at the time of this report.',
    ],
    [
        'q' => 'Who organised the championship?',
        'a' => 'The meet was organised by the Directorate of Sports at SRM Institute of Science and Technology, with Easy Yoga Studioz and Padmam Yoga Wellness Centre.',
    ],
];

$faqHtml = '';
foreach ($faqItems as $item) {
    $q = htmlspecialchars($item['q'], ENT_QUOTES, 'UTF-8');
    $a = htmlspecialchars($item['a'], ENT_QUOTES, 'UTF-8');
    $faqHtml .= '<div class="ysn-faq-item"><h3 class="ysn-faq-q">' . $q . '</h3><p class="ysn-faq-a">' . $a . '</p></div>';
}

$content = <<<HTML
<div class="ysn-feature">
<style>
.ysn-feature{--ink:#1c1917;--muted:#57534e;--brand:#0f4c3a;--brand-mid:#0d6b4f;--accent:#b45309;--soft:#ecfdf5;--line:#a7f3d0;--shadow:0 12px 32px rgba(15,76,58,.1);font-family:"Poppins","Segoe UI",system-ui,sans-serif;color:var(--ink);font-size:17px;line-height:1.75;max-width:100%}
.ysn-feature *{box-sizing:border-box}
.ysn-feature h2,.ysn-feature h3{font-family:"Poppins",system-ui,sans-serif!important;border:none!important;padding:0!important;margin:1.45rem 0 .6rem!important;color:var(--brand)!important;font-weight:700!important;line-height:1.3!important}
.ysn-feature h2{font-size:1.32rem!important;padding-bottom:.4rem!important;border-bottom:2px solid var(--soft)!important}
.ysn-feature h3{font-size:1.08rem!important}
.ysn-hero{background:linear-gradient(135deg,#14532d 0%,#0f4c3a 55%,#0d6b4f 100%);color:#fff;padding:1.55rem 1.35rem;border-radius:14px;margin:0 0 1.35rem;box-shadow:var(--shadow)}
.ysn-hero .eyebrow{font:700 .72rem/1.2 "Poppins",sans-serif;letter-spacing:.12em;text-transform:uppercase;opacity:.92;margin:0 0 .45rem}
.ysn-hero .sub{font-size:1.06rem;line-height:1.55;margin:0;color:#d1fae5;font-weight:500}
.ysn-lead{font-size:1.1rem;font-weight:600;color:#1a2332;margin:0 0 1rem;line-height:1.62}
.ysn-dateline{font-size:.92rem;color:var(--muted);margin:0 0 1rem}
.ysn-pills{display:flex;flex-wrap:wrap;gap:8px;margin:1rem 0 1.4rem}
.ysn-pill{padding:.32rem .72rem;background:#fff;color:var(--brand-mid);border:1px solid var(--line);border-radius:999px;font-size:.76rem;font-weight:600}
.ysn-figure{margin:1.4rem 0;border-radius:14px;overflow:hidden;border:1px solid #e7e5e4;background:#fff;box-shadow:0 6px 20px rgba(28,25,23,.06)}
.ysn-figure img{display:block;width:100%;height:auto;margin:0}
.ysn-figure figcaption{padding:.8rem 1rem;font-size:.88rem;color:var(--muted);line-height:1.5;background:#fafaf9;border-top:1px solid #e7e5e4}
.ysn-facts{margin:1.2rem 0;padding:1rem 1.15rem;border-radius:12px;background:var(--soft);border:1px solid var(--line)}
.ysn-facts dt{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--brand);margin:.65rem 0 .2rem}
.ysn-facts dt:first-child{margin-top:0}
.ysn-facts dd{margin:0;font-size:.95rem;font-weight:600;color:#1c1917}
.ysn-note{margin:1.5rem 0 0;padding:1rem 1.1rem;border-radius:10px;background:#f8fafc;border-left:4px solid #64748b;font-size:.88rem;color:#475569;line-height:1.6}
.ysn-src{margin:1.2rem 0 0;font-size:.86rem;color:var(--muted);line-height:1.55}
.ysn-faq{margin:1.6rem 0 1.2rem}
.ysn-faq-item{background:#fff;border:1px solid #e7e5e4;border-radius:12px;padding:1rem 1.15rem;margin:0 0 12px}
.ysn-faq-q{margin:0 0 .4rem!important;font-size:1.02rem!important;color:var(--brand)!important;border:none!important;padding:0!important}
.ysn-faq-a{margin:0;font-size:.96rem;color:#44403c}
.ysn-closing{margin:1.4rem 0 .5rem;padding:1.05rem 1.2rem;background:linear-gradient(135deg,#14532d,#0f4c3a);color:#fff;border-radius:14px;font-size:1rem;line-height:1.6}
.ysn-closing p{margin:.2rem 0;color:#d1fae5}
@media (max-width:600px){.ysn-hero{padding:1.2rem 1rem}}
</style>

<div class="ysn-hero">
<p class="eyebrow">Acharapakkam, Chengalpattu &middot; Sunday, 22 November 2026</p>
<p class="sub">Students from <strong>SriSon Naturopathy and Yoga Centre, Nanganallur</strong>, took part in the 2nd National Yogasana Championship at SRM Institute of Science and Technology, Acharapakkam.</p>
</div>

<p class="ysn-lead"><strong>Acharapakkam:</strong> A group of young students from <strong>SriSon Naturopathy and Yoga Centre</strong> in Nanganallur, Chennai, competed on Sunday, 22 November 2026, at the <strong>2nd National Yogasana Championship</strong> held on the SRM Institute of Science and Technology campus in Acharapakkam, Chengalpattu district.</p>

<p class="ysn-dateline"><strong>22 November 2026</strong> &mdash; By <em>MyOMR Editorial Team</em></p>

<div class="ysn-pills">
<span class="ysn-pill">Yogasana</span>
<span class="ysn-pill">Nanganallur</span>
<span class="ysn-pill">SRM IST Acharapakkam</span>
<span class="ysn-pill">Chengalpattu</span>
<span class="ysn-pill">Student sports</span>
</div>

<p>The meet was organised by the <strong>Directorate of Sports</strong> at SRM Institute of Science and Technology, with <strong>Easy Yoga Studioz</strong> and <strong>Padmam Yoga Wellness Centre</strong>. According to the championship notice, the competition was open to individual participants as well as students from schools, colleges and recognised yoga institutions.</p>

<figure class="ysn-figure">
<img src="{$imgStudents}" alt="Students of SriSon Naturopathy and Yoga Centre, Nanganallur, with certificates and medals after the 2nd National Yogasana Championship at SRM Acharapakkam" loading="lazy" width="1200" height="675">
<figcaption>SriSon students and coaches with certificates after the championship at SRM Institute of Science and Technology, Acharapakkam, on 22 November 2026. Several children wore the centre&rsquo;s kit, marked Srison.</figcaption>
</figure>

<h2>Event snapshot</h2>

<dl class="ysn-facts">
<dt>Event</dt>
<dd>2nd National Yogasana Championship &ndash; 2026</dd>
<dt>Date</dt>
<dd>Sunday, 22 November 2026</dd>
<dt>Venue</dt>
<dd>SRM Institute of Science and Technology, Acharapakkam campus, Chengalpattu district</dd>
<dt>Organised by</dt>
<dd>Directorate of Sports, SRM IST, with Easy Yoga Studioz and Padmam Yoga Wellness Centre</dd>
<dt>Chennai centre</dt>
<dd>SriSon Naturopathy and Yoga Centre, Nanganallur, Chennai &ndash; 600 061</dd>
</dl>

<h2>Championship honours named by the centre</h2>

<p>SriSon told MyOMR that three of its students &mdash; <strong>Adarsh</strong>, <strong>Elandeeran</strong> and <strong>Sri Raman</strong> &mdash; won championship honours at the meet. The championship notice said the championship round was open to first-place finishers in the 10&ndash;21 age groups, with a champion trophy for the winner of that round.</p>

<p>A complete official result sheet, with age groups and places for every SriSon participant, was not shared at the time of publication. Photographs from the venue show several students from the centre receiving certificates, and some holding medals or trophies on the presentation stage.</p>

<figure class="ysn-figure">
<img src="{$imgTrophies}" alt="Students with trophies and certificates on stage at the 2nd National Yogasana Championship 2026, SRM IST Acharapakkam" loading="lazy" width="1200" height="900">
<figcaption>Trophy presentation on the indoor stage at SRM IST Acharapakkam, with the championship title displayed on the screens behind the participants.</figcaption>
</figure>

<h2>How the competition was run</h2>

<p>The organisers&rsquo; notice set a standard yogasana format: three compulsory asanas from a prescribed chart, plus two asanas of the participant&rsquo;s choice. Age groups ranged from 6&ndash;7 years through 56 and above. The championship round, by the same notice, was limited to the 10&ndash;21 age groups and only to those who had already taken first place in their category.</p>

<p>Prizes listed by the organisers included participation certificates for all entrants, medals and merit certificates for the top three in each age group (separately for male and female), and merit certificates for fourth to tenth places. Yoga centres, schools and colleges with a large turnout were to receive a special memento.</p>

<figure class="ysn-figure">
<img src="{$imgCertificates}" alt="Younger SriSon students receive certificates of merit at the SRM Acharapakkam yogasana championship" loading="lazy" width="1200" height="900">
<figcaption>Younger participants from the SriSon group receive certificates of merit on the wooden stage at the Acharapakkam campus.</figcaption>
</figure>

<h2>Institution Honour for SriSon</h2>

<p>After the meet, the centre received a plaque titled <strong>Institution Honour</strong>, dated 22 November 2026, in the name of <strong>Srison Naturopathy and Yoga Center, Nanganallur, Chennai &ndash; 600 061</strong>. The plaque names the 2nd National Yogasana Championship and the SRM IST Acharapakkam campus as the venue.</p>

<p>The championship notice said yoga centres, schools and colleges with a large number of participants would be given a special memento. The plaque does not list a rank or a participant count, and this report does not add one.</p>

<figure class="ysn-figure">
<img src="{$imgHonour}" alt="Institution Honour plaque awarded to Srison Naturopathy and Yoga Center, Nanganallur, at the 2nd National Yogasana Championship 2026" loading="lazy" width="900" height="1600">
<figcaption>The Institution Honour plaque presented to Srison Naturopathy and Yoga Center, Nanganallur, dated 22 November 2026, at SRM Institute of Science and Technology, Acharapakkam campus.</figcaption>
</figure>

<p>Nanganallur is in south Chennai; the venue is the SRM campus at Acharapakkam in Chengalpattu district. The championship title is the organisers&rsquo; name for the meet. This report follows the centre&rsquo;s note and the photographs; it does not add medal colours or an overall national ranking that those sources do not state.</p>

<section class="ysn-faq" id="faq">
<h2>Frequently asked questions</h2>
{$faqHtml}
</section>

<p class="ysn-note"><strong>Looking for other local programmes?</strong> See upcoming listings on the <a href="{$eventsUrl}">MyOMR events hub</a>, or more neighbourhood stories in <a href="{$newsUrl}">local news</a>.</p>

<p class="ysn-src"><strong>Sources:</strong> Note and photographs from SriSon Naturopathy and Yoga Centre, Nanganallur (22 November 2026); championship notice issued by the Directorate of Sports, SRM Institute of Science and Technology, Easy Yoga Studioz and Padmam Yoga Wellness Centre; Institution Honour plaque dated 22 November 2026. Names of championship winners are as given by the centre.</p>

<div class="ysn-closing">
<p>A Nanganallur yoga class travelled to Acharapakkam, competed, and came home with certificates, a few championship honours, and an institutional plaque. That is the story of the day.</p>
</div>

</div>
HTML;

$values = [
    'title' => $title,
    'slug' => $slug,
    'summary' => $summary,
    'content' => $content,
    'category' => $category,
    'author' => $author,
    'status' => $status,
    'published_date' => $publishedDate,
    'image_path' => $imagePath,
    'tags' => $tags,
    'is_featured' => '0',
];

$validation = myomr_news_article_validate($values, $conn);
if ($validation['errors']) {
    foreach ($validation['errors'] as $error) {
        fwrite(STDERR, 'ERROR: ' . $error . PHP_EOL);
    }
    exit(1);
}
foreach ($validation['warnings'] as $warning) {
    echo 'WARNING: ' . $warning . PHP_EOL;
}

$result = myomr_news_article_save($conn, $values);
$actionLine = strtoupper((string) $result['action']) . ' id=' . (int) $result['id'];

$aeoSummary = 'Students from SriSon Naturopathy and Yoga Centre, Nanganallur, took part in the 2nd National Yogasana Championship at SRM IST Acharapakkam on 22 November 2026. The centre said Adarsh, Elandeeran and Sri Raman won championship honours, and SriSon received an Institution Honour plaque.';
$colRes = $conn->query('SHOW COLUMNS FROM articles');
$hasAeo = false;
if ($colRes instanceof mysqli_result) {
    while ($col = $colRes->fetch_assoc()) {
        if (strtolower((string) ($col['Field'] ?? '')) === 'aeo_summary') {
            $hasAeo = true;
            break;
        }
    }
    $colRes->free();
}
if ($hasAeo) {
    $stmt = $conn->prepare('UPDATE articles SET aeo_summary=? WHERE slug=?');
    if ($stmt) {
        $stmt->bind_param('ss', $aeoSummary, $slug);
        $stmt->execute();
        $stmt->close();
        echo 'aeo_summary updated.' . PHP_EOL;
    }
}

$imgDir = dirname(__DIR__, 2) . '/local-news/omr-news-images';
foreach ([
    'srison-yoga-nanganallur-srm-acharapakkam-yogasana-championship-2026-students.jpg',
    'srison-yoga-nanganallur-srm-acharapakkam-yogasana-championship-2026-trophies.jpg',
    'srison-yoga-nanganallur-srm-acharapakkam-yogasana-championship-2026-certificates.jpg',
    'srison-yoga-nanganallur-srm-acharapakkam-yogasana-championship-2026-institution-honour.jpg',
] as $img) {
    if (!is_file($imgDir . '/' . $img)) {
        echo 'NOTE: Image missing locally: local-news/omr-news-images/' . $img . PHP_EOL;
    }
}

myomr_news_cli_finish_after_db($slug, $title, $actionLine, $status);
echo 'published_date=' . $publishedDate . PHP_EOL;
echo 'image_path=' . $imagePath . PHP_EOL;
