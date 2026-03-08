/**
 * MyOMR Job Portal 2026 — Client-Side JS
 * AJAX search · Sort · Save jobs (session) · Job alerts · Spotlight nav
 */

(function () {
  'use strict';

  /* ── Helpers ───────────────────────────────────────────────── */
  const $ = id => document.getElementById(id);
  const qs = sel => document.querySelector(sel);
  const qsa = sel => document.querySelectorAll(sel);
  const spin = v => {
    const s = $('jobs-spinner');
    if (s) s.classList.toggle('show', v);
  };

  /* ── Debounce ──────────────────────────────────────────────── */
  function debounce(fn, ms) {
    let t;
    return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), ms); };
  }

  /* ── AJAX Search ───────────────────────────────────────────── */
  const searchForm = $('main-search-form');
  let abortCtrl = null;

  function buildQuery(form) {
    const fd = new FormData(form);
    const params = new URLSearchParams();
    for (const [k, v] of fd.entries()) {
      if (v.trim()) params.set(k, v.trim());
    }
    return params;
  }

  function renderJobs(html, count) {
    const grid = $('jobs-grid');
    const countEl = $('filter-job-count');
    if (grid) grid.innerHTML = html;
    if (countEl) countEl.textContent = count;
    // Animate new cards
    qsa('#jobs-grid .jp-card').forEach((c, i) => {
      c.style.animationDelay = (i * 0.04) + 's';
    });
  }

  async function fetchJobs(params) {
    if (abortCtrl) abortCtrl.abort();
    abortCtrl = new AbortController();
    spin(true);
    try {
      const r = await fetch('api/jobs.php?' + params.toString(), {
        signal: abortCtrl.signal,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      if (!r.ok) throw new Error('Network error');
      const d = await r.json();
      if (d.html !== undefined) {
        renderJobs(d.html, d.total);
        // Update pagination
        const pag = $('jobs-pagination');
        if (pag && d.pagination) pag.innerHTML = d.pagination;
      }
    } catch (e) {
      if (e.name !== 'AbortError') {
        console.warn('Job search error:', e);
      }
    } finally {
      spin(false);
    }
  }

  if (searchForm) {
    const debouncedSearch = debounce(() => {
      const params = buildQuery(searchForm);
      history.pushState({}, '', '?' + params.toString());
      fetchJobs(params);
    }, 350);

    // Live search on text inputs
    searchForm.querySelectorAll('input[type=text], select').forEach(el => {
      el.addEventListener('change', debouncedSearch);
    });
    searchForm.querySelectorAll('input[type=text]').forEach(el => {
      el.addEventListener('input', debounce(debouncedSearch, 600));
    });

    // Form submit
    searchForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const params = buildQuery(this);
      history.pushState({}, '', '?' + params.toString());
      fetchJobs(params);
    });
  }

  /* Quick-filter pills & sidebar radios also submit via AJAX */
  qsa('.jp-quick-filters a.jp-pill, .jp-sidebar a.jp-location-chip').forEach(link => {
    link.addEventListener('click', function (e) {
      const href = new URL(this.href);
      if (href.pathname === window.location.pathname ||
          href.pathname.includes('omr-local-job-listings')) {
        e.preventDefault();
        history.pushState({}, '', href.search);
        const params = new URLSearchParams(href.search);
        fetchJobs(params);
        // Update active state
        qsa('.jp-pill, .jp-location-chip').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
      }
    });
  });

  /* Sidebar radio changes auto-submit */
  qsa('[form="main-search-form"]').forEach(el => {
    el.addEventListener('change', () => {
      if (searchForm) {
        const params = buildQuery(searchForm);
        history.pushState({}, '', '?' + params.toString());
        fetchJobs(params);
      }
    });
  });

  /* ── Sort ──────────────────────────────────────────────────── */
  qsa('[data-sort]').forEach(btn => {
    btn.addEventListener('click', function () {
      qsa('[data-sort]').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      if (searchForm) {
        const params = buildQuery(searchForm);
        params.set('sort', this.dataset.sort);
        history.pushState({}, '', '?' + params.toString());
        fetchJobs(params);
      }
    });
  });

  /* ── Save Jobs (session) ────────────────────────────────────── */
  window.toggleSave = async function (btn, jobId) {
    const saved = btn.classList.contains('saved');
    btn.disabled = true;
    try {
      const r = await fetch('includes/save-job.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ job_id: jobId, action: saved ? 'remove' : 'save' })
      });
      const d = await r.json();
      if (d.ok) {
        btn.classList.toggle('saved', !saved);
        const icon = btn.querySelector('i');
        if (icon) icon.className = (!saved ? 'fas' : 'far') + ' fa-heart';
        showToast(!saved ? 'Job saved!' : 'Removed from saved.', !saved ? 'success' : 'info');
      }
    } catch (e) {
      console.warn('Save error:', e);
    } finally {
      btn.disabled = false;
    }
  };

  /* ── Toast notification ─────────────────────────────────────── */
  function showToast(msg, type = 'success') {
    const existing = qs('.jp-toast');
    if (existing) existing.remove();
    const t = document.createElement('div');
    t.className = 'jp-toast';
    t.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-1"></i> ${msg}`;
    Object.assign(t.style, {
      position: 'fixed', bottom: '1.5rem', right: '1.5rem',
      background: type === 'success' ? '#008552' : '#1a73e8',
      color: '#fff', padding: '.6rem 1.25rem', borderRadius: '8px',
      fontFamily: 'Poppins, sans-serif', fontSize: '.88rem', fontWeight: '600',
      boxShadow: '0 4px 16px rgba(0,0,0,.2)', zIndex: '9999',
      opacity: '0', transition: 'opacity .3s'
    });
    document.body.appendChild(t);
    requestAnimationFrame(() => { t.style.opacity = '1'; });
    setTimeout(() => {
      t.style.opacity = '0';
      setTimeout(() => t.remove(), 300);
    }, 2800);
  }

  /* ── Spotlight nav (featured carousel) ─────────────────────── */
  const spotPrev = $('spotPrev');
  const spotNext = $('spotNext');
  const spotTrack = $('spotlight-track');
  if (spotPrev && spotNext && spotTrack) {
    let spotIdx = 0;
    const cards = spotTrack.querySelectorAll('.col-md-4');
    const total = cards.length;

    function updateSpot() {
      cards.forEach((c, i) => {
        c.style.display = (i === spotIdx % total) ? '' : '';
      });
    }

    spotPrev.addEventListener('click', () => { spotIdx = Math.max(0, spotIdx - 1); updateSpot(); });
    spotNext.addEventListener('click', () => { spotIdx = Math.min(total - 1, spotIdx + 1); updateSpot(); });
  }

  /* ── Process Application resume preview ───────────────────── */
  const resumeInput = qs('#resume');
  if (resumeInput) {
    resumeInput.addEventListener('change', function () {
      const zone = qs('#resumeDropZone');
      const lbl  = qs('#resumeLabel');
      if (!this.files[0]) return;
      if (lbl) lbl.innerHTML = '<i class="fas fa-check-circle" style="color:#008552"></i> ' + this.files[0].name;
      if (zone) { zone.style.borderColor = '#008552'; zone.style.background = '#e8f5ed'; }
    });
  }

  /* ── Browser back/forward – re-fetch results ────────────────── */
  window.addEventListener('popstate', () => {
    const params = new URLSearchParams(window.location.search);
    if ($('jobs-grid')) fetchJobs(params);
  });

})();
