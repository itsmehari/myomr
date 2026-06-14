  </main>
  <footer class="sa-footer">
    <span>MyOMR Superadmin · <?= date('Y') ?></span>
    <span class="text-muted">Unified admin layout</span>
  </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
<?php if (!empty($extraScripts)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
<script src="/superadmin/assets/superadmin-charts.js" defer></script>
<?php endif; ?>
<script src="/superadmin/assets/superadmin-dashboard.js" defer></script>
</body>
</html>
