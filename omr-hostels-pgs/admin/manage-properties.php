<?php
/**
 * Admin - Manage Properties
 */

require_once __DIR__ . '/../../admin/_bootstrap.php';
requireAdmin();

$title = 'Manage Properties';

require_once __DIR__ . '/../../core/omr-connect.php';
global $conn;

// Get all properties
$query = "SELECT h.*, p.full_name as owner_name 
          FROM hostels_pgs h 
          LEFT JOIN property_owners p ON h.owner_id = p.id 
          ORDER BY h.created_at DESC";
$result = $conn->query($query);
$properties = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Get stats
$total = count($properties);
$active = count(array_filter($properties, fn($p) => $p['status'] === 'active'));
$pending = count(array_filter($properties, fn($p) => $p['status'] === 'pending'));

// Helpers for filters
$types = array_values(array_unique(array_filter(array_map(fn($p) => $p['property_type'], $properties))));
sort($types, SORT_NATURAL | SORT_FLAG_CASE);

$statuses = array_values(array_unique(array_filter(array_map(fn($p) => $p['status'], $properties))));
sort($statuses, SORT_NATURAL | SORT_FLAG_CASE);

$localities = array_map(fn($p) => $p['locality'] ? $p['locality'] : 'OMR Corridor', $properties);
$localities = array_values(array_unique($localities));
sort($localities, SORT_NATURAL | SORT_FLAG_CASE);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - MyOMR Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --admin-bg: #f4f7fb;
            --admin-card: #ffffff;
            --admin-border: #e3e8ee;
            --admin-accent: #118a44;
            --admin-accent-soft: rgba(17, 138, 68, 0.08);
        }
        body {
            background: var(--admin-bg);
        }
        .admin-shell {
            min-height: 100vh;
        }
        .admin-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.75rem 1.5rem 1rem;
            background: var(--admin-card);
            border-bottom: 1px solid var(--admin-border);
            position: sticky;
            top: 0;
            z-index: 1020;
        }
        .admin-header h2 {
            margin: 0;
            font-weight: 700;
            color: #133b2c;
        }
        .admin-header .stats-pill {
            display: flex;
            gap: 0.75rem;
        }
        .stats-chip {
            padding: 0.5rem 0.9rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid transparent;
            color: #133b2c;
            background: var(--admin-accent-soft);
        }
        .stats-chip.success {border-color: #badbcc;color: #0f5132;background: #d1e7dd;}
        .stats-chip.warning {border-color: #fde68a;color: #8a5200;background: #fff3cd;}
        .stats-chip.secondary {border-color: #d0d5dd;color: #364152;background: #f5f6f8;}

        .admin-toolbar {
            background: var(--admin-card);
            border-radius: 12px;
            border: 1px solid var(--admin-border);
            padding: 1.25rem 1.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
            margin-bottom: 1.25rem;
        }
        .admin-toolbar .form-select,
        .admin-toolbar .form-control {
            min-width: 160px;
        }
        .admin-toolbar .density-toggle .btn {
            border-radius: 999px;
            padding-inline: 0.8rem;
        }
        .bulk-actions .btn {
            border-radius: 999px;
        }
        .admin-card {
            background: var(--admin-card);
            border-radius: 12px;
            border: 1px solid var(--admin-border);
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }
        .admin-table {
            margin: 0;
        }
        .admin-table thead th {
            position: sticky;
            top: 0;
            background: #f8fafc;
            border-bottom: 1px solid var(--admin-border);
            font-weight: 600;
            color: #475467;
            z-index: 5;
        }
        .admin-table tbody tr {
            transition: transform 0.12s ease, background 0.12s ease;
        }
        .admin-table tbody tr:hover {
            background: #f6fff9;
        }
        .badge-soft {
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: capitalize;
        }
        .badge-soft.success {background: #d1e7dd;color: #0f5132;}
        .badge-soft.warning {background: #fff3cd;color: #8a5200;}
        .badge-soft.secondary {background: #e2e8f0;color: #475467;}
        .badge-soft.danger {background: #fee2e2;color: #b91c1c;}
        .featured-icon {
            color: #f59e0b;
        }
        .featured-icon.inactive {
            color: #d1d5db;
        }
        .table-density-condensed .admin-table tbody tr td {
            padding: 0.45rem 0.75rem;
        }
        .table-density-comfortable .admin-table tbody tr td {
            padding: 0.9rem 0.95rem;
        }
        .row-select {
            width: 1.1rem;
            height: 1.1rem;
        }
        @media (max-width: 992px) {
            .admin-header {
                position: static;
            }
            .admin-toolbar {
                flex-direction: column;
                align-items: stretch;
            }
            .admin-toolbar .form-select,
            .admin-toolbar .form-control {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid admin-shell">
  <div class="row">
    <?php include '../../admin/admin-sidebar.php'; ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-4 py-4">
      <?php include '../../admin/admin-header.php'; ?>
      <?php include '../../admin/admin-breadcrumbs.php'; ?>
      
      <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success shadow-sm">Operation completed successfully!</div>
      <?php endif; ?>
      
      <header class="admin-header shadow-sm mb-4">
        <h2><?php echo $title; ?></h2>
        <div class="stats-pill">
          <span class="stats-chip success"><i class="fas fa-check-circle me-1"></i><?php echo $active; ?> Active</span>
          <span class="stats-chip warning"><i class="fas fa-hourglass-half me-1"></i><?php echo $pending; ?> Pending</span>
          <span class="stats-chip secondary"><i class="fas fa-database me-1"></i><?php echo $total; ?> Total</span>
        </div>
      </header>

      <div class="admin-toolbar">
        <div class="d-flex flex-wrap gap-2 align-items-center">
          <select class="form-select form-select-sm filter-control" data-filter="type">
            <option value="">All Types</option>
            <?php foreach ($types as $type): ?>
              <option value="<?php echo htmlspecialchars(strtolower($type)); ?>"><?php echo htmlspecialchars(ucfirst($type)); ?></option>
            <?php endforeach; ?>
          </select>
          <select class="form-select form-select-sm filter-control" data-filter="status">
            <option value="">All Status</option>
            <?php foreach ($statuses as $status): ?>
              <option value="<?php echo htmlspecialchars(strtolower($status)); ?>"><?php echo htmlspecialchars(ucfirst($status)); ?></option>
            <?php endforeach; ?>
          </select>
          <select class="form-select form-select-sm filter-control" data-filter="locality">
            <option value="">All Localities</option>
            <?php foreach ($localities as $loc): ?>
              <option value="<?php echo htmlspecialchars(strtolower($loc)); ?>"><?php echo htmlspecialchars($loc); ?></option>
            <?php endforeach; ?>
          </select>
          <input type="search" class="form-control form-control-sm filter-search" placeholder="Search property or owner">
        </div>
        <div class="bulk-actions d-flex flex-wrap gap-2 ms-auto">
          <div class="btn-group density-toggle" role="group">
            <button class="btn btn-outline-secondary btn-sm active" data-density="comfortable" type="button"><i class="fas fa-grip-lines"></i></button>
            <button class="btn btn-outline-secondary btn-sm" data-density="condensed" type="button"><i class="fas fa-grip-lines-vertical"></i></button>
          </div>
          <form id="bulkActionForm" class="d-flex gap-2" method="post" action="bulk-update-properties.php">
            <input type="hidden" name="ids" id="bulkIds">
            <input type="hidden" name="action" id="bulkActionField">
            <button type="button" class="btn btn-outline-success btn-sm bulk-action" data-action="activate"><i class="fas fa-toggle-on me-1"></i>Activate</button>
            <button type="button" class="btn btn-outline-warning btn-sm bulk-action" data-action="pending"><i class="fas fa-hourglass-half me-1"></i>Pending</button>
            <button type="button" class="btn btn-outline-secondary btn-sm bulk-action" data-action="inactive"><i class="fas fa-eye-slash me-1"></i>Unlist</button>
          </form>
        </div>
      </div>
      
      <?php if (empty($properties)): ?>
        <div class="alert alert-info">No properties found.</div>
      <?php else: ?>
        <div class="admin-card table-density-comfortable" id="propertyTableCard">
          <div class="table-responsive">
          <table class="table admin-table align-middle">
            <thead class="table-light">
              <tr>
                <th style="width:35px;">
                  <input type="checkbox" id="selectAll" class="form-check-input">
                </th>
                <th>ID</th>
                <th>Property Name</th>
                <th>Type</th>
                <th>Owner</th>
                <th>Location</th>
                <th>Status</th>
                <th>Featured</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($properties as $prop): ?>
                <?php
                  $locality = $prop['locality'] ? $prop['locality'] : 'OMR Corridor';
                  $statusColors = ['active' => 'success', 'pending' => 'warning', 'inactive' => 'secondary', 'flagged' => 'danger'];
                  $color = $statusColors[$prop['status']] ?? 'secondary';
                ?>
                <tr data-type="<?php echo htmlspecialchars(strtolower($prop['property_type'])); ?>"
                    data-status="<?php echo htmlspecialchars(strtolower($prop['status'])); ?>"
                    data-locality="<?php echo htmlspecialchars(strtolower($locality)); ?>"
                    data-owner="<?php echo htmlspecialchars(strtolower($prop['owner_name'] ?? 'n/a')); ?>">
                  <td><input type="checkbox" class="form-check-input row-select" value="<?php echo (int)$prop['id']; ?>"></td>
                  <td><?php echo $prop['id']; ?></td>
                  <td>
                    <div class="d-flex flex-column">
                      <strong class="text-dark"><?php echo htmlspecialchars($prop['property_name']); ?></strong>
                      <span class="text-muted small"><?php echo htmlspecialchars($prop['slug']); ?></span>
                    </div>
                  </td>
                  <td><span class="badge-soft secondary text-uppercase"><?php echo htmlspecialchars($prop['property_type']); ?></span></td>
                  <td><?php echo htmlspecialchars($prop['owner_name'] ?? 'N/A'); ?></td>
                  <td><?php echo htmlspecialchars($locality); ?></td>
                  <td>
                    <span class="badge-soft <?php echo $color; ?>"><?php echo ucfirst($prop['status']); ?></span>
                  </td>
                  <td>
                    <i class="fas fa-star <?php echo $prop['featured'] ? 'featured-icon' : 'featured-icon inactive'; ?>"></i>
                  </td>
                  <td>
                    <div class="btn-group btn-group-sm">
                      <a href="../../omr-hostels-pgs/property-detail.php?id=<?php echo (int)$prop['id']; ?>" class="btn btn-outline-primary" title="View public page" data-bs-toggle="tooltip">
                        <i class="fas fa-eye"></i>
                      </a>
                      <?php if ($prop['status'] === 'pending'): ?>
                        <button class="btn btn-outline-success" onclick="approveProperty(<?php echo (int)$prop['id']; ?>)" title="Mark Active" data-bs-toggle="tooltip">
                          <i class="fas fa-check"></i>
                        </button>
                      <?php endif; ?>
                      <?php if ($prop['verification_status'] !== 'verified'): ?>
                        <button class="btn btn-outline-info" onclick="verifyProperty(<?php echo (int)$prop['id']; ?>)" title="Verify" data-bs-toggle="tooltip">
                          <i class="fas fa-certificate"></i>
                        </button>
                      <?php endif; ?>
                      <?php if ($prop['status'] === 'active'): ?>
                        <button class="btn btn-outline-warning" onclick="unlistProperty(<?php echo (int)$prop['id']; ?>)" title="Unlist" data-bs-toggle="tooltip">
                          <i class="fas fa-ban"></i>
                        </button>
                      <?php else: ?>
                        <button class="btn btn-outline-secondary" onclick="activateProperty(<?php echo (int)$prop['id']; ?>)" title="Activate" data-bs-toggle="tooltip">
                          <i class="fas fa-toggle-on"></i>
                        </button>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        </div>
      <?php endif; ?>
      
    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function approveProperty(id) {
  if (confirm('Approve this property?')) {
    window.location.href = 'approve-property.php?id=' + id;
  }
}
function verifyProperty(id) {
  if (confirm('Verify this property?')) {
    window.location.href = 'verify-property.php?id=' + id;
  }
}
function unlistProperty(id) {
  if (confirm('Unlist this property? It will no longer appear in public listings.')) {
    window.location.href = 'unlist-property.php?id=' + id;
  }
}
function activateProperty(id) {
  if (confirm('Set this property status to active?')) {
    window.location.href = 'approve-property.php?id=' + id;
  }
}

const tableCard = document.getElementById('propertyTableCard');
const selectAll = document.getElementById('selectAll');
const rowCheckboxes = document.querySelectorAll('.row-select');
const bulkIds = document.getElementById('bulkIds');
const bulkActionField = document.getElementById('bulkActionField');
const filterControls = document.querySelectorAll('.filter-control');
const filterSearch = document.querySelector('.filter-search');

const rows = Array.from(document.querySelectorAll('.admin-table tbody tr'));

function applyFilters() {
  const filters = {};
  filterControls.forEach(select => {
    const value = select.value.trim().toLowerCase();
    if (value) filters[select.dataset.filter] = value;
  });
  const query = filterSearch.value.trim().toLowerCase();

  rows.forEach(row => {
    let visible = true;
    Object.entries(filters).forEach(([key, value]) => {
      if (visible) {
        const datasetValue = (row.dataset[key] || '').toLowerCase();
        if (!datasetValue.includes(value)) {
          visible = false;
        }
      }
    });
    if (visible && query) {
      const matchText = (row.querySelector('td:nth-child(3)').innerText + ' ' + row.dataset.owner).toLowerCase();
      visible = matchText.includes(query);
    }
    row.style.display = visible ? '' : 'none';
  });
}

filterControls.forEach(select => select.addEventListener('change', applyFilters));
filterSearch.addEventListener('input', applyFilters);

document.querySelectorAll('.density-toggle .btn').forEach(btn => {
  btn.addEventListener('click', function () {
    document.querySelectorAll('.density-toggle .btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
    const density = this.dataset.density;
    tableCard.classList.remove('table-density-comfortable', 'table-density-condensed');
    tableCard.classList.add(density === 'condensed' ? 'table-density-condensed' : 'table-density-comfortable');
  });
});

selectAll?.addEventListener('change', function () {
  rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
});

rowCheckboxes.forEach(cb => cb.addEventListener('change', () => {
  if (!cb.checked) selectAll.checked = false;
}));

document.querySelectorAll('.bulk-action').forEach(btn => {
  btn.addEventListener('click', function () {
    const selected = Array.from(rowCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
    if (!selected.length) {
      alert('Select at least one property to apply the bulk action.');
      return;
    }
    const action = this.dataset.action;
    if (confirm(`Apply "${action}" to ${selected.length} selected properties?`)) {
      bulkIds.value = selected.join(',');
      bulkActionField.value = action;
      document.getElementById('bulkActionForm').submit();
    }
  });
});

const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
tooltipTriggerList.forEach(function (tooltipTriggerEl) {
  new bootstrap.Tooltip(tooltipTriggerEl);
});

applyFilters();
</script>
</body>
</html>

