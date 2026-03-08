---
title: PRD-001 User Management & Authentication
status: Draft
owner: Hari Krishnan
project-manager: GPT-5 Codex (AI)
created: 18-11-2025
updated: 18-11-2025
version: 1.0
document-type: PRD
tags:
  - omr-marketplace
  - user-management
  - authentication
  - prd
---

# PRD-001: User Management & Authentication

> **Document Type:** Product Requirements Document (PRD)  
> **Status:** `Draft v1.0` | **Last Updated:** 18-11-2025  
> **Priority:** P0 (Critical - MVP)  
> **Related Documents:** [Marketplace Project Discussion](../../inbox/MARKETPLACE-PROJECT-DISCUSSION.md)

---

## 📋 Executive Summary

User Management & Authentication is the foundation of the marketplace platform. This PRD defines the requirements for user registration, authentication, profile management, and role-based access control for sellers, buyers, and administrators.

### Key Objectives

1. Enable seamless user registration for buyers and sellers
2. Provide secure authentication with email and phone verification
3. Support role-based access control (buyer, seller, admin)
4. Integrate with existing MyOMR authentication where applicable
5. Ensure GDPR-compliant user data management

---

## 🎯 User Stories

### US-001.1: User Registration
**As a** new user (buyer or seller),  
**I want to** register with email and phone number,  
**So that** I can access the marketplace features.

**Acceptance Criteria:**
- User can register with email, phone, and password
- Email validation and uniqueness check
- Phone number validation (Indian format)
- Password strength requirements enforced
- Registration confirmation email sent

### US-001.2: Email Verification
**As a** newly registered user,  
**I want to** verify my email address,  
**So that** my account is activated and secure.

**Acceptance Criteria:**
- Verification email sent immediately after registration
- Email contains unique verification link
- Link expires after 24 hours
- User can resend verification email
- Account remains inactive until email verified

### US-001.3: Phone OTP Verification
**As a** newly registered user,  
**I want to** verify my phone number via OTP,  
**So that** I can receive important notifications.

**Acceptance Criteria:**
- OTP sent to phone number after email verification
- OTP expires after 5 minutes
- User can request OTP resend (max 3 attempts)
- Phone number format validated (10-digit Indian mobile)
- Verification status tracked in database

### US-001.4: User Login
**As a** registered user,  
**I want to** login with email and password,  
**So that** I can access my marketplace account.

**Acceptance Criteria:**
- Login form accepts email and password
- Failed login attempts tracked (max 5 before lockout)
- Account lockout duration: 15 minutes
- Remember me functionality (30-day cookie)
- Session management with secure tokens

### US-001.5: Password Reset
**As a** user who forgot password,  
**I want to** reset my password via email,  
**So that** I can regain access to my account.

**Acceptance Criteria:**
- Password reset link sent to registered email
- Reset link expires after 1 hour
- User must enter new password twice
- Old password cannot be reused
- Password change confirmation email sent

### US-001.6: Profile Management
**As a** logged-in user,  
**I want to** update my profile information,  
**So that** my account details are current.

**Acceptance Criteria:**
- User can update name, phone, avatar
- Email changes require re-verification
- Phone changes require OTP verification
- Profile updates logged in audit trail
- Real-time validation feedback

---

## 📋 Functional Requirements

### FR-001.1: Registration System

**Priority:** P0 (Critical)  
**Status:** Planned

**Description:**
Users must be able to register for the marketplace with basic information. The registration flow supports both buyers and sellers, with seller-specific steps deferred to seller onboarding.

**Requirements:**
- Registration form fields:
  - Full Name (required, 2-100 characters)
  - Email Address (required, unique, valid email format)
  - Phone Number (required, unique, 10-digit Indian mobile)
  - Password (required, min 8 characters, must contain uppercase, lowercase, number)
  - Confirm Password (required, must match)
  - User Type Selection (Buyer/Seller) - radio buttons
  - Terms & Conditions checkbox (required)
- Server-side validation for all fields
- Real-time client-side validation feedback
- Duplicate email/phone detection
- Password strength indicator
- CAPTCHA on registration form (to prevent spam)

**Technical Specifications:**
- PHP form processing with sanitization
- MySQL database storage (`users` and `user_profiles` tables)
- Email sending via existing `core/mailer.php`
- OTP generation and SMS integration (future phase)

### FR-001.2: Email Verification

**Priority:** P0 (Critical)  
**Status:** Planned

**Description:**
All users must verify their email addresses before account activation. Unverified accounts have limited access.

**Requirements:**
- Verification email sent immediately after registration
- Email contains:
  - Unique verification token (UUID)
  - Expiration timestamp (24 hours)
  - Clear call-to-action button
  - Link to resend if expired
- Verification link format: `/marketplace/verify-email?token={uuid}`
- Account status updated to 'active' upon verification
- Unverified accounts can access login but see verification reminder banner
- Resend verification email functionality (max 3 per hour)

**Technical Specifications:**
- Verification token stored in `users` table (`email_verification_token`, `email_verified_at`)
- Token generation using PHP `random_bytes()` or UUID
- Email template stored in `marketplace/components/emails/verification.php`
- Verification status check middleware for protected routes

### FR-001.3: Phone OTP Verification

**Priority:** P1 (High - Post-MVP Enhancement)  
**Status:** Planned for Phase 2

**Description:**
Phone number verification via OTP ensures users can receive critical notifications and enables two-factor authentication.

**Requirements:**
- OTP sent after email verification (if phone verification enabled)
- OTP generation: 6-digit random number
- OTP expiration: 5 minutes
- OTP resend: Max 3 attempts per hour
- OTP verification page: `/marketplace/verify-phone`
- Phone verification status tracked in database

**Technical Specifications:**
- OTP stored temporarily in `users` table (`phone_otp`, `phone_otp_expires_at`)
- SMS integration via third-party service (Twilio/SMSGateway)
- Rate limiting on OTP requests
- Phone number format validation (Indian mobile: +91XXXXXXXXXX)

### FR-001.4: Authentication System

**Priority:** P0 (Critical)  
**Status:** Planned

**Description:**
Secure user authentication with session management, password hashing, and account security features.

**Requirements:**
- Login form: Email and Password fields
- Password hashing: PHP `password_hash()` with `PASSWORD_BCRYPT`
- Session management: PHP sessions with secure cookies
- Session timeout: 30 minutes inactivity
- Remember me: 30-day persistent cookie with secure token
- Failed login tracking:
  - Max 5 failed attempts
  - Account lockout: 15 minutes
  - Lockout count resets after successful login
- Login activity logging (IP address, timestamp, device info)
- Two-factor authentication: Optional (Phase 2)

**Technical Specifications:**
- Session storage in database (`user_sessions` table) or PHP filesystem
- Password verification using `password_verify()`
- CSRF protection on login form
- Rate limiting on login attempts (IP-based)
- Security headers: SameSite cookies, Secure flag, HttpOnly

### FR-001.5: Password Reset

**Priority:** P0 (Critical)  
**Status:** Planned

**Description:**
Users can reset forgotten passwords via secure email link.

**Requirements:**
- Password reset request form: Email input
- Reset link sent to registered email (if exists)
- Security: No indication if email exists (prevent enumeration)
- Reset link format: `/marketplace/reset-password?token={uuid}`
- Link expiration: 1 hour
- Reset form: New password and confirm password
- Password validation: Same rules as registration
- Old password cannot be reused (last 3 passwords tracked)
- Password change confirmation email sent

**Technical Specifications:**
- Reset token stored in `users` table (`password_reset_token`, `password_reset_expires_at`)
- Token generation: Cryptographically secure random token
- Token single-use (deleted after successful reset)
- Password history tracking (optional, Phase 2)

### FR-001.6: Profile Management

**Priority:** P1 (High)  
**Status:** Planned

**Description:**
Users can view and update their profile information through a dedicated profile page.

**Requirements:**
- Profile page accessible from user menu
- Editable fields:
  - Full Name (required)
  - Email Address (editable, requires re-verification if changed)
  - Phone Number (editable, requires OTP if changed)
  - Profile Avatar (image upload, max 2MB, JPG/PNG)
  - Bio/Description (optional, max 500 characters for buyers)
- Read-only fields:
  - Account Type (Buyer/Seller)
  - Registration Date
  - Last Login Date
- Profile update requires password confirmation for email/phone changes
- Real-time validation feedback
- Update success/error notifications

**Technical Specifications:**
- Profile data stored in `user_profiles` table
- Image upload handling with validation
- Image storage: `marketplace/uploads/avatars/`
- Image optimization: Resize to 200x200px, compress
- Update timestamp tracking
- Audit log for sensitive changes (email/phone)

### FR-001.7: Role-Based Access Control (RBAC)

**Priority:** P0 (Critical)  
**Status:** Planned

**Description:**
Users have different roles (buyer, seller, admin) with appropriate access levels.

**Requirements:**
- User roles:
  - `buyer`: Can browse, save favorites, send inquiries
  - `seller`: All buyer features + create listings, manage seller account
  - `admin`: Full system access, moderation, analytics
- Role stored in `users.role` field (ENUM)
- Role assignment:
  - Default: `buyer` at registration
  - `seller`: Assigned after seller account creation
  - `admin`: Manual assignment by super admin
- Access control:
  - Routes protected by role checks
  - API endpoints verify user role
  - UI elements conditionally rendered based on role
- Role change logging (audit trail)

**Technical Specifications:**
- Role checking middleware/functions
- Session includes role information
- Protected routes defined in `.htaccess` or PHP routing
- Admin routes require additional `admin_logged_in` session check

---

## 🔒 Security Requirements

### SR-001.1: Data Protection
- All passwords hashed using bcrypt (cost factor 12)
- Sensitive data encrypted at rest (database)
- HTTPS enforced for all authentication endpoints
- SQL injection prevention: Prepared statements only
- XSS prevention: All user input sanitized and escaped

### SR-001.2: Session Security
- Session ID regeneration on login
- Secure, HttpOnly, SameSite cookies
- Session timeout: 30 minutes inactivity
- Concurrent session limits (optional, Phase 2)

### SR-001.3: Account Security
- Failed login attempt tracking and lockout
- Password strength requirements enforced
- Password reset token security (cryptographically random, single-use)
- Email verification required for account activation
- Security event logging (login, password changes, email changes)

### SR-001.4: Privacy & Compliance
- GDPR compliance: User data export and deletion
- Privacy policy acceptance required at registration
- Terms of service acceptance required at registration
- Data retention policies defined
- User consent management

---

## 🗄️ Database Schema

### `users` Table
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(191) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('buyer', 'seller', 'admin') DEFAULT 'buyer',
    status ENUM('active', 'inactive', 'suspended', 'deleted') DEFAULT 'inactive',
    email_verified_at DATETIME NULL,
    email_verification_token VARCHAR(100) NULL,
    phone_verified_at DATETIME NULL,
    phone_otp VARCHAR(6) NULL,
    phone_otp_expires_at DATETIME NULL,
    failed_login_attempts INT DEFAULT 0,
    locked_until DATETIME NULL,
    last_login_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### `user_profiles` Table
```sql
CREATE TABLE user_profiles (
    user_id INT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    phone VARCHAR(20) UNIQUE NULL,
    whatsapp_opt_in TINYINT(1) DEFAULT 0,
    avatar_url VARCHAR(255) NULL,
    bio TEXT NULL,
    address JSON NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### `user_sessions` Table (Optional)
```sql
CREATE TABLE user_sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    last_activity DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 🎨 UI/UX Requirements

### UR-001.1: Registration Form
- Clean, single-page form layout
- Real-time validation feedback (green checkmarks, error messages)
- Password strength indicator (visual bar)
- Mobile-responsive design
- Loading states during submission
- Success message with next steps

### UR-001.2: Login Form
- Simple, focused design
- Email and password fields
- "Remember me" checkbox
- "Forgot password?" link
- Social login options (optional, Phase 2)
- Error messages: Generic (don't reveal if email exists)

### UR-001.3: Profile Page
- Tabbed interface: Personal Info, Security, Preferences
- Avatar upload with preview
- Form validation feedback
- Success/error toast notifications
- Mobile-optimized layout

---

## 🧪 Testing Requirements

### TR-001.1: Unit Tests
- Password hashing and verification
- Email validation functions
- Phone number validation
- Token generation and expiration
- Role-based access checks

### TR-001.2: Integration Tests
- Complete registration flow
- Email verification flow
- Login/logout flow
- Password reset flow
- Profile update flow

### TR-001.3: Security Tests
- SQL injection attempts
- XSS payload injection
- CSRF protection verification
- Brute force protection
- Session hijacking prevention

### TR-001.4: User Acceptance Tests
- First-time user registration
- Existing user login
- Password recovery scenario
- Profile update workflow
- Mobile device testing

---

## 📊 Success Metrics

### Key Performance Indicators (KPIs)
- **Registration Completion Rate**: Target >80%
- **Email Verification Rate**: Target >90% within 24 hours
- **Login Success Rate**: Target >95%
- **Password Reset Success Rate**: Target >85%
- **Average Registration Time**: Target <2 minutes

### Monitoring & Analytics
- Track registration funnel drop-off points
- Monitor failed login attempts (fraud detection)
- Track verification email delivery rates
- Measure profile completion rates
- Monitor account security events

---

## 🗓️ Implementation Timeline

### Phase 1: MVP (Weeks 1-4)
- ✅ User registration with email
- ✅ Email verification
- ✅ Login/logout
- ✅ Password reset
- ✅ Basic profile management
- ✅ Role-based access control

### Phase 2: Enhancements (Weeks 5-8)
- Phone OTP verification
- Remember me functionality
- Enhanced profile features
- Security audit logs
- Two-factor authentication (optional)

---

## 📝 Open Questions & Decisions

### Decisions Made
- ✅ Email verification required before account activation
- ✅ Phone OTP verification deferred to Phase 2
- ✅ Role-based access control implemented at database level
- ✅ Session management using PHP sessions (not JWT for MVP)

### Open Questions
- [ ] Integrate with existing MyOMR authentication system?
- [ ] Social login (Google/Facebook) in MVP or Phase 2?
- [ ] Two-factor authentication requirement for sellers?
- [ ] Account deletion vs. soft delete (GDPR compliance)?

---

## 📎 Related Documents

- [Marketplace Project Discussion](../../inbox/MARKETPLACE-PROJECT-DISCUSSION.md)
- [Seller Management PRD](PRD-002-Seller-Management.md) (Next)
- [Database Schema Documentation](../../../data-backend/DATABASE_STRUCTURE.md)

---

**Last Updated:** 18-11-2025  
**Next Review:** Upon stakeholder feedback  
**Document Owner:** Project Manager (GPT-5 Codex)




