(function () {
  function waitForChart(callback) {
    if (typeof Chart !== 'undefined') {
      callback();
      return;
    }
    window.setTimeout(function () { waitForChart(callback); }, 50);
  }

  function baseOptions() {
    return {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#0f172a',
          padding: 10,
          cornerRadius: 8,
        },
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { font: { size: 10 }, maxRotation: 0, autoSkip: true, maxTicksLimit: 6 },
        },
        y: {
          beginAtZero: true,
          grid: { color: 'rgba(148, 163, 184, 0.2)' },
          ticks: { precision: 0, font: { size: 10 } },
        },
      },
    };
  }

  function lineChart(canvasId, dataset, color) {
    var el = document.getElementById(canvasId);
    if (!el || !dataset) return;

    new Chart(el, {
      type: 'line',
      data: {
        labels: dataset.labels || [],
        datasets: [{
          label: dataset.label || '',
          data: dataset.values || [],
          borderColor: color,
          backgroundColor: color + '22',
          fill: true,
          tension: 0.35,
          pointRadius: 2,
          pointHoverRadius: 4,
          borderWidth: 2,
        }],
      },
      options: baseOptions(),
    });
  }

  function initOverviewCharts() {
    var data = window.saOverviewCharts;
    if (!data) return;

    lineChart('saChartJobs', data.jobs, data.jobs.color);
    lineChart('saChartSeekers', data.seekers, data.seekers.color);
    lineChart('saChartArticles', data.articles, data.articles.color);
    lineChart('saChartEvents', data.events, data.events.color);
    lineChart('saChartClassified', data.classified, data.classified.color);
    lineChart('saChartMarketplace', data.marketplace, data.marketplace.color);
  }

  function initInsightCharts() {
    var payload = window.saInsightCharts;
    if (!payload) return;

    var trendEl = document.getElementById('saInsightTrendChart');
    if (trendEl) {
      new Chart(trendEl, {
        type: 'bar',
        data: {
          labels: payload.trend.labels || [],
          datasets: [{
            label: 'Records',
            data: payload.trend.values || [],
            backgroundColor: payload.trendColor + 'cc',
            borderRadius: 6,
            borderSkipped: false,
          }],
        },
        options: baseOptions(),
      });
    }

    var statusEl = document.getElementById('saInsightStatusChart');
    if (statusEl && payload.status) {
      new Chart(statusEl, {
        type: 'doughnut',
        data: {
          labels: payload.status.labels || [],
          datasets: [{
            data: payload.status.values || [],
            backgroundColor: ['#0d7a42', '#2563eb', '#a16207', '#7c3aed', '#dc2626', '#64748b', '#0369a1'],
            borderWidth: 0,
          }],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '62%',
          plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } },
          },
        },
      });
    }
  }

  waitForChart(function () {
    initOverviewCharts();
    initInsightCharts();
  });
})();
