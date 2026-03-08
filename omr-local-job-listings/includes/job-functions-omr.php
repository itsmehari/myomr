<?php
/**
 * Job Portal Helper Functions
 * Central functions for the MyOMR Job Portal
 *
 * @package MyOMR Job Portal
 * @version 2.0.0
 *
 * CHANGELOG v2.0:
 *  - getJobListings() / getJobCount() now push ALL filters to SQL WHERE.
 *    No more full-table PHP scan. Pagination counts are always correct.
 *  - Added _buildJobWhereClause() to keep filter logic in one place.
 *  - Added timeAgo() for "2 days ago" style dates.
 *  - Added formatSalaryStructured() for salary_min/salary_max columns.
 *  - All debug error_log() calls gated behind DEVELOPMENT_MODE.
 *  - Removed fallback LOWER/TRIM queries (data integrity should be fixed at DB).
 */

// ─── QUERY HELPERS ───────────────────────────────────────────────────────────

/**
 * Build a reusable WHERE clause + bound params array from a filter map.
 *
 * Returns: [ $whereString, $paramsArray, $typesString ]
 */
function _buildJobWhereClause(array $filters): array
{
    $conditions = ["j.status = 'approved'"];
    $params     = [];
    $types      = '';

    if (!empty($filters['category'])) {
        $conditions[] = 'j.category = ?';
        $params[]     = $filters['category'];
        $types       .= 's';
    }

    if (!empty($filters['location'])) {
        $conditions[] = 'j.location LIKE ?';
        $params[]     = '%' . $filters['location'] . '%';
        $types       .= 's';
    }

    if (!empty($filters['job_type'])) {
        $conditions[] = 'j.job_type = ?';
        $params[]     = $filters['job_type'];
        $types       .= 's';
    }

    if (!empty($filters['experience_level'])) {
        $conditions[] = 'j.experience_level = ?';
        $params[]     = $filters['experience_level'];
        $types       .= 's';
    }

    if (isset($filters['is_remote']) && $filters['is_remote'] !== '') {
        $conditions[] = 'j.is_remote = ?';
        $params[]     = (int) $filters['is_remote'];
        $types       .= 'i';
    }

    if (!empty($filters['salary_min'])) {
        $conditions[] = 'j.salary_min >= ?';
        $params[]     = (int) $filters['salary_min'];
        $types       .= 'i';
    }

    if (!empty($filters['salary_max'])) {
        $conditions[] = '(j.salary_max <= ? OR j.salary_max IS NULL OR j.salary_max = 0)';
        $params[]     = (int) $filters['salary_max'];
        $types       .= 'i';
    }

    if (!empty($filters['search'])) {
        $like         = '%' . $filters['search'] . '%';
        $conditions[] = '(j.title LIKE ? OR j.description LIKE ? OR e.company_name LIKE ?)';
        $params[]     = $like;
        $params[]     = $like;
        $params[]     = $like;
        $types       .= 'sss';
    }

    // Featured-only spotlight filter
    if (!empty($filters['is_featured'])) {
        $conditions[] = 'j.featured = 1';
    }

    return [implode(' AND ', $conditions), $params, $types];
}

// ─── LISTING & COUNT ──────────────────────────────────────────────────────────

/**
 * Get job listings with SQL-level filtering and pagination.
 * All filter conditions are pushed into the WHERE clause — zero PHP-level scan.
 *
 * @param array $filters  category, location, job_type, search, experience_level,
 *                        is_remote, salary_min, salary_max
 * @param int   $limit    Rows per page
 * @param int   $offset   SQL OFFSET
 * @return array
 */
function getJobListings(array $filters = [], int $limit = 20, int $offset = 0): array
{
    require_once __DIR__ . '/../../core/omr-connect.php';
    global $conn;

    if (!$conn instanceof mysqli || $conn->connect_error) {
        if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE) {
            error_log('getJobListings(): DB connection unavailable');
        }
        return [];
    }

    [$where, $params, $types] = _buildJobWhereClause($filters);

    $sql = "SELECT j.*,
                   e.company_name, e.contact_person,
                   e.email    AS employer_email,
                   e.phone    AS employer_phone,
                   e.address  AS company_address,
                   e.company_logo,
                   c.name     AS category_name
            FROM job_postings j
            LEFT JOIN employers e ON j.employer_id = e.id
            LEFT JOIN job_categories c ON j.category = c.slug
            WHERE {$where}
            ORDER BY j.featured DESC, j.created_at DESC
            LIMIT ? OFFSET ?";

    $params[] = (int) $limit;
    $params[] = (int) $offset;
    $types   .= 'ii';

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE) {
            error_log('getJobListings(): prepare() failed: ' . $conn->error);
        }
        return [];
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Count total jobs matching filters.
 * Uses a single COUNT(*) query — no full-table PHP scan.
 *
 * @param array $filters  Same keys as getJobListings()
 * @return int
 */
function getJobCount(array $filters = []): int
{
    require_once __DIR__ . '/../../core/omr-connect.php';
    global $conn;

    if (!$conn instanceof mysqli || $conn->connect_error) {
        return 0;
    }

    [$where, $params, $types] = _buildJobWhereClause($filters);

    $sql = "SELECT COUNT(*) AS total
            FROM job_postings j
            LEFT JOIN employers e ON j.employer_id = e.id
            WHERE {$where}";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return 0;
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return (int) ($row['total'] ?? 0);
}

// ─── SINGLE JOB ───────────────────────────────────────────────────────────────

/**
 * Get a single approved job by ID.
 * Returns null if not found or not approved.
 *
 * @param int $job_id
 * @return array|null
 */
function getJobById(int $job_id): ?array
{
    require_once __DIR__ . '/../../core/omr-connect.php';
    global $conn;

    if (!$conn instanceof mysqli) {
        return null;
    }

    $stmt = $conn->prepare(
        "SELECT j.*,
                e.company_name, e.contact_person,
                e.email   AS employer_email,
                e.phone   AS employer_phone,
                e.address AS company_address,
                e.company_logo,
                c.name    AS category_name
         FROM job_postings j
         LEFT JOIN employers e ON j.employer_id = e.id
         LEFT JOIN job_categories c ON j.category = c.slug
         WHERE j.id = ? AND j.status = 'approved'"
    );

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('i', $job_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: null;
}

/**
 * Get related jobs in the same category (excluding the current job).
 *
 * @param int    $job_id
 * @param string $category
 * @param int    $limit
 * @return array
 */
function getRelatedJobs(int $job_id, string $category, int $limit = 3): array
{
    require_once __DIR__ . '/../../core/omr-connect.php';
    global $conn;

    if (!$conn instanceof mysqli) {
        return [];
    }

    $stmt = $conn->prepare(
        "SELECT j.*, e.company_name, e.company_logo
         FROM job_postings j
         LEFT JOIN employers e ON j.employer_id = e.id
         WHERE j.category = ? AND j.id != ? AND j.status = 'approved'
         ORDER BY j.created_at DESC
         LIMIT ?"
    );

    if (!$stmt) {
        return [];
    }

    $stmt->bind_param('sii', $category, $job_id, $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// ─── APPLICATIONS ─────────────────────────────────────────────────────────────

/**
 * Check if an email address has already applied for a job.
 *
 * @param int    $job_id
 * @param string $email
 * @return bool
 */
function hasUserApplied(int $job_id, string $email): bool
{
    require_once __DIR__ . '/../../core/omr-connect.php';
    global $conn;

    if (!$conn instanceof mysqli) {
        return false;
    }

    $stmt = $conn->prepare(
        'SELECT 1 FROM job_applications WHERE job_id = ? AND applicant_email = ? LIMIT 1'
    );

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param('is', $job_id, $email);
    $stmt->execute();
    return (bool) $stmt->get_result()->fetch_assoc();
}

// ─── VIEWS ────────────────────────────────────────────────────────────────────

/**
 * Increment view count for a job posting.
 *
 * @param int $job_id
 */
function incrementJobViews(int $job_id): void
{
    require_once __DIR__ . '/../../core/omr-connect.php';
    global $conn;

    if (!$conn instanceof mysqli) {
        return;
    }

    $stmt = $conn->prepare('UPDATE job_postings SET views = views + 1 WHERE id = ?');
    if (!$stmt) {
        return;
    }
    $stmt->bind_param('i', $job_id);
    $stmt->execute();
}

// ─── CATEGORIES ───────────────────────────────────────────────────────────────

/**
 * Get all active job categories.
 *
 * @return array
 */
function getJobCategories(): array
{
    require_once __DIR__ . '/../../core/omr-connect.php';
    global $conn;

    if (!$conn instanceof mysqli || $conn->connect_error) {
        return [];
    }

    $result = $conn->query("SELECT * FROM job_categories WHERE is_active = 1 ORDER BY name");
    if (!$result || $result->num_rows === 0) {
        $result = $conn->query("SELECT * FROM job_categories ORDER BY name");
    }

    return ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// ─── PAGINATION ───────────────────────────────────────────────────────────────

/**
 * Generate Bootstrap pagination HTML with ellipsis for large page counts.
 *
 * @param int    $current_page
 * @param int    $total_pages
 * @param string $base_url
 * @return string  HTML string
 */
function generatePagination(int $current_page, int $total_pages, string $base_url): string
{
    if ($total_pages <= 1) {
        return '';
    }

    $buildUrl = function (int $page) use ($base_url): string {
        $base = rtrim($base_url, '?&');
        if ($page <= 1) {
            return $base;
        }
        $sep = strpos($base, '?') !== false ? '&' : '?';
        return $base . $sep . 'page=' . $page;
    };

    $html = '<nav aria-label="Job listings pagination"><ul class="pagination justify-content-center flex-wrap">';

    // Previous
    $html .= $current_page > 1
        ? '<li class="page-item"><a class="page-link" href="' . htmlspecialchars($buildUrl($current_page - 1)) . '">&laquo; Prev</a></li>'
        : '<li class="page-item disabled"><span class="page-link">&laquo; Prev</span></li>';

    // Window of page numbers with leading/trailing ellipsis
    $start = max(1, $current_page - 3);
    $end   = min($total_pages, $current_page + 3);

    if ($start > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . htmlspecialchars($buildUrl(1)) . '">1</a></li>';
        if ($start > 2) {
            $html .= '<li class="page-item disabled"><span class="page-link">…</span></li>';
        }
    }

    for ($i = $start; $i <= $end; $i++) {
        $html .= $i === $current_page
            ? '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>'
            : '<li class="page-item"><a class="page-link" href="' . htmlspecialchars($buildUrl($i)) . '">' . $i . '</a></li>';
    }

    if ($end < $total_pages) {
        if ($end < $total_pages - 1) {
            $html .= '<li class="page-item disabled"><span class="page-link">…</span></li>';
        }
        $html .= '<li class="page-item"><a class="page-link" href="' . htmlspecialchars($buildUrl($total_pages)) . '">' . $total_pages . '</a></li>';
    }

    // Next
    $html .= $current_page < $total_pages
        ? '<li class="page-item"><a class="page-link" href="' . htmlspecialchars($buildUrl($current_page + 1)) . '">Next &raquo;</a></li>'
        : '<li class="page-item disabled"><span class="page-link">Next &raquo;</span></li>';

    $html .= '</ul></nav>';
    return $html;
}

// ─── FORMATTING HELPERS ───────────────────────────────────────────────────────

/**
 * Convert a datetime string to a human-readable "time ago" string.
 * e.g. "2 days ago", "3 hours ago", "Just now".
 *
 * @param string $datetime  MySQL DATETIME value
 * @return string
 */
function timeAgo(string $datetime): string
{
    $timestamp = strtotime($datetime);
    if ($timestamp === false || $timestamp <= 0) {
        return '';
    }

    $diff = max(0, time() - $timestamp);

    if ($diff < 60) {
        return 'Just now';
    }
    if ($diff < 3600) {
        $m = (int) ($diff / 60);
        return $m . ' minute' . ($m === 1 ? '' : 's') . ' ago';
    }
    if ($diff < 86400) {
        $h = (int) ($diff / 3600);
        return $h . ' hour' . ($h === 1 ? '' : 's') . ' ago';
    }
    if ($diff < 86400 * 7) {
        $d = (int) ($diff / 86400);
        return $d . ' day' . ($d === 1 ? '' : 's') . ' ago';
    }
    if ($diff < 86400 * 30) {
        $w = (int) ($diff / (86400 * 7));
        return $w . ' week' . ($w === 1 ? '' : 's') . ' ago';
    }
    return date('M j, Y', $timestamp);
}

/**
 * Format a raw salary string for display (legacy salary_range column).
 *
 * @param string $salary
 * @return string
 */
function formatSalary(string $salary): string
{
    if (empty($salary) || stripos($salary, 'not disclosed') !== false) {
        return 'Competitive';
    }
    $salary = ltrim(trim($salary), '₹ ');
    return '₹' . $salary;
}

/**
 * Format salary from the structured salary_min / salary_max INT columns.
 * Returns "₹25K–45K/mo", "₹1.2L/mo", etc.
 *
 * @param int|null $min
 * @param int|null $max
 * @return string
 */
function formatSalaryStructured(?int $min, ?int $max): string
{
    if (!$min && !$max) {
        return 'Competitive';
    }

    $fmt = static function (int $n): string {
        if ($n >= 100000) {
            return number_format($n / 100000, 1) . 'L';
        }
        if ($n >= 1000) {
            return number_format($n / 1000, 0) . 'K';
        }
        return (string) $n;
    };

    if ($min && $max) {
        return '₹' . $fmt($min) . '–' . $fmt($max) . '/mo';
    }
    return '₹' . $fmt($min ?: $max) . '/mo';
}

/**
 * Create a URL-safe slug from a string.
 *
 * @param string $text
 * @return string
 */
function createSlug(string $text): string
{
    $text = mb_strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

// ─── VALIDATION ───────────────────────────────────────────────────────────────

/**
 * Sanitize a user-supplied string to prevent XSS.
 *
 * @param string $data
 * @return string
 */
function sanitizeInput(string $data): string
{
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email address format.
 *
 * @param string $email
 * @return bool
 */
function validateEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate Indian mobile phone number.
 * Accepts: 9876543210, +91 9876543210, +919876543210
 *
 * @param string $phone
 * @return bool
 */
function validatePhone(string $phone): bool
{
    $digits = preg_replace('/[\s\-\(\)]/', '', $phone);
    return (bool) preg_match('/^(\+91)?[6-9]\d{9}$/', $digits);
}
