<?php
/**
 * Sidebar filter panel — shared between desktop sidebar and mobile drawer
 * Requires: $filters, $categories to be available in calling scope
 */
?>

<!-- Category -->
<div class="jp-sidebar-card">
  <h3><i class="fas fa-layer-group me-1"></i> Category</h3>
  <div class="d-flex flex-column gap-1">
    <?php foreach ($categories as $cat): ?>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="category"
             id="cat_<?= htmlspecialchars($cat['slug']) ?>"
             value="<?= htmlspecialchars($cat['slug']) ?>"
             form="main-search-form"
             <?= ($filters['category'] ?? '') === $cat['slug'] ? 'checked' : '' ?>>
      <label class="form-check-label" for="cat_<?= htmlspecialchars($cat['slug']) ?>">
        <?= htmlspecialchars($cat['name']) ?>
      </label>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Job Type -->
<div class="jp-sidebar-card">
  <h3><i class="fas fa-briefcase me-1"></i> Job Type</h3>
  <?php
  $types = [
    ''           => 'All types',
    'full-time'  => 'Full-Time',
    'part-time'  => 'Part-Time',
    'contract'   => 'Contract',
    'internship' => 'Internship',
    'walk-in'    => '🚶 Walk-In',
  ];
  foreach ($types as $val => $label): ?>
  <div class="form-check">
    <input class="form-check-input" type="radio" name="job_type"
           id="jt_<?= $val ?: 'all' ?>"
           value="<?= htmlspecialchars($val) ?>"
           form="main-search-form"
           <?= ($filters['job_type'] ?? '') === $val ? 'checked' : '' ?>>
    <label class="form-check-label" for="jt_<?= $val ?: 'all' ?>"><?= htmlspecialchars($label) ?></label>
  </div>
  <?php endforeach; ?>
</div>

<!-- Job Segment -->
<div class="jp-sidebar-card">
  <h3><i class="fas fa-users me-1"></i> Job Segment</h3>
  <?php
  $segments = [
    ''         => 'All segments',
    'office'   => 'Office / White-collar',
    'manpower' => 'Manpower / Blue-collar',
    'hybrid'   => 'Hybrid',
  ];
  foreach ($segments as $val => $label): ?>
  <div class="form-check">
    <input class="form-check-input" type="radio" name="work_segment"
           id="ws_<?= $val ?: 'all' ?>"
           value="<?= htmlspecialchars($val) ?>"
           form="main-search-form"
           <?= ($filters['work_segment'] ?? '') === $val ? 'checked' : '' ?>>
    <label class="form-check-label" for="ws_<?= $val ?: 'all' ?>"><?= htmlspecialchars($label) ?></label>
  </div>
  <?php endforeach; ?>
</div>

<!-- Experience -->
<div class="jp-sidebar-card">
  <h3><i class="fas fa-user-clock me-1"></i> Experience</h3>
  <?php
  $exps = [
    'Fresher'   => 'Fresher (0 yrs)',
    'Junior'    => '1 – 3 years',
    'Mid-level' => '3 – 5 years',
    'Senior'    => '5 – 10 years',
    'Lead'      => 'Lead / Manager',
  ];
  foreach ($exps as $val => $label): ?>
  <div class="form-check">
    <input class="form-check-input" type="radio" name="experience_level"
           id="exp_<?= $val ?>"
           value="<?= $val ?>"
           form="main-search-form"
           <?= ($filters['experience_level'] ?? '') === $val ? 'checked' : '' ?>>
    <label class="form-check-label" for="exp_<?= $val ?>"><?= $label ?></label>
  </div>
  <?php endforeach; ?>
</div>

<!-- Work Mode -->
<div class="jp-sidebar-card">
  <h3><i class="fas fa-home me-1"></i> Work Mode</h3>
  <div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" role="switch"
           id="is_remote" name="is_remote" value="1"
           form="main-search-form"
           <?= ($filters['is_remote'] ?? '') === '1' ? 'checked' : '' ?>>
    <label class="form-check-label" for="is_remote">Remote / WFH only</label>
  </div>
</div>

<!-- Locations -->
<div class="jp-sidebar-card">
  <h3><i class="fas fa-map-marker-alt me-1"></i> Area</h3>
  <div>
    <?php
    $areas = ['Perungudi','Sholinganallur','Thoraipakkam','Navalur','Kelambakkam','Siruseri','Karapakkam'];
    foreach ($areas as $area): ?>
    <a href="?location=<?= urlencode($area) ?>"
       class="jp-location-chip <?= ($filters['location'] ?? '') === $area ? 'active' : '' ?>">
      <?= htmlspecialchars($area) ?>
    </a>
    <?php endforeach; ?>
  </div>
</div>

<!-- Apply / Clear -->
<div style="display:flex;gap:.5rem;margin-top:.25rem">
  <button type="submit" form="main-search-form"
          style="flex:1;background:var(--omr-green);color:#fff;border:none;border-radius:8px;padding:.6rem;font-weight:700;font-size:.9rem;font-family:'Poppins',sans-serif;cursor:pointer">
    <i class="fas fa-filter me-1"></i> Apply Filters
  </button>
  <a href="/omr-local-job-listings/"
     style="flex:0 0 auto;border:1.5px solid #e5e7eb;border-radius:8px;padding:.6rem .85rem;color:#6b7280;font-size:.88rem;text-decoration:none;display:flex;align-items:center;gap:.3rem">
    <i class="fas fa-times"></i> Clear
  </a>
</div>
