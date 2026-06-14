<?php
/**
 * Temporary diagnostics — remove or set MYOMR_PUBLISHER_DEBUG to false once stable.
 */
define('MYOMR_PUBLISHER_DEBUG', false);

if (MYOMR_PUBLISHER_DEBUG) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

$__publisher_debug = [
    'steps' => [],
    'checks' => [],
    'runtime_errors' => [],
    'php_version' => PHP_VERSION,
    'script' => __FILE__,
    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? '(unknown)',
];

if (!function_exists('myomr_publisher_debug_step')) {
    function myomr_publisher_debug_step(string $message): void
    {
        global $__publisher_debug;
        $__publisher_debug['steps'][] = $message;
    }
}

if (!function_exists('myomr_publisher_debug_check')) {
    function myomr_publisher_debug_check(string $label, string $path): void
    {
        global $__publisher_debug;
        $__publisher_debug['checks'][] = [
            'label' => $label,
            'path' => $path,
            'exists' => is_file($path),
            'readable' => is_readable($path),
        ];
    }
}

if (!function_exists('myomr_publisher_render_error_page')) {
    function myomr_publisher_render_error_page(string $title, string $message, ?Throwable $e = null): void
    {
        global $__publisher_debug;

        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: text/html; charset=UTF-8');
        }

        $trace = $e ? $e->getTraceAsString() : '';
        $file = $e ? $e->getFile() . ':' . $e->getLine() : '';
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> — News Publisher</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
  <main class="container" style="max-width:900px">
    <div class="alert alert-danger">
      <h1 class="h4 mb-2"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
      <p class="mb-0"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
      <?php if ($file): ?>
      <p class="mb-0 mt-2 small"><strong>Location:</strong> <code><?= htmlspecialchars($file, ENT_QUOTES, 'UTF-8') ?></code></p>
      <?php endif; ?>
    </div>
    <?php if ($trace): ?>
    <pre class="bg-dark text-warning p-3 rounded small" style="white-space:pre-wrap;overflow:auto"><?= htmlspecialchars($trace, ENT_QUOTES, 'UTF-8') ?></pre>
    <?php endif; ?>
    <?php myomr_publisher_render_debug_panel(true); ?>
    <a class="btn btn-outline-secondary" href="/superadmin/index.php">Back to dashboard</a>
  </main>
</body>
</html>
        <?php
        exit;
    }
}

if (!function_exists('myomr_publisher_render_debug_panel')) {
    function myomr_publisher_render_debug_panel(bool $forceOpen = false): void
    {
        global $__publisher_debug;

        if (!MYOMR_PUBLISHER_DEBUG) {
            return;
        }

        $open = $forceOpen || !empty($__publisher_debug['runtime_errors']);
        ?>
<details class="publisher-debug-panel mb-4" <?= $open ? 'open' : '' ?>>
  <summary class="btn btn-sm btn-outline-dark mb-2">Diagnostics (remove after fixing)</summary>
  <div class="card card-body small">
    <p class="mb-2"><strong>PHP:</strong> <?= htmlspecialchars($__publisher_debug['php_version'], ENT_QUOTES, 'UTF-8') ?></p>
    <p class="mb-2"><strong>Script:</strong> <code><?= htmlspecialchars($__publisher_debug['script'], ENT_QUOTES, 'UTF-8') ?></code></p>
    <p class="mb-3"><strong>Document root:</strong> <code><?= htmlspecialchars($__publisher_debug['document_root'], ENT_QUOTES, 'UTF-8') ?></code></p>

    <?php if (!empty($__publisher_debug['steps'])): ?>
    <h2 class="h6">Boot steps</h2>
    <ol class="mb-3">
      <?php foreach ($__publisher_debug['steps'] as $step): ?>
      <li><?= htmlspecialchars($step, ENT_QUOTES, 'UTF-8') ?></li>
      <?php endforeach; ?>
    </ol>
    <?php endif; ?>

    <?php if (!empty($__publisher_debug['checks'])): ?>
    <h2 class="h6">Dependency checks</h2>
    <table class="table table-sm table-bordered mb-3">
      <thead><tr><th>File</th><th>Exists</th><th>Readable</th><th>Resolved path</th></tr></thead>
      <tbody>
      <?php foreach ($__publisher_debug['checks'] as $check): ?>
        <tr class="<?= $check['exists'] && $check['readable'] ? 'table-success' : 'table-danger' ?>">
          <td><code><?= htmlspecialchars($check['label'], ENT_QUOTES, 'UTF-8') ?></code></td>
          <td><?= $check['exists'] ? 'yes' : 'no' ?></td>
          <td><?= $check['readable'] ? 'yes' : 'no' ?></td>
          <td><code><?= htmlspecialchars($check['path'], ENT_QUOTES, 'UTF-8') ?></code></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>

    <?php if (!empty($__publisher_debug['runtime_errors'])): ?>
    <h2 class="h6">Runtime errors</h2>
    <?php foreach ($__publisher_debug['runtime_errors'] as $runtimeError): ?>
    <pre class="bg-light border rounded p-2 mb-2" style="white-space:pre-wrap"><?= htmlspecialchars($runtimeError, ENT_QUOTES, 'UTF-8') ?></pre>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>
</details>
        <?php
    }
}

if (MYOMR_PUBLISHER_DEBUG) {
    set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
        if (!(error_reporting() & $severity)) {
            return false;
        }
        throw new ErrorException($message, 0, $severity, $file, $line);
    });

    set_exception_handler(static function (Throwable $e): void {
        myomr_publisher_render_error_page('Uncaught exception', $e->getMessage(), $e);
    });

    register_shutdown_function(static function (): void {
        $last = error_get_last();
        if (!$last) {
            return;
        }
        $fatalTypes = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];
        if (!in_array($last['type'], $fatalTypes, true)) {
            return;
        }
        myomr_publisher_render_error_page(
            'Fatal PHP error',
            $last['message'] . ' in ' . $last['file'] . ':' . $last['line']
        );
    });
}

try {
    myomr_publisher_debug_step('Loading superadmin auth');
    require_once __DIR__ . '/_bootstrap.php';
    myomr_publisher_debug_step('Superadmin session OK');

    $publisher_deps = [
        __DIR__ . '/../core/security-helpers.php' => 'core/security-helpers.php',
        __DIR__ . '/../core/publishing/news-article-publisher.php' => 'core/publishing/news-article-publisher.php',
        __DIR__ . '/../core/omr-connect.php' => 'core/omr-connect.php',
    ];

    foreach ($publisher_deps as $path => $label) {
        myomr_publisher_debug_check($label, $path);
        if (!is_file($path)) {
            myomr_publisher_render_error_page(
                'Missing dependency',
                'Upload ' . $label . ' to the site root, then reload this page.'
            );
        }
    }

    myomr_publisher_debug_step('Loading security helpers');
    require_once __DIR__ . '/../core/security-helpers.php';

    myomr_publisher_debug_step('Loading news article publisher');
    require_once __DIR__ . '/../core/publishing/news-article-publisher.php';

    if (!function_exists('myomr_news_article_defaults')) {
        myomr_publisher_render_error_page(
            'Publisher functions missing',
            'news-article-publisher.php loaded but myomr_news_article_defaults() was not defined.'
        );
    }

    myomr_publisher_debug_step('Loading database connection');
    require_once __DIR__ . '/../core/omr-connect.php';
    global $conn;

    if (!isset($conn) || !($conn instanceof mysqli)) {
        myomr_publisher_render_error_page(
            'Database connection unavailable',
            'omr-connect.php did not provide a valid $conn mysqli instance.'
        );
    }
    if ($conn->connect_error) {
        myomr_publisher_render_error_page(
            'Database connection failed',
            'mysqli connect_error: ' . $conn->connect_error
        );
    }
    myomr_publisher_debug_step('Database connection OK');

    $values = myomr_news_article_defaults();
    $errors = [];
    $warnings = [];
    $result = null;
    $preview = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        myomr_publisher_debug_step('Processing POST request');
        if (!function_exists('verify_csrf_token') || !verify_csrf_token($_POST['csrf_token'] ?? '')) {
            $errors[] = 'Invalid CSRF token. Please reload and try again.';
        } else {
            $values = myomr_news_article_from_post($_POST);
            $preview = ($_POST['intent'] ?? '') === 'preview';

            try {
                $check = myomr_news_article_validate($values, $conn);
                $errors = $check['errors'];
                $warnings = $check['warnings'];

                if (!$errors && !$preview) {
                    $result = myomr_news_article_save($conn, $values);
                    myomr_publisher_debug_step('Article saved: ' . ($result['action'] ?? 'unknown'));
                }
            } catch (Throwable $e) {
                $__publisher_debug['runtime_errors'][] = $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine();
                $errors[] = 'Save/validate error: ' . $e->getMessage();
            }
        }
    }

    $publicUrl = myomr_news_article_public_url($values['slug']);
    $sitemapUrl = 'https://myomr.in/local-news/sitemap.xml';
    $publishedTimestamp = strtotime($values['published_date']);
    $publishedInputValue = $publishedTimestamp === false ? '' : date('Y-m-d\TH:i', $publishedTimestamp);
    myomr_publisher_debug_step('Page render ready');
} catch (Throwable $e) {
    myomr_publisher_render_error_page('Bootstrap failed', $e->getMessage(), $e);
}

$pageTitle = 'Token-Free News Publisher';
$activeNav = '/superadmin/news-publisher.php';
include __DIR__ . '/includes/admin-shell-open.php';
?>
<style>
  .publisher-grid { display:grid; grid-template-columns:minmax(0, 1fr) 360px; gap:1.25rem; align-items:start; }
  .publisher-panel { background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:1.25rem; }
  .article-preview { max-height:520px; overflow:auto; }
  .article-preview img, .article-preview iframe { max-width:100%; }
  @media (max-width: 991.98px) { .publisher-grid { grid-template-columns:1fr; } }
</style>
  <main class="container-fluid py-4 px-3 px-lg-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-1">Token-Free News Publisher</h1>
        <p class="text-muted mb-0">Paste a structured news item, preview it, then insert or update the <code>articles</code> row by slug.</p>
      </div>
      <a href="/superadmin/index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Dashboard</a>
    </div>

    <?php myomr_publisher_render_debug_panel(); ?>

    <?php foreach ($errors as $error): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endforeach; ?>

    <?php foreach ($warnings as $warning): ?>
      <div class="alert alert-warning"><?php echo htmlspecialchars($warning, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endforeach; ?>

    <?php if ($result): ?>
      <div class="alert alert-success">
        Article <?php echo htmlspecialchars($result['action'], ENT_QUOTES, 'UTF-8'); ?> as ID #<?php echo (int) $result['id']; ?>.
        <a href="<?php echo htmlspecialchars($result['url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">Open public URL</a>
      </div>
    <?php endif; ?>

    <div class="publisher-grid">
      <form method="POST" class="publisher-panel" autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">

        <div class="mb-3">
          <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($values['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label" for="slug">Slug <span class="text-danger">*</span></label>
          <div class="input-group">
            <input type="text" class="form-control" id="slug" name="slug" value="<?php echo htmlspecialchars($values['slug'], ENT_QUOTES, 'UTF-8'); ?>" required pattern="[a-z0-9]+(-[a-z0-9]+)*">
            <button class="btn btn-outline-secondary" type="button" id="makeSlug" title="Generate slug from title"><i class="fas fa-wand-magic-sparkles"></i></button>
          </div>
          <div class="form-text">Public URL: <span id="publicUrl"><?php echo htmlspecialchars($publicUrl, ENT_QUOTES, 'UTF-8'); ?></span></div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="summary">Summary / meta description <span class="text-danger">*</span></label>
          <textarea class="form-control" id="summary" name="summary" rows="3" required><?php echo htmlspecialchars($values['summary'], ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label" for="content">Article content HTML <span class="text-danger">*</span></label>
          <textarea class="form-control" id="content" name="content" rows="18" required><?php echo htmlspecialchars($values['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label" for="category">Category</label>
            <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($values['category'], ENT_QUOTES, 'UTF-8'); ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label" for="author">Author</label>
            <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($values['author'], ENT_QUOTES, 'UTF-8'); ?>">
          </div>
        </div>

        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="form-label" for="status">Status</label>
            <select class="form-select" id="status" name="status">
              <option value="draft" <?php echo $values['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
              <option value="published" <?php echo $values['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
            </select>
          </div>
          <div class="col-md-5 mb-3">
            <label class="form-label" for="published_date">Published date</label>
            <input type="datetime-local" class="form-control" id="published_date" name="published_date" value="<?php echo htmlspecialchars($publishedInputValue, ENT_QUOTES, 'UTF-8'); ?>">
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label" for="is_featured">Featured</label>
            <select class="form-select" id="is_featured" name="is_featured">
              <option value="0" <?php echo $values['is_featured'] !== '1' ? 'selected' : ''; ?>>No</option>
              <option value="1" <?php echo $values['is_featured'] === '1' ? 'selected' : ''; ?>>Yes</option>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="image_path">Image path</label>
          <input type="text" class="form-control" id="image_path" name="image_path" value="<?php echo htmlspecialchars($values['image_path'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="/local-news/omr-news-images/example.webp">
        </div>

        <div class="mb-3">
          <label class="form-label" for="tags">Tags</label>
          <input type="text" class="form-control" id="tags" name="tags" value="<?php echo htmlspecialchars($values['tags'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="OMR, Chennai, Sholinganallur">
        </div>

        <div class="d-flex flex-wrap gap-2">
          <button type="submit" name="intent" value="preview" class="btn btn-outline-primary"><i class="fas fa-eye me-1"></i> Preview / Dry Run</button>
          <button type="submit" name="intent" value="publish" class="btn btn-success"><i class="fas fa-database me-1"></i> Save to Articles</button>
        </div>
      </form>

      <aside class="publisher-panel">
        <h2 class="h5">Publish Check</h2>
        <dl class="small mb-3">
          <dt>Mode</dt>
          <dd><?php echo $preview ? 'Dry run only. No DB changes.' : 'Ready to insert/update by slug.'; ?></dd>
          <dt>Public URL</dt>
          <dd><a href="<?php echo htmlspecialchars($publicUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($publicUrl, ENT_QUOTES, 'UTF-8'); ?></a></dd>
          <dt>Sitemap</dt>
          <dd><a href="<?php echo htmlspecialchars($sitemapUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">local-news/sitemap.xml</a></dd>
        </dl>

        <?php if ($preview && !$errors): ?>
          <h2 class="h5">Preview</h2>
          <article class="article-preview border rounded p-3">
            <h1 class="h4"><?php echo htmlspecialchars($values['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="text-muted small mb-2"><?php echo htmlspecialchars($values['category'], ENT_QUOTES, 'UTF-8'); ?> · <?php echo htmlspecialchars($values['published_date'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong><?php echo htmlspecialchars($values['summary'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
            <?php echo $values['content']; ?>
          </article>
        <?php else: ?>
          <p class="text-muted small mb-0">Use Preview / Dry Run to inspect the article HTML and whether the slug will insert or update.</p>
        <?php endif; ?>
      </aside>
    </div>
  </main>

  <script>
  (function() {
    function slugify(text) {
      return (text || '').toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').replace(/-+/g, '-');
    }
    var title = document.getElementById('title');
    var slug = document.getElementById('slug');
    var publicUrl = document.getElementById('publicUrl');
    var btn = document.getElementById('makeSlug');
    function updateUrl() {
      publicUrl.textContent = 'https://myomr.in/local-news/' + slug.value;
    }
    btn.addEventListener('click', function() {
      slug.value = slugify(title.value);
      updateUrl();
    });
    slug.addEventListener('input', function() {
      slug.value = slugify(slug.value);
      updateUrl();
    });
    updateUrl();
  })();
  </script>
<?php include __DIR__ . '/includes/admin-shell-close.php'; ?>
