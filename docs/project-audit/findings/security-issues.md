# Security Issues Identified

**Last Updated:** February 2026  
**Total Critical Issues:** 3  
**Total Medium Issues:** 1  
**Total Low Issues:** 1

---

## 🚨 Critical Security Issues

### 1. Hardcoded Database Credentials

**File:** `core/omr-connect.php`  
**Severity:** CRITICAL  
**Risk:** Database credentials exposed in source code

**Details:**
- Username: `metap8ok_myomr_admin`
- Password: `myomr@123` (weak password)
- Database: `metap8ok_myomr`

**Impact:**
- Anyone with code access can see credentials
- Weak password is easily guessable
- Credentials are in version control

**Fix:**
```php
// Use environment variables
$servername = $_ENV['DB_HOST'] ?? 'localhost:3306';
$username = $_ENV['DB_USER'] ?? '';
$password = $_ENV['DB_PASS'] ?? '';
$database = $_ENV['DB_NAME'] ?? '';
```

**Action Required:**
1. Move credentials to `.env` file
2. Add `.env` to `.gitignore`
3. Rotate database password immediately
4. Update connection file to use environment variables

---

### 2. Hardcoded Admin Password

**File:** `core/admin-config.php`  
**Severity:** CRITICAL  
**Risk:** Admin credentials exposed and weak default password

**Details:**
- Username: `admin` (default)
- Password: `Mylapore@21g` (weak, exposed)
- Password hash generated at runtime (should be pre-hashed)

**Impact:**
- Default password is weak
- Credentials visible in source code
- Password hash generation at runtime is inefficient

**Fix:**
```php
// Use environment variables
define('MYOMR_ADMIN_USERNAME', $_ENV['ADMIN_USER'] ?? 'admin');
define('MYOMR_ADMIN_PASSWORD_HASH', $_ENV['ADMIN_PASS_HASH'] ?? '');

// Pre-hash password: $ php -r "echo password_hash('YourStrongPassword', PASSWORD_DEFAULT);"
```

**Action Required:**
1. Generate strong admin password
2. Pre-hash password and store in `.env`
3. Move credentials to environment variables
4. Update admin login if password changed

---

### 3. Default/Placeholder Secret Key

**File:** `core/app-secrets.php`  
**Severity:** CRITICAL  
**Risk:** Weak secret key for event management tokens

**Details:**
- Secret: `'change-this-strong-secret-please-rotate'` (obvious placeholder)
- Used for: Event management token generation

**Impact:**
- Secret is easily guessable
- Event management tokens could be forged
- Security vulnerability in event system

**Fix:**
```php
// Generate strong secret: $ php -r "echo bin2hex(random_bytes(32));"
define('MYOMR_EVENTS_MANAGE_SECRET', $_ENV['EVENTS_SECRET'] ?? '');
```

**Action Required:**
1. Generate strong secret (32+ bytes)
2. Store in `.env` file
3. Rotate secret if already in production
4. Update all event token generation

---

## ⚠️ Medium Security Issues

### 4. Subscriber List in Web Directory

**File:** `components/subscribers.txt`  
**Severity:** MEDIUM  
**Risk:** Email addresses potentially accessible via web

**Details:**
- File location: `components/subscribers.txt`
- Contains: Email subscriber list
- Access: Potentially accessible via URL if not protected

**Impact:**
- Email addresses could be exposed
- Privacy concern for subscribers
- Potential spam/security risk

**Fix:**
- Move file outside web root (e.g., `data/subscribers.txt`)
- Add `.htaccess` protection if must remain in web directory
- Update all references to new location

**Action Required:**
1. Move file to secure location
2. Update all code references
3. Add protection if file must be in web directory

---

## 📋 Low Security Issues

### 5. File Extension Issues

**File:** `core/news-old-mahabalipuram-road`  
**Severity:** LOW  
**Risk:** Unclear file purpose, potential confusion

**Details:**
- File has no extension
- Purpose is unclear
- May be leftover from migration

**Impact:**
- Low security risk
- Code maintainability issue

**Fix:**
- Review file purpose
- Add appropriate extension if needed
- Delete if unused

**Action Required:**
1. Review file contents
2. Determine purpose
3. Delete if unused, or rename appropriately

---

## 🔒 General Security Recommendations

### Immediate Actions

1. **Create `.env` file:**
   ```env
   DB_HOST=localhost:3306
   DB_USER=metap8ok_myomr_admin
   DB_PASS=StrongUniquePassword123!
   DB_NAME=metap8ok_myomr
   
   ADMIN_USER=admin
   ADMIN_PASS_HASH=$2y$10$GeneratedHashHere...
   EVENTS_SECRET=Generated32ByteSecretHere...
   ```

2. **Add to `.gitignore`:**
   ```
   .env
   .env.local
   *.env
   ```

3. **Secure Session Configuration:**
   ```php
   session_set_cookie_params([
       'lifetime' => 0,
       'path' => '/',
       'domain' => '',
       'secure' => true,    // HTTPS only
       'httponly' => true,  // No JS access
       'samesite' => 'Strict'
   ]);
   ```

4. **Enable HTTPS Everywhere:**
   - Ensure SSL certificate is valid
   - Force HTTPS redirects
   - Use HSTS headers

5. **Implement Rate Limiting:**
   - For admin login attempts
   - For form submissions
   - For API endpoints

### Long-term Security Improvements

1. **Use Environment Variables:**
   - Move all secrets to `.env`
   - Never commit credentials
   - Use different credentials per environment

2. **Implement 2FA:**
   - Add two-factor authentication for admin
   - Use TOTP or SMS verification

3. **Regular Security Audits:**
   - Review code for vulnerabilities
   - Update dependencies
   - Scan for exposed secrets

4. **Implement Security Headers:**
   ```php
   header('X-Content-Type-Options: nosniff');
   header('X-Frame-Options: DENY');
   header('X-XSS-Protection: 1; mode=block');
   header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
   header('Content-Security-Policy: default-src \'self\'');
   ```

---

## 📊 Security Score

**Current Score:** 4/10 (Poor)

**Breakdown:**
- ✅ Input validation: 8/10 (Good)
- ✅ Password hashing: 9/10 (Excellent)
- ⚠️ Credential management: 2/10 (Critical issues)
- ✅ CSRF protection: 8/10 (Good)
- ✅ Error handling: 7/10 (Good)
- ⚠️ File security: 5/10 (Medium issues)
- ✅ SQL injection prevention: 8/10 (Good foundation)

**Target Score:** 8/10 (Good)

**Actions to Improve:**
1. Fix all critical issues → +2 points
2. Implement environment variables → +1 point
3. Add security headers → +1 point
4. Move subscriber file → +0.5 points

---

**Next Review:** After Phase 2 (Admin System Analysis)

---

**Priority Order:**
1. 🔴 **URGENT:** Fix database credentials (Issue #1)
2. 🔴 **URGENT:** Fix admin password (Issue #2)
3. 🔴 **URGENT:** Fix secret key (Issue #3)
4. 🟡 **HIGH:** Move subscriber file (Issue #4)
5. 🟢 **LOW:** Review file extension (Issue #5)

