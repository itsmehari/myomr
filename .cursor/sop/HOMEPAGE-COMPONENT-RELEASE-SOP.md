# Homepage Component Release SOP (MyOMR)

**Version:** 1.0  
**Last reviewed:** March 2026  
**Owner:** Engineering + Product

---

## 1. Scope

Safe rollout of new or changed **homepage sections** in [index.php](../../index.php): data fetching, conditional rendering, CSS, and ads.

---

## 2. Principles

| Topic | Rule |
|-------|------|
| Data guards | Wrap promo sections in `if (!empty($data))` so empty DB does not break layout |
| CSS | Homepage-specific styles: set `$omr_css_homepage = true` before head includes; use [assets/css/homepage-myomr.css](../../assets/css/homepage-myomr.css) or linked bundle |
| Ads | Use `omr_ad_slot()` / row patterns per [AD-BANNER-SLOTS-REGISTRY-SOP.md](AD-BANNER-SLOTS-REGISTRY-SOP.md) |
| Analytics | Single `components/analytics.php` in head |

---

## 3. Preconditions

- Local or staging view of homepage with production-like data when possible
- Awareness of existing sections: jobs banner, events widget, news bulletin, featured news, Buy & Sell row, etc.

---

## 4. Procedure

1. **Fetch data at top** of `index.php` for any new section (mysqli prepared statements).
2. **Add section markup** in logical order (hero → primary content → secondary).
3. **Guard** the section: `<?php if (!empty($variable)): ?> ... <?php endif; ?>`
4. **Styles:** Add BEM-style or scoped classes; place rules in `homepage-myomr.css` (or file already loaded when `$omr_css_homepage`).
5. **Mobile:** Verify breakpoints; max container width 1280px per project standard.
6. **Performance:** Avoid N+1 queries; cache small lists if needed.

---

## 5. Modal / footer interactions

If homepage triggers CTA modals: Bootstrap 5 must load where modals are used; see LEARNINGS on `modal-cta.php`. Do not duplicate Bootstrap.

---

## 6. Validation checklist

- [ ] Homepage loads with **empty** optional datasets (no notices)
- [ ] Homepage loads with **full** datasets
- [ ] Lighthouse spot-check (LCP, CLS) on hero if images change
- [ ] footer.css and analytics present

---

## 7. Rollback

Revert `index.php` + CSS commit; redeploy per [CPANEL-DEPLOYMENT-SOP.md](CPANEL-DEPLOYMENT-SOP.md).

---

## 8. Related references

- [AGENTS.md](../../AGENTS.md) — Homepage composition
- [.cursor/LEARNINGS.md](../LEARNINGS.md) — CTA modals / homepage ad row
