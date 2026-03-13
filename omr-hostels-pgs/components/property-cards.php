<?php
/**
 * Hostels & PGs – Property listings grid and empty state.
 * Expects: $properties, $total_properties, $current_page, $total_pages, $base_url, HOSTELS_PGS_BASE_URL.
 */
$base = defined('HOSTELS_PGS_BASE_URL') ? HOSTELS_PGS_BASE_URL : '/omr-hostels-pgs';
$properties = $properties ?? [];
$total_properties = (int)($total_properties ?? 0);
$current_page = (int)($current_page ?? 1);
$total_pages = (int)($total_pages ?? 1);
$base_url = $base_url ?? $base . '/';
?>
<?php if (!empty($properties)): ?>

    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="h4 mb-1">Available Properties</h2>
            <p class="text-muted mb-0">
                Showing <?php echo count($properties); ?> of <?php echo number_format($total_properties); ?> properties
                <?php if ($current_page > 1): ?>
                    – Page <?php echo $current_page; ?> of <?php echo $total_pages; ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="btn-group" role="group" aria-label="View options">
                <button type="button" class="btn btn-outline-primary btn-sm active" data-view="grid">
                    <i class="fas fa-th me-1"></i> Grid
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm" data-view="list">
                    <i class="fas fa-list me-1"></i> List
                </button>
            </div>
        </div>
    </div>

    <div class="row" id="properties-container">
        <?php foreach ($properties as $property): ?>
            <div class="col-lg-6 mb-4">
                <article class="property-card card-modern h-100 position-relative">
                    <?php if (!empty($property['featured'])): ?>
                        <span class="badge-verified position-absolute top-0 end-0 m-3">
                            <i class="fas fa-star me-1"></i> Featured
                        </span>
                    <?php endif; ?>

                    <div class="property-card-image-wrapper">
                        <?php if (!empty($property['featured_image'])): ?>
                            <img src="<?php echo htmlspecialchars($property['featured_image']); ?>"
                                 alt="<?php echo htmlspecialchars($property['property_name']); ?>"
                                 class="property-card-image"
                                 loading="lazy"
                                 decoding="async">
                        <?php else: ?>
                            <div class="property-card-image bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-home fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <header class="property-card-header">
                        <h3 class="h5 mb-2">
                            <a href="<?php echo htmlspecialchars($base); ?>/property-detail.php?id=<?php echo (int)$property['id']; ?>"
                               class="text-decoration-none text-dark property-card-title">
                                <?php echo htmlspecialchars($property['property_name']); ?>
                            </a>
                        </h3>
                        <div class="property-card-location">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            <?php echo htmlspecialchars($property['locality'] ?? 'OMR'); ?>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <span class="badge bg-primary"><?php echo htmlspecialchars($property['property_type'] ?? ''); ?></span>
                            <?php if (!empty($property['verification_status']) && $property['verification_status'] === 'verified'): ?>
                                <span class="badge-verified">
                                    <i class="fas fa-check-circle"></i> Verified
                                </span>
                            <?php endif; ?>
                            <?php if (!empty($property['is_student_friendly'])): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-graduation-cap me-1"></i> Student-Friendly
                                </span>
                            <?php endif; ?>
                        </div>
                    </header>

                    <div class="property-card-body">
                        <div class="property-card-overview mb-3">
                            <p class="text-muted mb-2">
                                <?php
                                $overview = $property['brief_overview'] ?? $property['full_description'] ?? '';
                                echo htmlspecialchars(strlen($overview) > 120 ? substr($overview, 0, 120) . '...' : $overview);
                                ?>
                            </p>
                        </div>
                        <?php if (!empty($property['facilities'])): ?>
                            <?php
                            $facilities = json_decode($property['facilities'], true);
                            if (is_array($facilities) && !empty($facilities)):
                            ?>
                                <div class="property-card-amenities">
                                    <?php foreach (array_slice($facilities, 0, 4) as $facility): ?>
                                        <span class="amenity-badge">
                                            <i class="fas fa-check me-1"></i><?php echo htmlspecialchars($facility); ?>
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if (count($facilities) > 4): ?>
                                        <span class="amenity-badge">+<?php echo count($facilities) - 4; ?> more</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <footer class="property-card-footer">
                        <div class="property-card-price">
                            ₹<?php echo number_format($property['monthly_rent_single'] ?? 0); ?>/month
                            <small class="text-muted d-block">Starting from</small>
                        </div>
                        <div class="property-actions">
                            <a href="<?php echo htmlspecialchars($base); ?>/property-detail.php?id=<?php echo (int)$property['id']; ?>"
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-eye me-1"></i> View Details
                            </a>
                        </div>
                    </footer>
                </article>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($total_pages > 1): ?>
        <div class="row mt-5">
            <div class="col-12">
                <?php echo generatePagination($current_page, $total_pages, $base_url); ?>
            </div>
        </div>
    <?php endif; ?>

<?php else: ?>

    <div class="row">
        <div class="col-12 text-center py-5">
            <div class="no-results">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h3 class="h4 mb-3">No Properties Found</h3>
                <p class="text-muted mb-4">
                    <?php if (!empty($filters)): ?>
                        Try adjusting your search criteria or <a href="<?php echo htmlspecialchars($base); ?>/">browse all properties</a>.
                    <?php else: ?>
                        No properties available at the moment. Check back soon for new listings!
                    <?php endif; ?>
                </p>
                <a href="<?php echo htmlspecialchars($base); ?>/" class="btn btn-primary">
                    <i class="fas fa-refresh me-1"></i> View All Properties
                </a>
            </div>
        </div>
    </div>

<?php endif; ?>
