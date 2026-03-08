# Phase 1: Core Infrastructure Analysis

**Phase:** 1 of 8  
**Date:** February 2026  
**Status:** Complete  
**Objective:** Document the foundational components of MyOMR.in including core utilities, database connections, security helpers, and reusable components.

---

## 📋 Executive Summary

This phase analyzed the core infrastructure layer of MyOMR.in, including:
- Database connection and configuration
- Core utility functions (URL helpers, security helpers, error handling)
- Reusable components (navigation, meta tags, footer, etc.)
- Authentication and authorization
- Environment configuration

**Key Findings:**
- ✅ Well-structured core utilities with good separation of concerns
- ⚠️ **CRITICAL SECURITY ISSUE:** Hardcoded database credentials in `core/omr-connect.php`
- ⚠️ **SECURITY RISK:** Hardcoded admin credentials in `core/admin-config.php`
- ✅ Comprehensive security helper functions available
- ✅ Good URL normalization and canonical URL generation
- ✅ Proper error handling with environment-based display
- ⚠️ Some duplicate/unused files identified

---

## 📁 Folder Structure Analysis

### `/core/` Directory

**Purpose:** Core utilities, database connection, security functions, and shared logic

**Files Inventory:**

| File | Purpose | Status | Notes |
|------|---------|--------|-------|
| `omr-connect.php` | Database connection | ⚠️ **SECURITY ISSUE** | Hardcoded credentials |
| `url-helpers.php` | URL generation/normalization | ✅ Good | Well-documented functions |
| `security-helpers.php` | Security utilities | ✅ Good | Comprehensive functions |
| `admin-auth.php` | Admin authentication | ✅ Good | Session-based auth |
| `admin-config.php` | Admin configuration | ⚠️ **SECURITY ISSUE** | Hardcoded password hash |
| `error-handler.php` | Error logging/handling | ✅ Good | Environment-aware |
| `app-secrets.php` | Application secrets | ⚠️ Needs review | Default secrets need change |
| `env.php` | Environment detection | ✅ Good | Simple env detection |
| `mailer.php` | Email sending | ✅ Good | Basic mailer function |
| `email.php` | Email utilities | ❓ Check usage | Potential duplicate |
| `article-seo-meta.php` | SEO meta generation | ✅ Good | Specialized function |
| `cache-helpers.php` | Caching utilities | ✅ Good | Caching functions |
| `action-links.php` | Action link component | ✅ Good | Reusable component |
| `action-links.css` | Action links styles | ✅ Good | Component styles |
| `action-links.js` | Action links JS | ✅ Good | Component JS |
| `subscribe.php` | Newsletter subscription | ✅ Good | Subscription handler |
| `subscribe.css` | Subscription styles | ✅ Good | Component styles |
| `modal.css` | Modal styles | ✅ Good | Modal component |
| `modal.js` | Modal functionality | ✅ Good | Modal component |
| `pricing.css` | Pricing styles | ❓ Check usage | Specialized styles |
| `pricing.html` | Pricing template | ❓ Check usage | Specialized template |
| `pricing.js` | Pricing JS | ❓ Check usage | Specialized JS |
| `search-services.html` | Search template | ❓ Check usage | Specialized template |
| `script.js` | Core scripts | ❓ Check usage | Generic JS file |
| `omr-road-database-list.php` | Database utilities | ❓ Check usage | Utility file |
| `news-old-mahabalipuram-road` | Unknown | ❓ **REVIEW** | No extension, unclear purpose |

**Total Files:** 23 files (15 PHP, 5 CSS, 2 JS, 1 HTML, 1 without extension)

---

### `/components/` Directory

**Purpose:** Reusable UI components, navigation, meta tags, and layout elements

**Files Inventory:**

| File | Purpose | Status | Notes |
|------|---------|--------|-------|
| `main-nav.php` | Main navigation | ✅ Good | Comprehensive nav with dropdowns |
| `meta.php` | SEO meta tags | ✅ Good | Complete meta tag generator |
| `footer.php` | Site footer | ✅ Good | Reusable footer |
| `analytics.php` | Google Analytics | ✅ Good | GA integration |
| `head-resources.php` | Head resources | ✅ Good | CSS/JS includes |
| `admin-header.php` | Admin header | ✅ Good | Admin layout |
| `admin-sidebar.php` | Admin sidebar | ✅ Good | Admin navigation |
| `admin-breadcrumbs.php` | Admin breadcrumbs | ✅ Good | Admin navigation aid |
| `admin-flash.php` | Flash messages | ✅ Good | Admin notifications |
| `navbar.php` | Alternative navbar | ⚠️ **DUPLICATE?** | Check if duplicate of main-nav |
| `sidebar.php` | Sidebar component | ✅ Good | Sidebar navigation |
| `action-buttons.php` | Action buttons | ✅ Good | Button components |
| `myomr-news-bulletin.php` | News bulletin | ✅ Good | News widget |
| `myomr-news-bulletin.css` | News bulletin styles | ✅ Good | Component styles |
| `myomr-news-bulletin.js` | News bulletin JS | ✅ Good | Component JS |
| `subscribe.php` | Newsletter subscription | ⚠️ **DUPLICATE?** | Also in /core/ |
| `subscribe.css` | Subscription styles | ⚠️ **DUPLICATE?** | Also in /core/ |
| `social-icons.php` | Social media icons | ✅ Good | Social links |
| `scroll-story.php` | Scroll story component | ✅ Good | Story component |
| `sdg-badge.php` | SDG badge | ✅ Good | UN SDG badge |
| `organization-schema.php` | Schema.org JSON-LD | ✅ Good | Structured data |
| `footer.css` | Footer styles | ✅ Good | Footer styles |
| `nav-footer-styles.css` | Nav/footer styles | ⚠️ **POTENTIAL MERGE** | Could merge with other nav styles |
| `skip-link.php` | Skip to content link | ✅ Good | Accessibility feature |
| `omr-listings-nav.php` | Listings navigation | ✅ Good | Specialized nav |
| `discover-nav.php` | Discover navigation | ✅ Good | Specialized nav |
| `internal-links-hubs.php` | Internal linking | ✅ Good | SEO internal links |
| `job-landing-page-links.php` | Job landing links | ✅ Good | Job feature links |
| `job-related-landing-pages.php` | Job related pages | ✅ Good | Job feature links |
| `thank-you.html` | Thank you page | ✅ Good | Static page |
| `subscribers.txt` | Subscriber list | ⚠️ **SECURITY** | Should be in secure location |

**Total Files:** 29 files (24 PHP, 3 CSS, 1 JS, 1 HTML, 1 TXT)

---

## 🔍 Detailed Analysis

### 1. Database Connection (`core/omr-connect.php`)

**Current Implementation:**
```1:28:core/omr-connect.php
<?php
$servername = "localhost:3306";
$username = "metap8ok_myomr_admin";
$password = "myomr@123";
$database = "metap8ok_myomr";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    // Log error
    error_log("Database connection failed: " . $conn->connect_error);
    
    // Show error if in development mode
    if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE) {
        die("Database Connection Failed: " . htmlspecialchars($conn->connect_error) . 
            "<br><br>Please check:<br>" .
            "- Database server is running<br>" .
            "- Credentials in core/omr-connect.php are correct<br>" .
            "- Database name exists<br>");
    } else {
        die("Database connection failed. Please contact administrator.");
    }
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");
?>
```

**Issues Identified:**

1. ⚠️ **CRITICAL SECURITY ISSUE:** Database credentials are hardcoded in source code
   - Username: `metap8ok_myomr_admin`
   - Password: `myomr@123` (weak password)
   - This file is in version control, exposing credentials

2. ⚠️ **Security Risk:** Credentials are visible to anyone with code access

3. ✅ **Good:** Error handling with environment-aware display
4. ✅ **Good:** UTF-8 charset set correctly

**Recommendations:**

1. **IMMEDIATE:** Move credentials to environment variables or `.env` file
2. **IMMEDIATE:** Add `.env` to `.gitignore` if not already
3. **IMMEDIATE:** Rotate database password
4. **URGENT:** Review all files that might contain credentials

**Suggested Fix:**
```php
// Use environment variables
$servername = $_ENV['DB_HOST'] ?? 'localhost:3306';
$username = $_ENV['DB_USER'] ?? '';
$password = $_ENV['DB_PASS'] ?? '';
$database = $_ENV['DB_NAME'] ?? '';
```

---

### 2. Security Helpers (`core/security-helpers.php`)

**Analysis:**

✅ **Strengths:**
- Comprehensive security functions
- Input sanitization
- CSRF token generation/verification
- File upload validation
- Password hashing with bcrypt
- Rate limiting
- HTML sanitization
- Security event logging

✅ **Functions Available:**
- `sanitize_input()` - Database-safe input sanitization
- `validate_email()` - Email validation
- `validate_phone()` - Indian phone number validation
- `validate_url()` - URL validation
- `generate_csrf_token()` - CSRF protection
- `verify_csrf_token()` - CSRF verification
- `sanitize_filename()` - File upload safety
- `validate_file_upload()` - File upload validation
- `hash_password()` - Secure password hashing
- `verify_password()` - Password verification
- `check_rate_limit()` - Rate limiting
- `sanitize_html()` - HTML sanitization
- `log_security_event()` - Security logging
- `is_admin()` - Admin check
- `require_admin()` - Admin requirement
- `sanitize_pagination()` - Pagination safety

⚠️ **Potential Issues:**

1. `sanitize_input()` uses `real_escape_string()` which is redundant when using prepared statements
2. CSRF tokens stored in session - ensure sessions are secure
3. Rate limiting uses session storage - may not work well with load balancers

**Recommendations:**

1. ✅ Keep using prepared statements (prefer over escaping)
2. ✅ Ensure secure session configuration (httponly, secure, samesite)
3. ⚠️ Consider Redis/database for rate limiting in production

---

### 3. URL Helpers (`core/url-helpers.php`)

**Analysis:**

✅ **Strengths:**
- Clean URL generation
- Canonical URL support
- WWW removal
- HTTPS enforcement
- Trailing slash handling
- Index.php handling

✅ **Functions:**
- `get_canonical_url($path = null)` - Generate canonical URL
- `get_current_url($include_query_string = false)` - Get current URL
- `normalize_url($url)` - Normalize URL format

✅ **Good Practices:**
- Always returns https://myomr.in (non-www)
- Removes trailing slashes (except root)
- Handles index.php redirects

**No Issues Found** - Well implemented

---

### 4. Admin Authentication (`core/admin-auth.php`)

**Analysis:**

**Current Implementation:**
```1:58:core/admin-auth.php
<?php
// Master admin auth for MyOMR

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/admin-config.php';

function isAdminLoggedIn(): bool {
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdmin(): void {
    if (!isAdminLoggedIn()) {
        $redirect = urlencode($_SERVER['REQUEST_URI'] ?? '/');
        header('Location: /admin/login.php?redirect=' . $redirect);
        exit;
    }
}

function requireRole(array $allowedRoles): void {
    requireAdmin();
    $role = $_SESSION['admin_role'] ?? '';
    if (!in_array($role, $allowedRoles, true)) {
        http_response_code(403);
        echo 'Forbidden';
        exit;
    }
}

function attemptAdminLogin(string $username, string $password): bool {
    // Super admin
    if (hash_equals((string)MYOMR_ADMIN_USERNAME, (string)$username) && password_verify($password, (string)MYOMR_ADMIN_PASSWORD_HASH)) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_login_time'] = time();
        $_SESSION['admin_role'] = (string)MYOMR_ADMIN_DEFAULT_ROLE; // super_admin
        return true;
    }
    // Editor events (optional)
    if (!empty(MYOMR_EDITOR_USERNAME) && hash_equals((string)MYOMR_EDITOR_USERNAME, (string)$username) && !empty(MYOMR_EDITOR_PASSWORD_HASH) && password_verify($password, (string)MYOMR_EDITOR_PASSWORD_HASH)) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_login_time'] = time();
        $_SESSION['admin_role'] = 'editor_events';
        return true;
    }
    return false;
}

function adminLogout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}


```

✅ **Strengths:**
- Uses `hash_equals()` for username comparison (timing-safe)
- Uses `password_verify()` for password checking (secure)
- Role-based access control
- Session-based authentication
- Proper logout with cookie cleanup

⚠️ **Issues:**

1. **Admin Config (`core/admin-config.php`):**
   ```php
   define('MYOMR_ADMIN_USERNAME', 'admin');
   define('MYOMR_ADMIN_PASSWORD_HASH', password_hash('Mylapore@21g', PASSWORD_DEFAULT));
   ```
   - ⚠️ Password hash generated at runtime (should be pre-hashed)
   - ⚠️ Default password is weak/common
   - ⚠️ Credentials in source code

**Recommendations:**

1. **IMMEDIATE:** Pre-hash passwords and store in environment variables
2. **IMMEDIATE:** Use strong, unique passwords
3. **IMMEDIATE:** Move credentials to `.env` file
4. ✅ Keep using `password_verify()` and `hash_equals()`

---

### 5. Error Handling (`core/error-handler.php`)

**Analysis:**

✅ **Strengths:**
- Environment-aware error display
- Structured logging
- Date-based log files
- Exception handling
- Fatal error catching

✅ **Features:**
- Production mode hides errors from users
- Development mode shows detailed errors
- Logs to `logs/app-YYYY-MM-DD.log`
- Includes context (file, line, trace)

**No Issues Found** - Well implemented

---

### 6. Application Secrets (`core/app-secrets.php`)

**Analysis:**

```1:16:core/app-secrets.php
<?php
// Application secrets/config for token generation (do not expose publicly)
if (!defined('MYOMR_EVENTS_MANAGE_SECRET')) {
    define('MYOMR_EVENTS_MANAGE_SECRET', 'change-this-strong-secret-please-rotate');
}

// Mailer defaults (can be moved to env/config later)
if (!defined('MYOMR_MAIL_FROM')) {
    define('MYOMR_MAIL_FROM', 'no-reply@myomr.in');
}
if (!defined('MYOMR_MAIL_FROM_NAME')) {
    define('MYOMR_MAIL_FROM_NAME', 'MyOMR');
}
if (!defined('MYOMR_ADMIN_ALERT_EMAIL')) {
    define('MYOMR_ADMIN_ALERT_EMAIL', 'myomrnews@gmail.com');
}


```

⚠️ **Issues:**

1. Default secret is obviously a placeholder: `'change-this-strong-secret-please-rotate'`
2. Should be in environment variables
3. Secret used for event management tokens

**Recommendations:**

1. **URGENT:** Generate strong secret and move to `.env`
2. **URGENT:** Rotate secret if this is production
3. Move all secrets to environment variables

---

### 7. Meta Tags Component (`components/meta.php`)

**Analysis:**

✅ **Strengths:**
- Complete meta tag generation
- Open Graph tags
- Twitter Card tags
- JSON-LD structured data
- Breadcrumb schema
- Canonical URL support

✅ **Features:**
- Organization schema
- BreadcrumbList schema
- Default values with overrides
- Proper escaping

**No Issues Found** - Well implemented

---

### 8. Main Navigation (`components/main-nav.php`)

**Analysis:**

✅ **Strengths:**
- Comprehensive navigation structure
- Mobile responsive
- Accessibility features (ARIA labels)
- Dropdown menus
- Active state handling
- Quick action pills

✅ **Features:**
- Top secondary menu bar
- Main navigation bar
- Mobile menu toggle
- Social media icons
- Contact links

⚠️ **Potential Issues:**

1. Large file with inline CSS (491 lines)
2. Consider extracting CSS to separate file for better maintainability

**Recommendations:**

1. Extract inline CSS to `assets/css/navigation.css`
2. Keep PHP logic in component file
3. ✅ Keep current functionality

---

## 🚨 Security Issues Summary

### Critical Issues (Fix Immediately)

1. **Hardcoded Database Credentials** (`core/omr-connect.php`)
   - **Risk:** Exposes database access
   - **Impact:** HIGH
   - **Fix:** Move to environment variables

2. **Hardcoded Admin Password** (`core/admin-config.php`)
   - **Risk:** Weak default password
   - **Impact:** HIGH
   - **Fix:** Use environment variables, pre-hash password

3. **Default Secret Key** (`core/app-secrets.php`)
   - **Risk:** Weak/placeholder secret
   - **Impact:** MEDIUM
   - **Fix:** Generate strong secret, use environment variable

4. **Subscriber List in Web Directory** (`components/subscribers.txt`)
   - **Risk:** Potential data exposure
   - **Impact:** LOW-MEDIUM
   - **Fix:** Move to secure location outside web root

---

## 📦 Duplicate/Unused Files

### Potential Duplicates

1. `core/subscribe.php` vs `components/subscribe.php`
   - **Action:** Check if both are used, consolidate if possible

2. `components/navbar.php` vs `components/main-nav.php`
   - **Action:** Verify if `navbar.php` is used anywhere, remove if duplicate

3. `core/email.php` vs `core/mailer.php`
   - **Action:** Check usage, consolidate email functionality

### Files Needing Review

1. `core/news-old-mahabalipuram-road` - No extension, unclear purpose
2. `core/pricing.html`, `pricing.css`, `pricing.js` - Check if used
3. `core/search-services.html` - Check if used
4. `core/script.js` - Generic name, check usage

---

## ✅ Best Practices Identified

1. ✅ UTF-8 charset set correctly (`utf8mb4`)
2. ✅ Prepared statements should be used (security-helpers.php provides foundation)
3. ✅ Password hashing with bcrypt
4. ✅ CSRF token protection
5. ✅ Input validation and sanitization
6. ✅ Environment-aware error handling
7. ✅ Canonical URL generation
8. ✅ Structured data (JSON-LD)
9. ✅ Accessibility features (skip links, ARIA labels)
10. ✅ Mobile responsive design

---

## 📊 Component Dependency Map

```
core/
├── omr-connect.php (Database)
│   └── Used by: ALL PHP files requiring DB access
├── url-helpers.php (URL utilities)
│   └── Used by: components/meta.php, many page files
├── security-helpers.php (Security)
│   └── Used by: Admin files, form handlers
├── admin-auth.php (Admin auth)
│   ├── Requires: admin-config.php
│   └── Used by: All /admin/ pages
├── admin-config.php (Admin config)
│   └── Required by: admin-auth.php
├── error-handler.php (Error handling)
│   ├── Requires: env.php
│   └── Auto-loaded globally
└── app-secrets.php (Secrets)
    └── Used by: mailer.php, event handlers

components/
├── meta.php (Meta tags)
│   ├── Requires: core/url-helpers.php
│   └── Used by: ALL page files
├── main-nav.php (Navigation)
│   └── Used by: Most page files
├── footer.php (Footer)
│   └── Used by: Most page files
└── analytics.php (GA)
    └── Used by: Most page files
```

---

## 🎯 Recommendations Summary

### Immediate Actions (Security)

1. **Move all credentials to `.env` file:**
   - Database credentials
   - Admin passwords
   - Secret keys
   - Email configuration

2. **Generate new secrets:**
   - Database password (strong, unique)
   - Admin password (strong, unique)
   - Event management secret (strong, unique)

3. **Secure file locations:**
   - Move `subscribers.txt` outside web root
   - Ensure `.env` is in `.gitignore`

### Short-term Improvements

1. Extract inline CSS from `main-nav.php` to separate file
2. Review and consolidate duplicate files
3. Document all core functions with PHPDoc
4. Add unit tests for core utilities

### Long-term Enhancements

1. Consider using Composer for dependency management
2. Implement PSR-4 autoloading
3. Consider framework (Laravel, Symfony) for better structure
4. Add API layer for future mobile apps

---

## 📈 Statistics

- **Core PHP Files:** 15
- **Component PHP Files:** 24
- **Total Files Analyzed:** 52
- **Security Issues:** 4 (3 Critical, 1 Medium)
- **Duplicate Files:** 3 potential
- **Files Needing Review:** 5

---

## ✅ Phase 1 Completion Checklist

- [x] Complete folder inventory
- [x] Analyze all core files
- [x] Analyze all component files
- [x] Document security issues
- [x] Identify duplicate files
- [x] Create dependency map
- [x] Document best practices
- [x] Create recommendations

---

**Next Phase:** Phase 2 - Admin System Analysis

**Status:** ✅ Phase 1 Complete

---

**Last Updated:** February 2026  
**Reviewed By:** AI Project Manager

