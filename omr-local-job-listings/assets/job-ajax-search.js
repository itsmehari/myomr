/**
 * MyOMR Job Portal — AJAX Live Search
 *
 * Intercepts the search / filter form submissions and fetches results from
 * api/jobs.php without a full page reload. URL is kept in sync via
 * history.pushState so pages remain linkable and crawlable.
 */

(function () {
  'use strict';

  // ── Config ──────────────────────────────────────────────────────────────────
  const API_URL        = '/omr-local-job-listings/api/jobs.php';
  const GRID_ID        = 'jobs-grid';
  const SUMMARY_ID     = 'jobs-summary';
  const PAGINATION_ID  = 'jobs-pagination';
  const SPINNER_ID     = 'jobs-spinner';
  const HERO_COUNT_ID  = 'hero-job-count';
  const FILTER_COUNT_ID = 'filter-job-count';

  // ── Helpers ─────────────────────────────────────────────────────────────────

  /** Escape HTML entities for safe insertion into innerHTML */
  function esc(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  /** Show / hide the loading spinner */
  function setLoading(on) {
    const spinner = document.getElementById(SPINNER_ID);
    const grid    = document.getElementById(GRID_ID);
    if (spinner) spinner.style.display = on ? 'flex' : 'none';
    if (grid)    grid.style.opacity    = on ? '0.4' : '1';
  }

  // ── Card renderer ────────────────────────────────────────────────────────────

  function logoHTML(job) {
    if (job.company_logo) {
      return `<img src="${esc(job.company_logo)}" alt="${esc(job.company_name)} logo"
                   class="company-logo-img" width="48" height="48"
                   style="object-fit:contain;border-radius:6px;">`;
    }
    const initial = (job.company_name || 'C').charAt(0).toUpperCase();
    return `<div class="company-logo-placeholder" aria-hidden="true"
                 style="width:48px;height:48px;border-radius:6px;background:#e7f5e7;
                        display:flex;align-items:center;justify-content:center;
                        font-weight:700;font-size:1.2rem;color:#008552;">
              ${esc(initial)}
            </div>`;
  }

  function badgesHTML(job) {
    let out = `<span class="badge-modern badge-modern-primary">
                  <i class="fas fa-briefcase me-1"></i>${esc(job.job_type || 'Full-time')}
               </span>`;
    if (job.category_name) {
      out += `<span class="badge-modern badge-modern-primary">
                <i class="fas fa-tag me-1"></i>${esc(job.category_name)}
              </span>`;
    }
    if (job.salary_display) {
      out += `<span class="badge-modern badge-modern-success">
                <i class="fas fa-rupee-sign me-1"></i>${esc(job.salary_display)}
              </span>`;
    }
    if (job.is_remote) {
      out += `<span class="badge-modern badge-modern-info">
                <i class="fas fa-wifi me-1"></i>Remote
              </span>`;
    }
    if (job.experience_level && job.experience_level !== 'Any') {
      out += `<span class="badge-modern badge-modern-secondary">
                <i class="fas fa-user me-1"></i>${esc(job.experience_level)}
              </span>`;
    }
    return out;
  }

  function buildCard(job) {
    const featuredBadge = job.featured
      ? `<span class="badge bg-warning text-dark position-absolute top-0 end-0 m-3">
           <i class="fas fa-star me-1"></i>Featured
         </span>`
      : '';

    const deadline = job.deadline
      ? `<span class="ms-3"><i class="fas fa-clock me-1"></i>Apply by ${esc(job.deadline)}</span>`
      : '';

    const appsCount = job.applications_count > 0
      ? `<span class="ms-2 text-muted small"><i class="fas fa-users me-1"></i>${job.applications_count} applied</span>`
      : '';

    return `
      <div class="col-lg-6 mb-4">
        <article class="job-card job-card-modern h-100 border rounded shadow-sm p-4 position-relative">
          ${featuredBadge}
          <header class="job-header mb-3">
            <div class="d-flex align-items-start gap-3 mb-2">
              ${logoHTML(job)}
              <div class="flex-grow-1">
                <h3 class="h5 mb-1 job-title">
                  <a href="${esc(job.url)}" class="text-decoration-none text-dark">${esc(job.title)}</a>
                </h3>
                <div class="company-info">
                  <span class="text-primary fw-semibold">${esc(job.company_name)}</span>
                  <span class="text-muted mx-2">•</span>
                  <span class="text-muted">
                    <i class="fas fa-map-marker-alt me-1"></i>${esc(job.location)}
                  </span>
                </div>
              </div>
            </div>
            <div class="job-meta mt-2">${badgesHTML(job)}</div>
          </header>
          <div class="job-description mb-3">
            <p class="text-muted mb-0">${esc(job.description)}</p>
          </div>
          <footer class="job-footer d-flex justify-content-between align-items-center">
            <div class="text-muted small">
              ${job.time_ago ? `<i class="fas fa-clock me-1"></i>${esc(job.time_ago)}` : ''}
              ${deadline}
              ${appsCount}
            </div>
            <a href="${esc(job.url)}" class="btn btn-primary btn-sm">
              <i class="fas fa-eye me-1"></i>View Details
            </a>
          </footer>
        </article>
      </div>`;
  }

  function renderJobs(jobs) {
    const grid = document.getElementById(GRID_ID);
    if (!grid) return;
    if (!jobs.length) {
      grid.innerHTML = `
        <div class="col-12 text-center py-5">
          <i class="fas fa-search fa-3x text-muted mb-3 d-block"></i>
          <h3 class="h4 mb-3">No Jobs Found</h3>
          <p class="text-muted mb-4">Try adjusting your search criteria or
             <a href="/omr-local-job-listings/">browse all jobs</a>.</p>
        </div>`;
      return;
    }
    grid.innerHTML = jobs.map(buildCard).join('');
  }

  // ── Pagination renderer ───────────────────────────────────────────────────────

  function buildPaginationLink(page, label, disabled, active, params) {
    if (disabled) {
      return `<li class="page-item disabled"><span class="page-link">${label}</span></li>`;
    }
    if (active) {
      return `<li class="page-item active" aria-current="page"><span class="page-link">${page}</span></li>`;
    }
    const p = new URLSearchParams(params);
    p.set('page', page);
    return `<li class="page-item">
              <a class="page-link ajax-page-link" href="?${p.toString()}" data-page="${page}">${label}</a>
            </li>`;
  }

  function renderPagination(data, params) {
    const container = document.getElementById(PAGINATION_ID);
    if (!container) return;
    if (data.total_pages <= 1) { container.innerHTML = ''; return; }

    const cp = data.page;
    const tp = data.total_pages;
    const start = Math.max(1, cp - 3);
    const end   = Math.min(tp, cp + 3);
    let html = '<nav aria-label="Job listings pagination"><ul class="pagination justify-content-center flex-wrap">';

    html += buildPaginationLink(cp - 1, '&laquo; Prev', cp <= 1,    false, params);
    if (start > 1) {
      html += buildPaginationLink(1, '1', false, false, params);
      if (start > 2) html += '<li class="page-item disabled"><span class="page-link">…</span></li>';
    }
    for (let i = start; i <= end; i++) {
      html += buildPaginationLink(i, String(i), false, i === cp, params);
    }
    if (end < tp) {
      if (end < tp - 1) html += '<li class="page-item disabled"><span class="page-link">…</span></li>';
      html += buildPaginationLink(tp, String(tp), false, false, params);
    }
    html += buildPaginationLink(cp + 1, 'Next &raquo;', cp >= tp, false, params);
    html += '</ul></nav>';
    container.innerHTML = html;

    // Bind click on new pagination links
    container.querySelectorAll('.ajax-page-link').forEach(function (a) {
      a.addEventListener('click', function (e) {
        e.preventDefault();
        doSearch(collectParams(parseInt(this.dataset.page, 10)));
      });
    });
  }

  // ── Summary updater ───────────────────────────────────────────────────────────

  function updateSummary(total, page, perPage) {
    const el = document.getElementById(SUMMARY_ID);
    if (el) {
      const from = ((page - 1) * perPage) + 1;
      const to   = Math.min(page * perPage, total);
      el.textContent = total
        ? `Showing ${from}–${to} of ${total.toLocaleString()} jobs`
        : 'No jobs found';
    }
    const heroEl = document.getElementById(HERO_COUNT_ID);
    if (heroEl) heroEl.textContent = total.toLocaleString();
    const filterEl = document.getElementById(FILTER_COUNT_ID);
    if (filterEl) filterEl.textContent = total.toLocaleString();
  }

  // ── Params collector ─────────────────────────────────────────────────────────

  function collectParams(page) {
    const form   = document.querySelector('form[data-ajax-search]') ||
                   document.querySelector('.search-form');
    const filter = document.querySelector('form[data-ajax-filter]') ||
                   document.querySelector('.advanced-filters');

    const p = {};
    const readForm = function (f) {
      if (!f) return;
      new FormData(f).forEach(function (val, key) {
        if (val && val !== '') p[key] = val;
      });
    };
    readForm(form);
    readForm(filter);
    p.page = page || 1;
    return p;
  }

  // ── Core search function ──────────────────────────────────────────────────────

  let _abortController = null;

  function doSearch(params) {
    // Cancel any in-flight request
    if (_abortController) _abortController.abort();
    _abortController = new AbortController();

    setLoading(true);
    window.scrollTo({ top: 0, behavior: 'smooth' });

    const qs = new URLSearchParams(params).toString();

    // Update browser URL so the page is bookmarkable / shareable
    history.pushState({ params }, '', '?' + qs);

    fetch(API_URL + '?' + qs, { signal: _abortController.signal })
      .then(function (r) {
        if (!r.ok) throw new Error('Server error ' + r.status);
        return r.json();
      })
      .then(function (data) {
        renderJobs(data.jobs);
        renderPagination(data, params);
        updateSummary(data.total, data.page, data.per_page);
        setLoading(false);
      })
      .catch(function (err) {
        if (err.name !== 'AbortError') {
          setLoading(false);
          const grid = document.getElementById(GRID_ID);
          if (grid) {
            grid.innerHTML = `<div class="col-12 text-center py-4 text-danger">
              <i class="fas fa-exclamation-triangle me-2"></i>
              Failed to load jobs. <a href="?${qs}">Reload page</a>.
            </div>`;
          }
        }
      });
  }

  // ── Bind forms ───────────────────────────────────────────────────────────────

  function bindForms() {
    const forms = document.querySelectorAll(
      'form[data-ajax-search], form[data-ajax-filter], .search-form, .advanced-filters'
    );

    forms.forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        doSearch(collectParams(1));
      });

      // Auto-search on select change (category, job_type dropdowns)
      form.querySelectorAll('select').forEach(function (sel) {
        sel.addEventListener('change', function () {
          doSearch(collectParams(1));
        });
      });
    });
  }

  // ── Handle browser back/forward ───────────────────────────────────────────────

  window.addEventListener('popstate', function (e) {
    const params = e.state && e.state.params
      ? e.state.params
      : Object.fromEntries(new URLSearchParams(location.search));
    doSearch(params);
  });

  // ── Init ─────────────────────────────────────────────────────────────────────

  document.addEventListener('DOMContentLoaded', function () {
    bindForms();

    // If the page was loaded with filter params in the URL, do an initial AJAX
    // fetch to keep the card rendering consistent with the JS template.
    const initialParams = Object.fromEntries(new URLSearchParams(location.search));
    const hasFilters = Object.keys(initialParams).some(function (k) {
      return k !== 'page' && initialParams[k] !== '';
    });
    if (hasFilters) {
      doSearch(initialParams);
    }
  });

})();
