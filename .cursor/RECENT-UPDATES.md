# Project recent updates and changes

Summary of notable changes for deployment and context. Keep this updated when shipping features or refactors.

---

## March 2026

### WhatsApp community group CTA rollout

**Shipped:** "Join our WhatsApp group" for connectivity and updates in multiple locations.

| Change | Location |
|--------|----------|
| Central constant for group URL | `core/include-path.php` — `MYOMR_WHATSAPP_GROUP_URL` |
| Hero CTA | `index.php` — "Join WhatsApp Group" button next to "Join the Community" |
| Article page | `local-news/article.php` — compact CTA after share bar; community section link uses constant |
| Job detail sidebar | `omr-local-job-listings/job-detail-omr.php` — "Join Our WhatsApp Group" block after Share This Job |
| Footer | `components/footer.php` — WhatsApp icon in Follow us; `assets/css/footer.css` — `.footer-social__link--wa` |

**Deploy checklist (6 files):**

- [ ] `core/include-path.php`
- [ ] `index.php`
- [ ] `local-news/article.php`
- [ ] `omr-local-job-listings/job-detail-omr.php`
- [ ] `components/footer.php`
- [ ] `assets/css/footer.css`

**Learnings:** See [.cursor/LEARNINGS.md](.cursor/LEARNINGS.md).  
**Skill reference:** [.cursor/skills/myomr-project/SKILL.md](.cursor/skills/myomr-project/SKILL.md) — WhatsApp community CTA section.

### Facebook group CTA rollout

**Shipped:** "Join our Facebook group" CTAs across homepage, article detail, job detail, and footer using a central constant + fallback pattern.

| Change | Location |
|--------|----------|
| Central constant for Facebook group URL | `core/include-path.php` — `MYOMR_FACEBOOK_GROUP_URL` |
| Hero CTA | `index.php` — "Join Facebook Group" button next to existing community CTAs |
| Article page | `local-news/article.php` — compact CTA near share bar/WhatsApp CTA line |
| Job detail sidebar | `omr-local-job-listings/job-detail-omr.php` — "Join Our Facebook Group" block after WhatsApp community block |
| Footer | `components/footer.php` — Facebook group icon/link in Follow us; `assets/css/footer.css` — `.footer-social__link--fb-group` |

**Deploy checklist (6 files):**

- [ ] `core/include-path.php`
- [ ] `index.php`
- [ ] `local-news/article.php`
- [ ] `omr-local-job-listings/job-detail-omr.php`
- [ ] `components/footer.php`
- [ ] `assets/css/footer.css`

**Docs updated:**

- [ ] `.cursor/LEARNINGS.md`
- [ ] `.cursor/RECENT-UPDATES.md`
- [ ] `.cursor/skills/myomr-project/SKILL.md`
