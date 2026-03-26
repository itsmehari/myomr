/**
 * GA4 affiliate link clicks — bind .js-affiliate-click (Amazon spotlight).
 */
(function () {
  document.querySelectorAll('.js-affiliate-click').forEach(function (link) {
    link.addEventListener('click', function () {
      if (typeof gtag !== 'function') return;
      gtag('event', 'affiliate_link_click', {
        event_category: 'Affiliate',
        event_label: this.getAttribute('data-affiliate-id') || 'amazon-affiliate',
        affiliate_network: this.getAttribute('data-affiliate-network') || 'amazon',
        affiliate_position: this.getAttribute('data-affiliate-position') || '',
        article_title: this.getAttribute('data-affiliate-article') || ''
      });
    });
  });
})();
