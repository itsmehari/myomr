<?php
/**
 * Hostels & PGs – Refine search filters bar.
 * Expects: $filters, $total_properties, HOSTELS_PGS_BASE_URL.
 */
$base = defined('HOSTELS_PGS_BASE_URL') ? HOSTELS_PGS_BASE_URL : '/omr-hostels-pgs';
$total_properties = (int)($total_properties ?? 0);
$filters = $filters ?? [];
?>
<section class="filters-section py-4 bg-light" aria-label="Refine search">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="h5 mb-0">Refine Your Search</h2>
                <small class="text-muted">Showing <?php echo number_format($total_properties); ?> properties</small>
            </div>
            <div class="col-md-6">
                <form method="GET" class="advanced-filters d-flex gap-2 flex-wrap" action="<?php echo htmlspecialchars($base); ?>/">
                    <?php foreach ($filters as $key => $value): ?>
                        <?php if ($key !== 'gender' && $value !== ''): ?>
                            <input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>">
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <select name="gender" class="form-select form-select-sm" aria-label="Gender preference">
                        <option value="">All</option>
                        <option value="Boys Only" <?php echo isset($filters['gender']) && $filters['gender'] === 'Boys Only' ? 'selected' : ''; ?>>Boys Only</option>
                        <option value="Girls Only" <?php echo isset($filters['gender']) && $filters['gender'] === 'Girls Only' ? 'selected' : ''; ?>>Girls Only</option>
                        <option value="Co-living" <?php echo isset($filters['gender']) && $filters['gender'] === 'Co-living' ? 'selected' : ''; ?>>Co-living</option>
                    </select>

                    <input type="number" name="price_max" class="form-control form-control-sm" placeholder="Max ₹/month"
                           value="<?php echo htmlspecialchars($filters['price_max'] ?? ''); ?>"
                           style="width: 150px;" aria-label="Maximum rent per month">

                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-filter me-1"></i> Apply
                    </button>

                    <?php if (!empty($filters)): ?>
                        <a href="<?php echo htmlspecialchars($base); ?>/" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-1"></i> Clear
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</section>
