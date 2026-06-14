(function () {
  const sidebar = document.getElementById('saSidebar');
  const overlay = document.getElementById('saOverlay');
  const toggle = document.getElementById('saSidebarToggle');
  const search = document.getElementById('saModuleSearch');
  const chips = document.querySelectorAll('.sa-chip');
  const cards = document.querySelectorAll('.sa-module-card');

  toggle?.addEventListener('click', () => {
    sidebar?.classList.toggle('is-open');
    overlay?.classList.toggle('is-visible');
  });

  overlay?.addEventListener('click', () => {
    sidebar?.classList.remove('is-open');
    overlay?.classList.remove('is-visible');
  });

  let activeGroup = 'all';

  function applyFilters() {
    const q = (search?.value || '').trim().toLowerCase();

    cards.forEach((card) => {
      const group = card.dataset.group || '';
      const text = (card.textContent || '').toLowerCase();
      let visible = activeGroup === 'all' || group === activeGroup;
      if (visible && q) {
        visible = text.includes(q);
      }
      card.style.display = visible ? '' : 'none';
    });
  }

  search?.addEventListener('input', applyFilters);

  chips.forEach((chip) => {
    chip.addEventListener('click', () => {
      chips.forEach((c) => c.classList.remove('is-active'));
      chip.classList.add('is-active');
      activeGroup = chip.dataset.filter || 'all';
      applyFilters();
    });
  });
})();
