<?php
/**
 * Hostels & PGs – Hero section with search form and stats.
 * Expects: $filters, $localities, $total_properties, HOSTELS_PGS_BASE_URL.
 */
$base = defined('HOSTELS_PGS_BASE_URL') ? HOSTELS_PGS_BASE_URL : '/omr-hostels-pgs';
$localities = $localities ?? ['All of OMR', 'Navalur', 'Sholinganallur', 'Thoraipakkam', 'Kandhanchavadi', 'Perungudi', 'Okkiyam Thuraipakkam'];
$total_properties = (int)($total_properties ?? 0);
$filters = $filters ?? [];
?>
<section class="hero-section hero-modern text-white py-5" aria-labelledby="hero-heading">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3 hero-modern-title" id="hero-heading">Find Your Perfect Home in OMR</h1>
                <p class="lead mb-4 hero-modern-subtitle">Discover safe, comfortable, and affordable hostels & PG accommodations for students and professionals in the OMR corridor.</p>

                <form method="GET" class="search-form" role="search" aria-label="Search hostels and PGs" action="<?php echo htmlspecialchars($base); ?>/">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label for="search" class="form-label visually-hidden">Search properties</label>
                            <input type="text"
                                   id="search"
                                   name="search"
                                   class="form-control form-control-lg"
                                   placeholder="Property name or location"
                                   value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                                   aria-describedby="search-help">
                            <div id="search-help" class="form-text text-white-50">e.g., Navalur, Boys PG, Girls Hostel</div>
                        </div>
                        <div class="col-md-3">
                            <label for="locality" class="form-label visually-hidden">Locality</label>
                            <select id="locality" name="locality" class="form-select form-select-lg">
                                <option value="">All Localities</option>
                                <?php foreach ($localities as $loc): ?>
                                    <option value="<?php echo htmlspecialchars($loc); ?>"
                                            <?php echo isset($filters['locality']) && $filters['locality'] === $loc ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($loc); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="property_type" class="form-label visually-hidden">Property Type</label>
                            <select id="property_type" name="property_type" class="form-select form-select-lg">
                                <option value="">All Types</option>
                                <option value="PG" <?php echo isset($filters['property_type']) && $filters['property_type'] === 'PG' ? 'selected' : ''; ?>>PG</option>
                                <option value="Hostel" <?php echo isset($filters['property_type']) && $filters['property_type'] === 'Hostel' ? 'selected' : ''; ?>>Hostel</option>
                                <option value="Co-living" <?php echo isset($filters['property_type']) && $filters['property_type'] === 'Co-living' ? 'selected' : ''; ?>>Co-living</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-light btn-lg w-100" aria-label="Search properties">
                                <i class="fas fa-search me-1"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 text-center mt-4 mt-lg-0">
                <div class="card-modern text-dark bg-white bg-opacity-10 p-4 rounded h-100">
                    <p class="h2 mb-1 text-success"><?php echo number_format($total_properties); ?>+</p>
                    <p class="mb-1 text-dark fw-semibold">Verified Properties</p>
                    <small class="text-muted">Safe and affordable options</small>
                </div>
            </div>
        </div>
    </div>
</section>
