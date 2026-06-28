<?php
/**
 * Publish: LB Road traffic diversions for Chennai Metro underground work (27 June 2026).
 * Usage (PowerShell): $env:DB_HOST='myomr.in'; php dev-tools/sql/run-lb-road-traffic-diversion-chennai-metro-june-2026.php
 */
declare(strict_types=1);

require_once __DIR__ . '/../../core/omr-connect.php';

$slug = 'lb-road-traffic-diversion-chennai-metro-thiruvanmiyur-june-2026';

$title = 'LB Road Closed from June 27: Chennai Metro Work Diversions Near Thiruvanmiyur';

$summary = 'LB Road is closed between Indian Oil petrol bunk and M.G. Road junction from 27 June for Chennai Metro underground work. Traffic police announce Sastri Nagar and Indira Nagar diversions — route guide for OMR commuters.';

$imagePath = 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d6/Chennai_Metro_Rail_work_in_progress_near_the_Tamil_Nadu_legislative_assembly-secretariat_complex.jpg/1280px-Chennai_Metro_Rail_work_in_progress_near_the_Tamil_Nadu_legislative_assembly-secretariat_complex.jpg';

$category = 'Local News';
$author = 'MyOMR Editorial Team';
$status = 'published';
$publishedDate = '2026-06-27 09:00:00';
$isFeatured = 1;

$tags = 'LB Road, Lattice Bridge Road, Thiruvanmiyur, Adyar, Besant Nagar, Chennai Metro, CMRL, traffic diversion, road closure, Sastri Nagar, Thiru Vi Ka Bridge, MG Road, OMR commute, Greater Chennai Traffic Police, metro construction, Indira Nagar';

$content = <<<'HTML'
<div class="lb-feature">
<style>
.lb-feature{--lb-ink:#0c1222;--lb-muted:#5b6478;--lb-brand:#7c2d12;--lb-brand-mid:#c2410c;--lb-accent:#ea580c;--lb-amber:#b45309;--lb-amber-soft:#fef3c7;--lb-blue:#1e40af;--lb-blue-soft:#eff6ff;--lb-purple:#6b21a8;--lb-purple-soft:#faf5ff;--lb-green:#15803d;--lb-green-soft:#dcfce7;--lb-surface:#ffffff;--lb-soft:#fff7ed;--lb-line:rgba(124,45,18,.14);--lb-shadow:0 18px 50px rgba(12,18,34,.08);font-family:"Poppins",system-ui,sans-serif;color:var(--lb-ink);font-size:1.05rem;line-height:1.72;max-width:100%}
.lb-feature *{box-sizing:border-box}
.lb-feature h2.lb-h2,.lb-feature h3.lb-h3{font-family:"Poppins",system-ui,sans-serif!important;border:none!important;padding:0!important;margin:1.5rem 0 .65rem!important;color:var(--lb-brand)!important;font-weight:700!important;line-height:1.3!important}
.lb-feature h2.lb-h2{font-size:1.32rem!important;padding-bottom:.45rem!important;border-bottom:2px solid var(--lb-soft)!important}
.lb-feature h3.lb-h3{font-size:1.05rem!important}
.lb-hero{position:relative;overflow:hidden;background:radial-gradient(120% 140% at 0% 0%,#c2410c 0%,#7c2d12 50%,#431407 100%);color:#fff;padding:1.6rem 1.5rem;border-radius:18px;margin:0 0 1.35rem;box-shadow:var(--lb-shadow)}
.lb-hero::after{content:"";position:absolute;right:-40px;top:-40px;width:180px;height:180px;border-radius:50%;background:rgba(251,191,36,.18);pointer-events:none}
.lb-hero .eyebrow{font-size:.72rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;opacity:.88;margin:0 0 .5rem}
.lb-hero .sub{font-size:1.05rem;line-height:1.58;margin:0;color:#ffedd5;font-weight:500;max-width:58ch}
.lb-alert{display:inline-flex;align-items:center;gap:8px;background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;padding:.42rem .9rem;border-radius:999px;font-size:.82rem;font-weight:700;letter-spacing:.04em;margin:0 0 1rem}
.lb-alert::before{content:"";width:8px;height:8px;border-radius:50%;background:#dc2626;box-shadow:0 0 0 3px rgba(220,38,38,.2)}
.lb-lead{font-size:1.1rem;font-weight:600;color:#1a2332;margin:0 0 1rem;line-height:1.62}
.lb-dateline{font-size:.92rem;color:var(--lb-muted);margin:0 0 1.1rem}
.lb-pills{display:flex;flex-wrap:wrap;gap:8px;margin:1rem 0 1.5rem}
.lb-pill{padding:.32rem .7rem;background:#fff;color:var(--lb-brand-mid);border:1px solid var(--lb-line);border-radius:999px;font-size:.76rem;font-weight:600}
.lb-map-wrap{margin:1.5rem 0;padding:1rem;background:#fff;border:1px solid var(--lb-line);border-radius:18px;box-shadow:var(--lb-shadow)}
.lb-osm-embed{border-radius:12px;overflow:hidden;border:1px solid var(--lb-line);aspect-ratio:16/9;max-height:420px}
.lb-osm-embed iframe{width:100%;height:100%;border:0;min-height:280px;display:block}
.lb-map-cap{font-size:.86rem;color:var(--lb-muted);margin:.75rem 0 0;text-align:center;line-height:1.5}
.lb-photo{margin:1.25rem 0;border-radius:16px;overflow:hidden;border:1px solid var(--lb-line);box-shadow:var(--lb-shadow);background:#fff}
.lb-photo img{display:block;width:100%;height:auto}
.lb-photo figcaption{font-size:.84rem;color:var(--lb-muted);padding:.65rem .9rem;line-height:1.45}
.lb-map-cap a{color:var(--lb-brand-mid);font-weight:600;text-decoration:none}
.lb-facts{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin:1.25rem 0 1.5rem}
.lb-fact{background:var(--lb-soft);border:1px solid var(--lb-line);border-radius:14px;padding:1rem 1.05rem}
.lb-fact .label{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--lb-brand-mid);margin:0 0 .35rem}
.lb-fact .value{font-size:.95rem;font-weight:600;color:var(--lb-ink);margin:0;line-height:1.45}
.lb-route{margin:1.25rem 0;padding:1.15rem 1.25rem;border-radius:16px;border:1px solid var(--lb-line);box-shadow:var(--lb-shadow)}
.lb-route.heavy{background:linear-gradient(135deg,var(--lb-purple-soft),#fff);border-left:5px solid var(--lb-purple)}
.lb-route.lmv{background:linear-gradient(135deg,var(--lb-green-soft),#fff);border-left:5px solid var(--lb-green)}
.lb-route.all{background:linear-gradient(135deg,var(--lb-blue-soft),#fff);border-left:5px solid var(--lb-blue)}
.lb-route .lb-h3{margin-top:0!important}
.lb-route ol{margin:.5rem 0 0;padding-left:1.25rem;font-size:.95rem;color:var(--lb-ink)}
.lb-route ol li{margin:.35rem 0;line-height:1.55}
.lb-route .note{font-size:.88rem;color:var(--lb-muted);margin:.65rem 0 0;padding:.55rem .75rem;background:rgba(255,255,255,.7);border-radius:10px;border:1px dashed var(--lb-line)}
.lb-rules{background:linear-gradient(135deg,#fffbeb,#fefce8);border:1px solid #fde68a;border-left:5px solid var(--lb-amber);border-radius:14px;padding:1.1rem 1.2rem;margin:1.4rem 0;font-size:.97rem;color:#78350f}
.lb-rules strong{display:block;color:#78350f;margin-bottom:.35rem;font-size:.95rem}
.lb-rules ul{margin:.4rem 0 0;padding-left:1.2rem}
.lb-rules li{margin:.3rem 0;line-height:1.55}
.lb-map-links{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:10px;margin:1rem 0 1.5rem}
.lb-map-link{display:flex;align-items:center;gap:10px;padding:.75rem .9rem;background:#fff;border:1px solid var(--lb-line);border-radius:12px;text-decoration:none;color:var(--lb-ink);font-size:.9rem;font-weight:500;transition:box-shadow .15s}
.lb-map-link:hover{box-shadow:0 4px 16px rgba(124,45,18,.12);color:var(--lb-brand-mid)}
.lb-map-link .pin{flex-shrink:0;width:32px;height:32px;border-radius:50%;background:var(--lb-soft);display:flex;align-items:center;justify-content:center;font-size:1rem}
.lb-tips{background:#fff;border:1px solid var(--lb-line);border-radius:16px;padding:1.15rem 1.3rem;margin:1.25rem 0;box-shadow:var(--lb-shadow)}
.lb-tips ul{margin:.5rem 0 0;padding:0;list-style:none}
.lb-tips li{position:relative;padding:.5rem 0 .5rem 1.8rem;border-bottom:1px dashed rgba(124,45,18,.12);font-size:.95rem}
.lb-tips li:last-child{border-bottom:0}
.lb-tips li::before{content:"";position:absolute;left:0;top:.85rem;width:10px;height:10px;border-radius:50%;background:var(--lb-brand-mid);box-shadow:0 0 0 3px rgba(194,65,12,.18)}
.lb-view{margin:1.75rem 0;padding:1.3rem 1.35rem;background:linear-gradient(135deg,#fff7ed 0%,#ffedd5 50%,#f8fafc 100%);border:1px solid rgba(124,45,18,.2);border-radius:16px;box-shadow:inset 0 1px 0 rgba(255,255,255,.8)}
.lb-view .lb-h2{color:var(--lb-brand-mid)!important;font-size:1.2rem!important;border:none!important;margin-top:0!important}
.lb-sources{font-size:.88rem;color:var(--lb-muted);margin:1.5rem 0 0;padding-top:1rem;border-top:1px solid var(--lb-line)}
.lb-sources a{color:var(--lb-brand-mid);font-weight:600;text-decoration:none}
@media (max-width:640px){.lb-hero{padding:1.25rem 1.1rem}}
</style>

<div class="lb-hero">
<p class="eyebrow">Traffic alert &middot; Chennai Metro &middot; Thiruvanmiyur corridor</p>
<p class="sub">Greater Chennai Traffic Police have closed a stretch of LB Road for underground metro work and announced diversions through Sastri Nagar and Indira Nagar until further notice.</p>
</div>

<span class="lb-alert">Road closure from 27 June 2026 &middot; Until further notice</span>

<p class="lb-lead">If you commute between Thiruvanmiyur, Adyar, Besant Nagar or the northern end of OMR, plan for delays from Saturday, 27 June. Chennai Metro Rail Limited (CMRL) is carrying out underground construction on LB Road (Lattice Bridge Road), and the Greater Chennai City Traffic Police have announced that the stretch between the Indian Oil petrol bunk junction and the LB Road&ndash;M.G. Road junction will be <strong>completely closed</strong> to traffic from 27 June 2026.</p>

<p class="lb-dateline"><strong>Chennai &middot; 27 June 2026</strong> &mdash; By <em>MyOMR Editorial Team</em></p>

<div class="lb-pills">
<span class="lb-pill">LB Road</span>
<span class="lb-pill">Thiruvanmiyur</span>
<span class="lb-pill">Chennai Metro</span>
<span class="lb-pill">Traffic Diversion</span>
<span class="lb-pill">Sastri Nagar</span>
<span class="lb-pill">OMR Commute</span>
</div>

<figure class="lb-photo">
<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d6/Chennai_Metro_Rail_work_in_progress_near_the_Tamil_Nadu_legislative_assembly-secretariat_complex.jpg/1280px-Chennai_Metro_Rail_work_in_progress_near_the_Tamil_Nadu_legislative_assembly-secretariat_complex.jpg" alt="Chennai Metro Rail underground construction work causing road closures and traffic diversions" loading="lazy" decoding="async" width="1280" height="960">
<figcaption>Chennai Metro underground construction has led to lane and road closures across the city, including the LB Road stretch near Thiruvanmiyur. Photo: <a href="https://commons.wikimedia.org/wiki/File:Chennai_Metro_Rail_work_in_progress_near_the_Tamil_Nadu_legislative_assembly-secretariat_complex.jpg" target="_blank" rel="noopener noreferrer">Wikimedia Commons</a> / Aravind Sivaraj (CC BY-SA 3.0) — representative image.</figcaption>
</figure>

<h2 class="lb-h2">What is closed</h2>

<div class="lb-facts">
<div class="lb-fact"><p class="label">Closed stretch</p><p class="value">Indian Oil petrol bunk junction &rarr; LB Road&ndash;M.G. Road junction (completely closed)</p></div>
<div class="lb-fact"><p class="label">Reason</p><p class="value">CMRL underground metro construction</p></div>
<div class="lb-fact"><p class="label">Effective from</p><p class="value">27 June 2026</p></div>
<div class="lb-fact"><p class="label">Duration</p><p class="value">Until further notice</p></div>
</div>

<div class="lb-map-wrap">
<div class="lb-osm-embed">
<iframe title="OpenStreetMap — LB Road closure zone, Thiruvanmiyur" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.openstreetmap.org/export/embed.html?bbox=80.2515%2C13.0005%2C80.2625%2C13.0095&amp;layer=mapnik&amp;marker=13.0049%2C80.2571"></iframe>
</div>
<p class="lb-map-cap">Free interactive map via <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">OpenStreetMap</a>. Marker shows the closed LB Road stretch between Indian Oil petrol bunk and M.G. Road junction. <a href="https://www.openstreetmap.org/?mlat=13.0049&amp;mlon=80.2571#map=16/13.0049/80.2571" target="_blank" rel="noopener noreferrer">Open full map &rarr;</a></p>
</div>

<figure class="lb-photo">
<img src="/local-news/omr-news-images/lb-road-metro-traffic-diversion-june-2026.png" alt="LB Road traffic diversion route map for Chennai Metro construction — buses, heavy vehicles, light motor vehicles and Thiruvanmiyur-bound routes, June 2026" loading="lazy" decoding="async" width="1200" height="1600">
<figcaption>MyOMR route guide based on the Greater Chennai Traffic Police diversion notice, cross-checked with <a href="https://www.thehindu.com/news/cities/chennai/traffic-diversions-due-to-chennai-metro-rail-construction-work-on-lb-road/article71149801.ece" target="_blank" rel="noopener noreferrer">The Hindu</a> (26 June 2026). Police notices spell the locality as &ldquo;Shastri Nagar&rdquo;; residents often write &ldquo;Sastri Nagar&rdquo; &mdash; both refer to the same area.</figcaption>
</figure>

<h2 class="lb-h2">Key landmarks on the map</h2>

<div class="lb-map-links">
<a class="lb-map-link" href="https://www.google.com/maps/search/?api=1&query=Indian+Oil+Petrol+Bunk+LB+Road+Adyar+Chennai" target="_blank" rel="noopener noreferrer"><span class="pin" aria-hidden="true">&#128205;</span><span>Indian Oil petrol bunk signal (closure south end)</span></a>
<a class="lb-map-link" href="https://www.google.com/maps/search/?api=1&query=LB+Road+MG+Road+Junction+Adyar+Chennai" target="_blank" rel="noopener noreferrer"><span class="pin" aria-hidden="true">&#128205;</span><span>LB Road&ndash;M.G. Road junction (closure north end)</span></a>
<a class="lb-map-link" href="https://www.google.com/maps/search/?api=1&query=Thiru+Vi+Ka+Bridge+Chennai" target="_blank" rel="noopener noreferrer"><span class="pin" aria-hidden="true">&#128205;</span><span>Thiru.Vi.Ka Bridge (Adyar side)</span></a>
<a class="lb-map-link" href="https://www.google.com/maps/search/?api=1&query=Thiruvanmiyur+Chennai" target="_blank" rel="noopener noreferrer"><span class="pin" aria-hidden="true">&#128205;</span><span>Thiruvanmiyur (OMR corridor side)</span></a>
<a class="lb-map-link" href="https://www.google.com/maps/search/?api=1&query=Sastri+Nagar+1st+Main+Road+Adyar+Chennai" target="_blank" rel="noopener noreferrer"><span class="pin" aria-hidden="true">&#128205;</span><span>Sastri Nagar 1st Main Road</span></a>
<a class="lb-map-link" href="https://www.google.com/maps/search/?api=1&query=Indira+Nagar+2nd+Avenue+Adyar+Chennai" target="_blank" rel="noopener noreferrer"><span class="pin" aria-hidden="true">&#128205;</span><span>Indira Nagar 2nd Avenue (LMV only)</span></a>
</div>

<h2 class="lb-h2">Diversions toward Thiru.Vi.Ka Bridge</h2>
<p>Vehicles heading from the Thiruvanmiyur side toward Thiru.Vi.Ka Bridge and Adyar must follow different routes depending on vehicle type.</p>

<div class="lb-route heavy">
<h3 class="lb-h3">Buses and heavy vehicles</h3>
<p>Diverted at the <strong>LB Road&ndash;M.G. Road junction</strong>:</p>
<ol>
<li>M.G. Road</li>
<li>Left turn onto <strong>Sastri Nagar 1st Main Road</strong></li>
<li>Left turn onto <strong>Sastri Nagar 1st Avenue</strong></li>
<li>Rejoin <strong>LB Road</strong> beyond the closure</li>
</ol>
<p class="note">Vehicles from Besant Nagar are diverted via <strong>7th Avenue M.G. Road</strong>, then right onto Sastri Nagar 1st Main Road, left onto Sastri Nagar 1st Avenue, and rejoin LB Road toward their destination (<a href="https://www.thehindu.com/news/cities/chennai/traffic-diversions-due-to-chennai-metro-rail-construction-work-on-lb-road/article71149801.ece" target="_blank" rel="noopener noreferrer">The Hindu</a>).</p>
</div>

<div class="lb-route lmv">
<h3 class="lb-h3">Light motor vehicles and other vehicles</h3>
<p>Per the traffic police notice, light motor vehicles and all other non-heavy vehicles from Thiruvanmiyur toward Thiru.Vi.Ka Bridge are diverted at the <strong>LB Road&ndash;M.G. Road junction</strong> via:</p>
<ol>
<li><strong>Indira Nagar 2nd Avenue</strong></li>
<li>Right turn onto <strong>1st Main Road</strong></li>
<li>Right and left turns onto <strong>1st Avenue</strong></li>
<li>Right turn to rejoin <strong>LB Road</strong> near the Indian Oil petrol bunk signal</li>
</ol>
<p class="note"><strong>Important:</strong> Heavy vehicles are strictly not permitted on Indira Nagar 2nd Avenue during the diversion period.</p>
</div>

<h2 class="lb-h2">Diversions toward Thiruvanmiyur</h2>

<div class="lb-route all">
<h3 class="lb-h3">All vehicles (from Thiru.Vi.Ka Bridge / Adyar side)</h3>
<p>From the <strong>LB Road&ndash;Shastri Nagar junction</strong> (<a href="https://www.thehindu.com/news/cities/chennai/traffic-diversions-due-to-chennai-metro-rail-construction-work-on-lb-road/article71149801.ece" target="_blank" rel="noopener noreferrer">The Hindu</a>):</p>
<ol>
<li><strong>Sastri Nagar 1st Avenue</strong></li>
<li>Right turn onto <strong>11th Cross Street</strong> (one-way toward Thiruvanmiyur)</li>
<li>Left turn onto <strong>8th Cross Street</strong></li>
<li>Right turn onto <strong>10th Cross Street</strong></li>
<li>Join <strong>M.G. Road</strong> to continue toward your destination</li>
</ol>
</div>

<h2 class="lb-h2">Temporary road-rule changes</h2>

<div class="lb-rules">
<strong>Two-way corridors (previously one-way)</strong>
<ul>
<li>Sastri Nagar 1st Main Road to LB Road via M.G. Road (Mahatma Gandhi Road)</li>
<li>Sastri Nagar 1st Avenue to Sastri Nagar 1st Main Road</li>
</ul>
<strong style="margin-top:.75rem">Strict one-way restrictions</strong>
<ul>
<li><strong>Sastri Nagar 1st Main Road</strong> &mdash; one-way only toward Thiru.Vi.Ka Bridge</li>
<li><strong>11th Cross Street</strong> &mdash; one-way only toward Thiruvanmiyur</li>
</ul>
</div>

<h2 class="lb-h2">Commuter tips</h2>

<div class="lb-tips">
<ul>
<li>Allow <strong>10&ndash;15 extra minutes</strong> if your route crosses LB Road, Adyar or Besant Nagar during peak hours.</li>
<li>Follow on-ground signage and traffic police directions &mdash; temporary two-way rules may feel unfamiliar on narrow residential streets.</li>
<li>If you use <strong>Thiruvanmiyur MRTS</strong>, Tidel Park or OMR-bound buses from the Adyar side, check whether your feeder route crosses the closed stretch.</li>
<li>Heavy vehicle operators: do not attempt Indira Nagar 2nd Avenue; use the Sastri Nagar corridor only.</li>
<li>We will update this article when police announce reopening or route changes.</li>
</ul>
</div>

<h2 class="lb-h2">Why this matters for OMR residents</h2>
<p>Thiruvanmiyur sits at the northern gateway to Old Mahabalipuram Road. Many OMR commuters use LB Road to reach Adyar, Besant Nagar, ECR connectors, Thiruvanmiyur MRTS or Tidel Park. Even if you do not live on LB Road, a closure here can ripple into longer travel times on radial roads feeding OMR.</p>
<p>The work is part of Chennai Metro Phase II underground construction. Similar metro-related lane closures and diversions have affected other parts of the corridor in recent months; this is one of the most significant closures on the Thiruvanmiyur&ndash;Adyar link.</p>

<div class="lb-view">
<h2 class="lb-h2">MyOMR View</h2>
<p>Metro expansion is necessary infrastructure, but residents deserve clear, timely diversion information. Save the official route map, bookmark the Google Maps links above, and share this guide with neighbours who commute through Thiruvanmiyur or Adyar.</p>
<p>If you notice unsafe driving on newly two-way residential stretches or missing signage, report it through the civic issue form or alert local traffic police.</p>
</div>

<p class="lb-sources"><strong>Sources:</strong> <a href="https://www.thehindu.com/news/cities/chennai/traffic-diversions-due-to-chennai-metro-rail-construction-work-on-lb-road/article71149801.ece" target="_blank" rel="noopener noreferrer">The Hindu</a> (26 June 2026); Greater Chennai City Traffic Police diversion notice; <a href="https://timesofindia.indiatimes.com/city/chennai/police-announce-traffic-diversions-on-l-b-road/articleshow/132019599.cms" target="_blank" rel="noopener noreferrer">Times of India</a>; <a href="https://www.dtnext.in/news/chennai/traffic-diversion-on-chennais-lb-road-due-to-metro-rail-construction-works" target="_blank" rel="noopener noreferrer">DT Next</a>.</p>

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
        'sssssssssis',
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
    $action = 'UPDATED id=' . $existing['id'];
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
echo 'URL: https://myomr.in/local-news/' . $slug . PHP_EOL;
