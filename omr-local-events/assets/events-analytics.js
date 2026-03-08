// MyOMR Events - Google Analytics Event Helpers
(function(){
  function send(action, params){
    if (typeof window.gtag === 'function') {
      window.gtag('event', action, params || {});
    }
  }

  // Expose minimal API
  window.MyOMREventsAnalytics = {
    filterUsed: function(label){ send('events_filter', { event_category: 'Events', event_label: label || '' }); },
    viewClicked: function(slug){ send('event_view', { event_category: 'Events', event_label: slug || '' }); },
    mapClicked: function(slug){ send('event_map', { event_category: 'Events', event_label: slug || '' }); },
    ticketClicked: function(slug){ send('event_ticket', { event_category: 'Events', event_label: slug || '' }); },
    shareClicked: function(channel, slug){ send('event_share', { event_category: 'Events', event_label: channel + '|' + (slug||'') }); },
    addToCalendar: function(slug){ send('event_calendar_add', { event_category: 'Events', event_label: slug || '' }); },
    icsDownloaded: function(slug){ send('event_ics_download', { event_category: 'Events', event_label: slug || '' }); },
    submissionStart: function(){ send('event_submit_start', { event_category: 'Events' }); },
    submissionSubmit: function(){ send('event_submit_attempt', { event_category: 'Events' }); },
    submissionSuccess: function(id){ send('event_submit_success', { event_category: 'Events', value: id||0 }); }
  };

  // Auto-bind common hooks
  document.addEventListener('DOMContentLoaded', function(){
    // Filter form
    var filterForm = document.querySelector('form.dashboard-toolbar');
    if (filterForm) {
      filterForm.addEventListener('submit', function(){
        var q = new URLSearchParams(new FormData(filterForm)).toString();
        window.MyOMREventsAnalytics.filterUsed(q);
      });
    }

    // Track elements with data-analytics attributes
    document.querySelectorAll('[data-analytics]').forEach(function(el){
      el.addEventListener('click', function(){
        var type = el.getAttribute('data-analytics');
        var label = el.getAttribute('data-analytics-label') || '';
        if (type && window.MyOMREventsAnalytics[type]) {
          window.MyOMREventsAnalytics[type](label);
        }
      });
    });
  });
})();


