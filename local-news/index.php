<?php
/**
 * MyOMR Local News — Index / Listing Page
 * Paginated news listing (9 per page) with category filter + keyword search.
 */

require_once '../core/omr-connect.php';

$per_page   = 9;
$cur_page   = max(1, (int)($_GET['page']     ?? 1));
$filter_cat = htmlspecialchars(trim($_GET['category'] ?? ''), ENT_QUOTES, 'UTF-8');
$search_q   = htmlspecialchars(trim($_GET['q']        ?? ''), ENT_QUOTES, 'UTF-8');

// ── Build WHERE clause ────────────────────────────────────────────────────────
$where_parts = ["status = 'published'"];
$bind_types  = '';
$bind_params = [];

if ($filter_cat !== '') {
    $where_parts[] = 'category = ?';
    $bind_types   .= 's';
    $bind_params[] = $filter_cat;
}
if ($search_q !== '') {
    $where_parts[] = '(title LIKE ? OR summary LIKE ? OR tags LIKE ?)';
    $bind_types   .= 'sss';
    $like          = '%' . $search_q . '%';
    $bind_params   = array_merge($bind_params, [$like, $like, $like]);
}
$where = 'WHERE ' . implode(' AND ', $where_parts);

// ── Total count ───────────────────────────────────────────────────────────────
$total_rows  = 0;
$count_sql   = "SELECT COUNT(*) AS c FROM articles $where";
if ($bind_params) {
    $s = $conn->prepare($count_sql);
    $s->bind_param($bind_types, ...$bind_params);
    $s->execute();
    $total_rows = (int)$s->get_result()->fetch_assoc()['c'];
    $s->close();
} else {
    $r = $conn->query($count_sql);
    if ($r) $total_rows = (int)$r->fetch_assoc()['c'];
}
$total_pages = max(1, (int)ceil($total_rows / $per_page));
$cur_page    = min($cur_page, $total_pages);
$offset      = ($cur_page - 1) * $per_page;

// ── Fetch articles ────────────────────────────────────────────────────────────
$articles  = [];
$data_sql  = "SELECT id, slug, title, summary, category, author, image_path, published_date
                FROM articles $where
               ORDER BY published_date DESC
               LIMIT ? OFFSET ?";
$full_types  = $bind_types . 'ii';
$full_params = array_merge($bind_params, [$per_page, $offset]);
$s2 = $conn->prepare($data_sql);
$s2->bind_param($full_types, ...$full_params);
$s2->execute();
$res = $s2->get_result();
while ($row = $res->fetch_assoc()) $articles[] = $row;
$s2->close();

// ── Category filter list ──────────────────────────────────────────────────────
$categories = [];
$cats_res   = $conn->query(
    "SELECT DISTINCT category FROM articles
      WHERE status='published' AND category IS NOT NULL AND category != ''
      ORDER BY category ASC"
);
if ($cats_res) {
    while ($cr = $cats_res->fetch_assoc()) $categories[] = $cr['category'];
}

// ── Featured / hero article ───────────────────────────────────────────────────
$hero_article = null;
if ($cur_page === 1 && empty($filter_cat) && empty($search_q) && !empty($articles)) {
    $hero_article = array_shift($articles);
}

// ── SEO meta ──────────────────────────────────────────────────────────────────
$page_title = 'OMR Local News — Community Updates, Civic News & Highlights | MyOMR Chennai';
if ($filter_cat !== '') {
    $page_title = htmlspecialchars($filter_cat) . ' News in OMR Chennai | MyOMR';
}
$page_desc     = 'Stay updated with the latest local news from OMR Chennai — roads, schools, businesses, events, civic updates, and community stories from Perungudi to Kelambakkam.';
$canonical_url = 'https://myomr.in/local-news/';
$og_image      = 'https://myomr.in/My-OMR-Logo.png';

// ── Pagination URL helper ─────────────────────────────────────────────────────
function news_pager_url(int $p, string $cat, string $q): string {
    $params = [];
    if ($p > 1)      $params['page']     = $p;
    if ($cat !== '') $params['category'] = $cat;
    if ($q  !== '') $params['q']        = $q;
    return '/local-news/' . ($params ? '?' . http_build_query($params) : '');
}

// ── Subscribe success flag ────────────────────────────────────────────────────
$subscribed   = isset($_GET['subscribed']);
$sub_error    = isset($_GET['subscribe_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($page_desc) ?>">
  <link rel="canonical" href="<?= htmlspecialchars($canonical_url . ($cur_page > 1 ? '?page=' . $cur_page : ''), ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:title"       content="<?= htmlspecialchars($page_title) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($page_desc) ?>">
  <meta property="og:url"         content="<?= $canonical_url ?>">
  <meta property="og:type"        content="website">
  <meta property="og:image"       content="<?= $og_image ?>">
  <meta property="og:site_name"   content="MyOMR">
  <meta name="twitter:card"       content="summary_large_image">
  <meta name="twitter:title"      content="<?= htmlspecialchars($page_title) ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($page_desc) ?>">
  <meta name="twitter:image"      content="<?= $og_image ?>">

  <?php
  $ga_custom_params = [
      'content_group'    => 'Local News',
      'article_category' => $filter_cat ?: 'All',
  ];
  include '../components/analytics.php';
  ?>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- JSON-LD: WebPage + BreadcrumbList -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": <?= json_encode($page_title) ?>,
    "description": <?= json_encode($page_desc) ?>,
    "url": "<?= $canonical_url ?>",
    "publisher": {
      "@type": "Organization",
      "name": "MyOMR",
      "logo": { "@type": "ImageObject", "url": "https://myomr.in/My-OMR-Logo.png" }
    }
  }
  </script>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
      {"@type": "ListItem", "position": 1, "name": "Home",        "item": "https://myomr.in/"},
      {"@type": "ListItem", "position": 2, "name": "Local News",  "item": "https://myomr.in/local-news/"}
      <?php if ($filter_cat !== ''): ?>
      ,{"@type": "ListItem", "position": 3, "name": <?= json_encode($filter_cat) ?>, "item": "https://myomr.in/local-news/?category=<?= urlencode($filter_cat) ?>"}
      <?php endif; ?>
    ]
  }
  </script>

  <style>
    body { font-family: 'Poppins', sans-serif; background: #f9fafb; }
    :root { --brand: #14532d; --brand-mid: #166534; --brand-accent: #22c55e; --brand-light: #f0fdf4; }

    /* ── Hero Bar ── */
    .news-hero {
      background: linear-gradient(135deg, var(--brand) 0%, var(--brand-mid) 100%);
      color: #fff; padding: 52px 0 40px;
    }
    .news-hero h1 { font-weight: 700; margin-bottom: .5rem; }
    .hero-badge-pill {
      display: inline-block; background: rgba(255,255,255,.15);
      border: 1px solid rgba(255,255,255,.3); border-radius: 20px;
      padding: 4px 16px; font-size: .82rem; margin-bottom: 1rem;
    }
    .news-search-bar input:focus { border-color: var(--brand); box-shadow: 0 0 0 .2rem rgba(20,83,45,.15); }
    .news-search-bar .btn-search {
      background: var(--brand-accent); color: #fff; border: none;
      border-radius: 0 8px 8px 0; padding: 10px 20px; font-weight: 600;
    }

    /* ── Category Chips ── */
    .cat-chip {
      display: inline-block; padding: 5px 16px; border-radius: 20px; font-size: .8rem;
      font-weight: 500; border: 1.5px solid #d1fae5; color: var(--brand);
      background: #fff; text-decoration: none; margin: 3px; transition: all .15s;
    }
    .cat-chip:hover, .cat-chip.active {
      background: var(--brand); color: #fff; border-color: var(--brand);
    }

    /* ── Hero Article ── */
    .hero-article-card {
      border-radius: 16px; overflow: hidden; position: relative;
      background: #1f2937; min-height: 360px; display: flex; align-items: flex-end;
      text-decoration: none;
    }
    .hero-article-card img {
      position: absolute; inset: 0; width: 100%; height: 100%;
      object-fit: cover; opacity: .55;
    }
    .hero-article-content {
      position: relative; z-index: 1; padding: 28px 32px; color: #fff;
    }
    .hero-article-content h2 { font-weight: 700; font-size: 1.5rem; line-height: 1.3; }
    .hero-article-content .meta { font-size: .82rem; opacity: .85; }

    /* ── News Cards ── */
    .news-card {
      border-radius: 12px; border: 1px solid #e5e7eb; background: #fff;
      overflow: hidden; height: 100%; transition: transform .2s, box-shadow .2s;
      text-decoration: none; display: flex; flex-direction: column;
    }
    .news-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(20,83,45,.10); }
    .news-card-img { width: 100%; height: 180px; object-fit: cover; background: #e5e7eb; }
    .news-card-img-placeholder {
      width: 100%; height: 180px; background: var(--brand-light);
      display: flex; align-items: center; justify-content: center;
      color: var(--brand); font-size: 2rem;
    }
    .news-card-body { padding: 16px 18px 20px; flex: 1; display: flex; flex-direction: column; }
    .news-cat-badge {
      display: inline-block; font-size: .72rem; font-weight: 600; text-transform: uppercase;
      letter-spacing: .04em; color: var(--brand); background: var(--brand-light);
      padding: 2px 10px; border-radius: 10px; margin-bottom: .5rem;
    }
    .news-card-title { font-weight: 600; color: #1f2937; font-size: .95rem; line-height: 1.45; flex: 1; }
    .news-card-summary { font-size: .82rem; color: #6b7280; margin-top: .4rem; }
    .news-card-meta { font-size: .75rem; color: #9ca3af; margin-top: .75rem; }
    .news-read-more { color: var(--brand); font-weight: 600; font-size: .82rem; text-decoration: none; }
    .news-read-more:hover { text-decoration: underline; }

    /* ── Subscribe Strip ── */
    .subscribe-strip {
      background: linear-gradient(135deg, var(--brand) 0%, var(--brand-mid) 100%);
      color: #fff; border-radius: 16px;
    }
    .subscribe-strip h2 { font-weight: 700; }
    .subscribe-strip .form-control:focus { border-color: #fff; }
    .btn-subscribe { background: var(--brand-accent); color: #fff; border: none; font-weight: 600; border-radius: 8px; padding: 12px 24px; }
    .btn-subscribe:hover { background: #16a34a; color: #fff; }

    /* ── Pagination ── */
    .page-link { color: var(--brand); }
    .page-item.active .page-link { background: var(--brand); border-color: var(--brand); }
    .page-link:hover { color: var(--brand-mid); }

    /* ── Section heading ── */
    .section-heading { font-weight: 700; color: var(--brand); }

    /* ── Breadcrumb ── */
    .breadcrumb-item a { color: var(--brand); text-decoration: none; }
    .breadcrumb-item.active { color: #6b7280; }
  </style>
</head>
<body>

<?php include '../components/main-nav.php'; ?>

<!-- ── Hero ── -->
<section class="news-hero">
  <div class="container">
    <div class="hero-badge-pill"><i class="fas fa-newspaper me-1"></i> OMR Chennai Local News</div>
    <h1 class="display-6">
      <?= $filter_cat !== '' ? htmlspecialchars($filter_cat) . ' News' : 'OMR Local News' ?>
    </h1>
    <p class="mb-4" style="opacity:.9;max-width:560px;">
      Community updates, civic developments, local stories and neighbourhood highlights
      from across the OMR corridor.
    </p>
    <!-- Search -->
    <form method="get" action="/local-news/" class="news-search-bar d-flex" style="max-width:480px;">
      <?php if ($filter_cat !== ''): ?>
        <input type="hidden" name="category" value="<?= htmlspecialchars($filter_cat) ?>">
      <?php endif; ?>
      <input type="text" name="q" value="<?= htmlspecialchars($search_q) ?>"
             class="form-control" placeholder="Search news…"
             style="border-radius:8px 0 0 8px; border-right:none;">
      <button type="submit" class="btn-search">
        <i class="fas fa-search"></i>
      </button>
    </form>
  </div>
</section>

<!-- ── Category Chips ── -->
<section class="bg-white border-bottom py-3">
  <div class="container">
    <a href="/local-news/" class="cat-chip <?= $filter_cat === '' && $search_q === '' ? 'active' : '' ?>">
      All News
    </a>
    <?php foreach ($categories as $cat): ?>
      <a href="/local-news/?category=<?= urlencode($cat) ?>"
         class="cat-chip <?= $filter_cat === $cat ? 'active' : '' ?>">
        <?= htmlspecialchars($cat) ?>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<main class="py-5">
  <div class="container">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb" style="font-size:.83rem;">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item <?= $filter_cat === '' ? 'active' : '' ?>">
          <?php if ($filter_cat !== ''): ?>
            <a href="/local-news/">Local News</a>
          <?php else: ?>
            Local News
          <?php endif; ?>
        </li>
        <?php if ($filter_cat !== ''): ?>
          <li class="breadcrumb-item active"><?= htmlspecialchars($filter_cat) ?></li>
        <?php endif; ?>
      </ol>
    </nav>

    <!-- Search result summary -->
    <?php if ($search_q !== ''): ?>
      <p class="text-muted mb-4">
        <i class="fas fa-search me-1"></i>
        <?= $total_rows ?> result<?= $total_rows !== 1 ? 's' : '' ?> for
        "<strong><?= htmlspecialchars($search_q) ?></strong>"
        — <a href="/local-news/<?= $filter_cat ? '?category=' . urlencode($filter_cat) : '' ?>" class="text-success">clear search</a>
      </p>
    <?php endif; ?>

    <!-- ── Hero Article (page 1, no filter) ── -->
    <?php if ($hero_article): ?>
      <div class="mb-5">
        <h2 class="section-heading mb-3 h5">Top Story</h2>
        <?php
        $h_img = $hero_article['image_path'] ?? '';
        $h_src = '';
        if ($h_img) {
            $h_src = (strpos($h_img, 'http') === 0) ? $h_img : 'https://myomr.in' . (strpos($h_img, '/') === 0 ? $h_img : '/' . $h_img);
        }
        ?>
        <a href="/local-news/<?= htmlspecialchars($hero_article['slug']) ?>"
           class="hero-article-card">
          <?php if ($h_src): ?>
            <img src="<?= htmlspecialchars($h_src) ?>" alt="<?= htmlspecialchars($hero_article['title']) ?>"
                 loading="lazy" decoding="async">
          <?php endif; ?>
          <div class="hero-article-content">
            <span style="background:var(--brand-accent);color:#fff;font-size:.72rem;font-weight:600;padding:3px 12px;border-radius:10px;text-transform:uppercase;letter-spacing:.04em;display:inline-block;margin-bottom:.75rem;">
              <?= htmlspecialchars($hero_article['category'] ?? 'Local News') ?>
            </span>
            <h2><?= htmlspecialchars($hero_article['title']) ?></h2>
            <p class="meta mb-0">
              <i class="fas fa-calendar me-1"></i>
              <?= date('d M Y', strtotime($hero_article['published_date'])) ?>
              &nbsp;·&nbsp;
              <?= htmlspecialchars($hero_article['author'] ?? 'MyOMR Editorial Team') ?>
            </p>
          </div>
        </a>
      </div>
    <?php endif; ?>

    <!-- ── Article Grid ── -->
    <?php if (!empty($articles)): ?>
      <h2 class="section-heading mb-4 h5">
        <?= $filter_cat !== '' ? htmlspecialchars($filter_cat) . ' Articles' : ($hero_article ? 'More Stories' : 'Latest Stories') ?>
      </h2>
      <div class="row g-4">
        <?php foreach ($articles as $art): ?>
          <?php
          $img     = $art['image_path'] ?? '';
          $img_src = '';
          if ($img) {
              $img_src = (strpos($img, 'http') === 0) ? $img : 'https://myomr.in' . (strpos($img, '/') === 0 ? $img : '/' . $img);
          }
          ?>
          <div class="col-md-6 col-lg-4">
            <a href="/local-news/<?= htmlspecialchars($art['slug']) ?>" class="news-card">
              <?php if ($img_src): ?>
                <img class="news-card-img" src="<?= htmlspecialchars($img_src) ?>"
                     alt="<?= htmlspecialchars($art['title']) ?>"
                     loading="lazy" decoding="async">
              <?php else: ?>
                <div class="news-card-img-placeholder">
                  <i class="fas fa-newspaper"></i>
                </div>
              <?php endif; ?>
              <div class="news-card-body">
                <?php if (!empty($art['category'])): ?>
                  <span class="news-cat-badge"><?= htmlspecialchars($art['category']) ?></span>
                <?php endif; ?>
                <div class="news-card-title"><?= htmlspecialchars($art['title']) ?></div>
                <?php if (!empty($art['summary'])): ?>
                  <div class="news-card-summary">
                    <?= htmlspecialchars(mb_strimwidth($art['summary'], 0, 110, '…')) ?>
                  </div>
                <?php endif; ?>
                <div class="news-card-meta">
                  <i class="fas fa-calendar-alt me-1"></i>
                  <?= date('d M Y', strtotime($art['published_date'])) ?>
                  <?php if (!empty($art['author'])): ?>
                    &nbsp;·&nbsp; <?= htmlspecialchars($art['author']) ?>
                  <?php endif; ?>
                </div>
                <span class="news-read-more mt-2 d-inline-block">
                  Read more <i class="fas fa-arrow-right ms-1" style="font-size:.7rem;"></i>
                </span>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php elseif (!$hero_article): ?>
      <div class="text-center py-5 text-muted">
        <i class="fas fa-newspaper fa-3x mb-3" style="opacity:.25;"></i>
        <p>No articles found<?= $search_q ? ' for "' . htmlspecialchars($search_q) . '"' : '' ?>.</p>
        <a href="/local-news/" class="btn btn-outline-success">Browse All News</a>
      </div>
    <?php endif; ?>

    <!-- ── Pagination ── -->
    <?php if ($total_pages > 1): ?>
      <nav class="mt-5 d-flex justify-content-center" aria-label="News pagination">
        <ul class="pagination">
          <?php if ($cur_page > 1): ?>
            <li class="page-item">
              <a class="page-link" href="<?= news_pager_url($cur_page - 1, $filter_cat, $search_q) ?>">
                <i class="fas fa-chevron-left"></i>
              </a>
            </li>
          <?php endif; ?>
          <?php
          $start = max(1, $cur_page - 2);
          $end   = min($total_pages, $cur_page + 2);
          if ($start > 1): ?>
            <li class="page-item"><a class="page-link" href="<?= news_pager_url(1, $filter_cat, $search_q) ?>">1</a></li>
            <?php if ($start > 2): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
          <?php endif; ?>
          <?php for ($p = $start; $p <= $end; $p++): ?>
            <li class="page-item <?= $p === $cur_page ? 'active' : '' ?>">
              <a class="page-link" href="<?= news_pager_url($p, $filter_cat, $search_q) ?>"><?= $p ?></a>
            </li>
          <?php endfor; ?>
          <?php if ($end < $total_pages): ?>
            <?php if ($end < $total_pages - 1): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
            <li class="page-item">
              <a class="page-link" href="<?= news_pager_url($total_pages, $filter_cat, $search_q) ?>"><?= $total_pages ?></a>
            </li>
          <?php endif; ?>
          <?php if ($cur_page < $total_pages): ?>
            <li class="page-item">
              <a class="page-link" href="<?= news_pager_url($cur_page + 1, $filter_cat, $search_q) ?>">
                <i class="fas fa-chevron-right"></i>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
      <p class="text-center text-muted" style="font-size:.8rem;">
        Page <?= $cur_page ?> of <?= $total_pages ?> &nbsp;·&nbsp; <?= $total_rows ?> articles
      </p>
    <?php endif; ?>

    <!-- ── Newsletter Subscribe ── -->
    <div class="subscribe-strip p-4 p-md-5 mt-5 text-center">
      <h2 class="mb-2 h3">Stay Updated on OMR News</h2>
      <p class="mb-4" style="opacity:.9;max-width:480px;margin:0 auto .75rem;">
        Get the latest community updates from OMR Chennai delivered to your inbox — free.
      </p>
      <?php if ($subscribed): ?>
        <div class="alert alert-success d-inline-flex align-items-center px-4 py-2" style="border-radius:30px;">
          <i class="fas fa-check-circle me-2"></i> You're subscribed! Welcome to the OMR community.
        </div>
      <?php elseif ($sub_error): ?>
        <div class="alert alert-danger d-inline-flex align-items-center px-4 py-2" style="border-radius:30px;">
          <i class="fas fa-exclamation-circle me-2"></i> Please enter a valid email address.
        </div>
      <?php else: ?>
        <form method="post" action="/components/subscribe.php"
              class="d-flex gap-2 justify-content-center flex-wrap"
              onsubmit="if(typeof gtag==='function') gtag('event','subscribe',{'event_category':'Newsletter','event_label':'local_news_index'});">
          <input type="hidden" name="source_url" value="/local-news/">
          <input type="email" name="email" required placeholder="Your email address"
                 class="form-control" style="max-width:300px;border-radius:8px;">
          <button type="submit" class="btn-subscribe">
            <i class="fas fa-bell me-1"></i> Subscribe — It's Free
          </button>
        </form>
        <p class="mt-2 mb-0" style="font-size:.78rem;opacity:.75;">
          <i class="fas fa-shield-alt me-1"></i> No spam. Unsubscribe any time.
        </p>
      <?php endif; ?>
    </div>

  </div>
</main>

<?php include '../components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
