<?php
/**
 * Publish: Perungudi govt school students sing Tamil birthday song at morning assembly (Jun 2026).
 *
 * Design section pointers (implemented in .psb-feature wrapper):
 *  1. Hero — green gradient, dateline Perungudi + education eyebrow
 *  2. Lead + geo pills (Perungudi, School Street, OMR, Tamil education)
 *  3. Hero figure — assembly still with school sign caption (GEO keywords)
 *  4. Video block — responsive 16:9 YouTube embed (buSFsWtlLiw)
 *  5. Pull-quote — Tamil line "நீ நீடு வாழ வேண்டும்" in callout card
 *  6. About the song — credits grid (Arivumathi, Arol Corelli, Uthara Unnikrishnan)
 *  7. Lyrics excerpt — limited Tamil lines + editorial rights note
 *  8. Why it matters — 3-card grid (language pride, assembly culture, govt schools)
 *  9. FAQ block — AEO-friendly Q&A
 * 10. Closing — OMR community soft-news tone
 *
 * Usage (PowerShell): $env:DB_HOST='myomr.in'; php dev-tools/sql/run-perungudi-govt-school-tamil-birthday-song-article-june-2026.php
 */
declare(strict_types=1);

require_once __DIR__ . '/../../core/omr-connect.php';

$slug = 'perungudi-govt-school-tamil-birthday-song-morning-assembly';

$title = 'Perungudi Govt School Students Sing Tamil Birthday Song During Morning Assembly';

$summary = 'Students at a government primary school in Perungudi, Chennai, sang poet Arivumathi\'s Tamil birthday wishes song during morning assembly — a warm moment of language pride and school bonding along OMR.';

$imagePath = '/local-news/omr-news-images/perungudi-govt-primary-school-tamil-birthday-song-assembly-june-2026.png';

$category = 'Local News / Education / Community';
$author = 'MyOMR Editorial Team';
$status = 'published';
$publishedDate = '2026-06-25 14:00:00';
$isFeatured = 0;

$tags = 'Perungudi, government school Chennai, Tamil birthday song, Arivumathi, morning assembly, OMR schools, Tamil education, School Street Perungudi, Chennai 600096, Uthara Unnikrishnan, Tamil Nadu government schools, local news OMR, community bonding, language pride';

$schoolsUrl = 'https://myomr.in/omr-listings/schools.php';
$youtubeUrl = 'https://youtu.be/buSFsWtlLiw';
$youtubeEmbed = 'https://www.youtube.com/embed/buSFsWtlLiw';

$content = <<<HTML
<div class="psb-feature">
<style>
.psb-feature{--psb-ink:#1c1917;--psb-muted:#57534e;--psb-brand:#14532d;--psb-brand-mid:#166534;--psb-accent:#b91c1c;--psb-soft:#f0fdf4;--psb-line:#d1fae5;--psb-gold:#ca8a04;--psb-shadow:0 12px 32px rgba(20,83,45,.1);font-family:"Poppins","Segoe UI",system-ui,sans-serif;color:var(--psb-ink);font-size:17px;line-height:1.75;max-width:100%}
.psb-feature *{box-sizing:border-box}
.psb-feature h2,.psb-feature h3{font-family:"Poppins",system-ui,sans-serif!important;border:none!important;padding:0!important;margin:1.45rem 0 .6rem!important;color:var(--psb-brand)!important;font-weight:700!important;line-height:1.3!important}
.psb-feature h2{font-size:1.32rem!important;padding-bottom:.4rem!important;border-bottom:2px solid var(--psb-soft)!important}
.psb-feature h3{font-size:1.08rem!important}
.psb-hero{background:linear-gradient(135deg,#14532d 0%,#166534 50%,#15803d 100%);color:#fff;padding:1.55rem 1.35rem;border-radius:14px;margin:0 0 1.35rem;box-shadow:var(--psb-shadow)}
.psb-hero .eyebrow{font:700 .72rem/1.2 "Poppins",sans-serif;letter-spacing:.12em;text-transform:uppercase;opacity:.92;margin:0 0 .45rem}
.psb-hero .sub{font-size:1.06rem;line-height:1.55;margin:0;color:#dcfce7;font-weight:500}
.psb-lead{font-size:1.1rem;font-weight:600;color:#1a2332;margin:0 0 1rem;line-height:1.62}
.psb-dateline{font-size:.92rem;color:var(--psb-muted);margin:0 0 1rem}
.psb-pills{display:flex;flex-wrap:wrap;gap:8px;margin:1rem 0 1.4rem}
.psb-pill{padding:.32rem .72rem;background:#fff;color:var(--psb-brand-mid);border:1px solid var(--psb-line);border-radius:999px;font-size:.76rem;font-weight:600}
.psb-figure{margin:1.4rem 0;border-radius:14px;overflow:hidden;border:1px solid #e7e5e4;background:#fff;box-shadow:0 6px 20px rgba(28,25,23,.06)}
.psb-figure img{display:block;width:100%;height:auto;margin:0}
.psb-figure figcaption{padding:.8rem 1rem;font-size:.88rem;color:var(--psb-muted);line-height:1.5;background:#fafaf9;border-top:1px solid #e7e5e4}
.psb-video{margin:1.4rem 0;border-radius:14px;overflow:hidden;background:#0f172a;box-shadow:var(--psb-shadow)}
.psb-video-frame{position:relative;width:100%;padding-bottom:56.25%;height:0;overflow:hidden}
.psb-video-frame iframe{position:absolute;top:0;left:0;width:100%;height:100%;border:0}
.psb-video-cap{font-size:.86rem;color:#64748b;margin:0;padding:.75rem 1rem;text-align:center;background:#f8fafc}
.psb-video-cap a{color:var(--psb-brand-mid);font-weight:600;text-decoration:none}
.psb-quote{margin:1.35rem 0;padding:1.15rem 1.25rem;border-radius:12px;background:linear-gradient(135deg,#fef2f2,#fff7ed);border-left:5px solid var(--psb-accent);font-size:1.05rem;line-height:1.65}
.psb-quote .ta{font-size:1.2rem;font-weight:700;color:#7f1d1d;margin:0 0 .35rem}
.psb-quote .en{font-size:.92rem;color:var(--psb-muted);margin:0}
.psb-credits{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin:1.1rem 0 1.4rem}
.psb-credit{background:var(--psb-soft);border:1px solid var(--psb-line);border-radius:12px;padding:.95rem 1rem}
.psb-credit dt{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--psb-brand);margin:0 0 .25rem}
.psb-credit dd{margin:0;font-size:.95rem;font-weight:600;color:#1c1917}
.psb-lyrics{margin:1.2rem 0;padding:1.1rem 1.2rem;border-radius:12px;background:#fffbeb;border:1px solid #fde68a;font-size:1rem;line-height:1.8}
.psb-lyrics .ta-line{color:#78350f;font-weight:500}
.psb-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:14px;margin:1rem 0 1.4rem}
.psb-card{background:#fff;border:1px solid #e7e5e4;border-radius:12px;padding:1rem 1.05rem;box-shadow:0 4px 14px rgba(28,25,23,.04)}
.psb-card h3{margin-top:0!important;font-size:.98rem!important}
.psb-card p{margin:.35rem 0 0;font-size:.9rem;color:var(--psb-muted);line-height:1.55}
.psb-faq{margin:1.4rem 0;padding:1.1rem 1.2rem;border-radius:12px;background:#f8fafc;border:1px solid #e2e8f0}
.psb-faq p{margin:.85rem 0 0;font-size:.95rem;line-height:1.6}
.psb-faq p:first-child{margin-top:0}
.psb-faq strong{color:var(--psb-brand)}
.psb-geo{margin:1.2rem 0;padding:1rem 1.15rem;border-radius:12px;background:var(--psb-soft);border:1px solid var(--psb-line);font-size:.92rem;color:#334155;line-height:1.6}
.psb-note{margin:1.5rem 0 0;padding:1rem 1.1rem;border-radius:10px;background:#f8fafc;border-left:4px solid #64748b;font-size:.88rem;color:#475569;line-height:1.6}
.psb-closing{margin:1.4rem 0 .5rem;padding:1.05rem 1.2rem;background:linear-gradient(135deg,#14532d,#166534);color:#fff;border-radius:14px;font-size:1rem;line-height:1.6}
.psb-closing p{margin:.2rem 0;color:#dcfce7}
@media (max-width:600px){.psb-hero{padding:1.2rem 1rem}.psb-quote .ta{font-size:1.08rem}}
</style>

<div class="psb-hero">
<p class="eyebrow">Perungudi, Chennai &middot; Local News &middot; Education</p>
<p class="sub"><strong>Government Primary School, School Street, Perungudi</strong> &mdash; students gathered during morning prayer and sang a Tamil birthday wishes song for a classmate, in a moment shared by the MyOMR community.</p>
</div>

<p class="psb-lead"><strong>Perungudi, Chennai:</strong> A simple and heartwarming moment from a government primary school in Perungudi has drawn local attention after a video was shared by the MyOMR community on Instagram.</p>

<p class="psb-dateline"><strong>25 June 2026</strong> &mdash; By <em>MyOMR Editorial Team</em></p>

<div class="psb-pills">
<span class="psb-pill">Perungudi</span>
<span class="psb-pill">Government School</span>
<span class="psb-pill">Tamil Birthday Song</span>
<span class="psb-pill">Morning Assembly</span>
<span class="psb-pill">OMR Corridor</span>
<span class="psb-pill">Arivumathi</span>
</div>

<figure class="psb-figure">
<img src="/local-news/omr-news-images/perungudi-govt-primary-school-tamil-birthday-song-assembly-june-2026.png" alt="Students at Government Primary School No. 1, School Street, Perungudi, Chennai, during morning assembly singing a Tamil birthday song" loading="lazy" width="1200" height="675">
<figcaption>Morning assembly at <strong>Government Primary School No. 1, School Street, Perungudi, Chennai &ndash; 96</strong> (Division 184, Unit 41, Zone 14). Students in green-and-white uniforms gather as classmates sing birthday wishes in Tamil.</figcaption>
</figure>

<p>The video shows students gathered during the morning prayer session, singing a Tamil birthday wishes song for one of their schoolmates. The scene, though simple, reflects the warmth of school life and the emotional value of shared celebrations among children.</p>

<h2>Watch the video</h2>
<div class="psb-video">
<div class="psb-video-frame">
<iframe src="{$youtubeEmbed}" title="Perungudi government school students sing Tamil birthday song during morning assembly" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
</div>
<p class="psb-video-cap"><a href="{$youtubeUrl}" rel="noopener noreferrer" target="_blank">Watch on YouTube</a> &middot; video shared via MyOMR community</p>
</div>

<h2>About the Tamil birthday song</h2>
<p>The song heard in the video is the popular Tamil birthday wishes song written by poet <strong>Arivumathi</strong>. It is known for replacing the usual English birthday greeting with meaningful Tamil lines that bless a child with long life, knowledge, kindness, humility, good character and growth.</p>

<div class="psb-quote">
<p class="ta">&#8220;&#2984;&#3008; &#2984;&#3008;&#2975;&#3009; &#2997;&#3006;&#2996; &#2997;&#3015;&#2979;&#3021;&#2975;&#3009;&#2990;&#8221;</p>
<p class="en">One of the well-known lines from the song &mdash; wishing the child a long and full life.</p>
</div>

<p>The beauty of the song lies in its message. It does not merely wish a child a happy birthday. It wishes the child to grow with love, wisdom, good values, compassion and public respect. This makes the song especially suitable for schools, family gatherings and community celebrations.</p>

<p>The Tamil birthday song became popular because of its clear cultural purpose. Poet Arivumathi&rsquo;s lyrics gave Tamil-speaking families and institutions a native alternative to the standard birthday song. The music by <strong>Arol Corelli</strong> and the voice of <strong>Uthara Unnikrishnan</strong> helped give the song a soft, memorable and emotionally rich identity.</p>

<dl class="psb-credits">
<div class="psb-credit"><dt>Lyrics</dt><dd>Poet Arivumathi</dd></div>
<div class="psb-credit"><dt>Music</dt><dd>Arol Corelli</dd></div>
<div class="psb-credit"><dt>Voice</dt><dd>Uthara Unnikrishnan</dd></div>
</dl>

<h2>Why this Perungudi school moment matters</h2>
<p>In the Perungudi school video, the song takes on an even deeper meaning. When children sing it together during morning assembly, the moment becomes more than a birthday greeting. It becomes a shared act of affection, language pride and school bonding.</p>

<div class="psb-grid">
<div class="psb-card">
<h3>Language pride at school</h3>
<p>When classmates wish a child in Tamil during assembly, the celebration feels personal, rooted and memorable &mdash; not borrowed from another language tradition.</p>
</div>
<div class="psb-card">
<h3>Morning assembly culture</h3>
<p>Assemblies help children build confidence, listen, sing, observe and feel included. A birthday song turns routine prayer time into shared joy.</p>
</div>
<div class="psb-card">
<h3>Government schools as community spaces</h3>
<p>Beyond classrooms and exams, govt schools shape children through friendship, values and collective experiences that residents along OMR often overlook.</p>
</div>
</div>

<p>Morning assemblies in schools often play an important role beyond daily discipline. They help children build confidence, participate in group activities, listen, sing, observe and feel included. When a child is wished by classmates in their own language, the celebration becomes personal and memorable.</p>

<p>The Perungudi school moment also reminds us of the quiet strength of government schools. Beyond classrooms, textbooks and exams, schools continue to shape children through values, friendship, language and collective experiences.</p>

<p>For many students, such small moments become lifelong memories. A birthday song sung by classmates during assembly may look ordinary from outside, but for the child being celebrated, it can remain special for years.</p>

<p>In a fast-growing urban corridor like <strong>OMR</strong>, where daily life is often centred around traffic, apartments, offices and infrastructure, this video brings attention back to the softer side of the neighbourhood &mdash; children, schools, teachers and community bonding.</p>

<p>The scene is also a reminder that education is not limited to marksheets. It lives in songs, greetings, kindness, participation and the confidence children gain when they are seen and celebrated.</p>

<p>Such local school moments deserve visibility because they show the human side of our neighbourhood institutions. Government schools are not just education centres. They are community spaces where children learn to belong, grow together and celebrate each other.</p>

<p>Explore schools along the corridor in the <a href="{$schoolsUrl}">OMR schools directory</a>.</p>

<h2 id="lyrics-excerpt">Song excerpt (Tamil)</h2>
<div class="psb-lyrics">
<p class="ta-line">&#2984;&#3008;&#2975;&#2984;&#3008;&#2975;&#3021; &#2984;&#3008;&#2975;&#3021; &#2965;&#3006;&#2994;&#2990;&#3021; &#2984;&#3008; &#2984;&#3008;&#2975;&#3009; &#2997;&#3006;&#2996; &#2997;&#3015;&#2979;&#3021;&#2975;&#3009;&#2990;&#8230;</p>
<p class="ta-line">&#2997;&#3006;&#2985;&#2990;&#3021; &#2980;&#3008;&#2979;&#3021;&#2975;&#3009;&#2990;&#3021; &#2980;&#3010;&#2992;&#2990;&#3021; &#2984;&#3008; &#2997;&#2995;&#2992;&#3021;&#2985;&#3021;&#2980;&#3009; &#2997;&#3006;&#2996; &#2997;&#3015;&#2979;&#3021;&#2975;&#3009;&#2990;&#8230;</p>
<p class="ta-line">&#2949;&#2985;&#3021;&#2986;&#3009; &#2997;&#3015;&#2979;&#3021;&#2975;&#3009;&#2990;&#3021; &#2949;&#2993;&#3007;&#2997;&#3009; &#2997;&#3015;&#2979;&#3021;&#2975;&#3009;&#2990;&#3021; &#2986;&#2979;&#3021;&#2986;&#3009; &#2997;&#3015;&#2979;&#3021;&#2975;&#3009;&#2990;&#3021; &#2986;&#2996;&#2965; &#2997;&#3015;&#2979;&#3021;&#2975;&#3009;&#2990;&#8230;</p>
<p class="ta-line">&#2958;&#2975;&#3021;&#2975;&#3009;&#2980;&#3021;&#2980;&#3007;&#2965;&#3021;&#2965;&#3009;&#2990;&#3021;&#2986; &#2986;&#3009;&#2965;&#2996; &#2997;&#3015;&#2979;&#3021;&#2975;&#3009;&#2990;&#8230;</p>
</div>

<p class="psb-note"><strong>Editorial note:</strong> Only a short excerpt from the song has been included here. Full lyrics should be published only with proper permission from the rights holder. <strong>Source:</strong> Instagram post shared by MyOMR Community.</p>

<div class="psb-geo">
<strong>Location (GEO):</strong> Government Primary School No. 1, School Street, Perungudi, Chennai &ndash; 600096, Tamil Nadu, India. Perungudi lies along Old Mahabalipuram Road (OMR) between Thoraipakkam and Velachery, near the Perungudi MRTS station and the IT corridor.
</div>

<h2 id="faq">Frequently asked questions</h2>
<div class="psb-faq">
<p><strong>Which school is shown in the Perungudi birthday song video?</strong><br>
The video shows students at a government primary school in Perungudi, Chennai. The school sign visible in the footage reads Government Primary School No. 1, School Street, Perungudi, Chennai &ndash; 96.</p>

<p><strong>What Tamil birthday song are the students singing?</strong><br>
The students sing the popular Tamil birthday wishes song with lyrics by poet Arivumathi, music by Arol Corelli and vocals by Uthara Unnikrishnan. It is widely used in Tamil homes and schools as a culturally rooted alternative to the English &ldquo;Happy Birthday&rdquo; song.</p>

<p><strong>Who wrote the Tamil birthday wishes song?</strong><br>
The lyrics were written by Tamil poet Arivumathi. The music was composed by Arol Corelli and the well-known recording features singer Uthara Unnikrishnan.</p>

<p><strong>Why is a morning assembly birthday song significant?</strong><br>
Morning assemblies build routine, discipline and group participation. When classmates sing birthday wishes in Tamil during assembly, the child being celebrated feels seen by the whole school. It also reinforces language pride and community bonding in government schools along corridors like OMR.</p>

<p><strong>Where is Perungudi located along OMR?</strong><br>
Perungudi is a neighbourhood on Old Mahabalipuram Road (OMR) in south Chennai, between Thoraipakkam and Velachery. It is home to government schools, IT parks, residential apartments and the Perungudi MRTS station.</p>
</div>

<div class="psb-closing">
<p><strong>For OMR residents:</strong> Behind the traffic, towers and deadlines, neighbourhoods like Perungudi still grow through children, teachers and small shared moments. This assembly video is a gentle reminder of that human side.</p>
</div>

</div>
HTML;

$check = $conn->prepare('SELECT id, status FROM articles WHERE slug = ? LIMIT 1');
if (!$check) {
    fwrite(STDERR, 'Prepare failed: ' . $conn->error . PHP_EOL);
    exit(1);
}
$check->bind_param('s', $slug);
$check->execute();
$existing = $check->get_result()->fetch_assoc();
$check->close();

if ($existing) {
    $stmt = $conn->prepare(
        'UPDATE articles SET title=?, summary=?, content=?, category=?, author=?, status=?, published_date=?, image_path=?, tags=?, is_featured=?, updated_at=NOW() WHERE slug=?'
    );
    if (!$stmt) {
        fwrite(STDERR, 'Prepare UPDATE failed: ' . $conn->error . PHP_EOL);
        exit(1);
    }
    $stmt->bind_param(
        'ssssssssiss',
        $title,
        $summary,
        $content,
        $category,
        $author,
        $status,
        $publishedDate,
        $imagePath,
        $tags,
        $isFeatured,
        $slug
    );
    $ok = $stmt->execute();
    $action = 'UPDATED id=' . ($existing['id'] ?? '?');
    $stmt->close();
} else {
    $stmt = $conn->prepare(
        'INSERT INTO articles (title, slug, summary, content, category, author, status, published_date, image_path, tags, is_featured, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())'
    );
    if (!$stmt) {
        fwrite(STDERR, 'Prepare INSERT failed: ' . $conn->error . PHP_EOL);
        exit(1);
    }
    $stmt->bind_param(
        'ssssssssssi',
        $title,
        $slug,
        $summary,
        $content,
        $category,
        $author,
        $status,
        $publishedDate,
        $imagePath,
        $tags,
        $isFeatured
    );
    $ok = $stmt->execute();
    $action = 'INSERTED id=' . $conn->insert_id;
    $stmt->close();
}

if (!$ok) {
    fwrite(STDERR, 'Execute failed: ' . $conn->error . PHP_EOL);
    exit(1);
}

echo $action . PHP_EOL;
echo 'Article: https://myomr.in/local-news/' . $slug . PHP_EOL;
echo 'Image: ' . $imagePath . PHP_EOL;
