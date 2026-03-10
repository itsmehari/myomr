<?php
/**
 * Modular Directory Page Template
 * This template can be used for all directory pages (schools, hospitals, restaurants, etc.)
 */

// Include configuration
require_once 'directory-config.php';

// Get directory type from URL parameter or default
$directory_type = isset($_GET['type']) ? $_GET['type'] : 'schools';
$config = get_directory_config($directory_type);

if (!$config) {
    die('Invalid directory type');
}

// Database connection
require '../core/omr-connect.php';

// Pagination settings
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Filters
$filters = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $filters['name'] = $conn->real_escape_string($_GET['search']);
}

// Build SQL query
$sql = get_directory_sql($config, $filters);
$sql .= " LIMIT $items_per_page OFFSET $offset";

// Count total items for pagination
$count_sql = "SELECT COUNT(*) as total FROM {$config['table']} WHERE 1=1";
foreach ($filters as $field => $value) {
    if (!empty($value)) {
        $count_sql .= " AND $field LIKE '%$value%'";
    }
}
$count_result = $conn->query($count_sql);
$total_items = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_items / $items_per_page);

// Fetch data
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../components/meta.php'; ?>
    <?php include '../components/analytics.php'; ?>
    <?php include '../components/head-resources.php'; ?>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo htmlspecialchars($config['description']); ?>">
    <meta name="keywords" content="Old Mahabalipuram Road, OMR Road, OMR News, My OMR, <?php echo ucfirst($directory_type); ?>, Chennai">
    <meta name="author" content="MyOMR Team">
    
    <title><?php echo htmlspecialchars($config['title']); ?> | MyOMR</title>
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo htmlspecialchars($config['title']); ?> | MyOMR">
    <meta property="og:description" content="<?php echo htmlspecialchars($config['description']); ?>">
    <meta property="og:image" content="https://myomr.in/My-OMR-Logo.png">
    <meta property="og:url" content="https://myomr.in/omr-listings/<?php echo $directory_type; ?>.php">
    <meta property="og:site_name" content="My OMR Old Mahabalipuram Road">
    
    <!-- Twitter Cards -->
    <meta name="twitter:title" content="<?php echo htmlspecialchars($config['title']); ?> | MyOMR">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($config['description']); ?>">
    <meta name="twitter:image" content="https://myomr.in/My-OMR-Logo.png">
    <meta name="twitter:site" content="@MyomrNews">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://myomr.in/omr-listings/<?php echo $directory_type; ?>.php">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Josefin+Sans:wght@300;400&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/main.css">
    
    <style>
        .directory-hero {
            background: linear-gradient(135deg, <?php echo $config['color']; ?> 0%, #22c55e 100%);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }
        .directory-item {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        .directory-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .directory-item h5 {
            color: <?php echo $config['color']; ?>;
            font-family: 'Playfair Display', serif;
            margin-bottom: 1rem;
        }
        .directory-item p {
            margin-bottom: 0.5rem;
            color: #666;
        }
        .directory-item .contact {
            background: <?php echo $config['color']; ?>;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin-top: 0.5rem;
        }
        .search-section {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        .pagination {
            justify-content: center;
            margin-top: 2rem;
        }
    </style>
</head>

<body>
    <!-- WhatsApp Floating Button -->
    <a href="https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi" class="float" target="_blank">
        <i class="fa fa-whatsapp my-float"></i>
    </a>

    <!-- Facebook SDK -->
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v14.0" nonce="brAi0ji4"></script>

    <!-- Navigation -->
    <?php include '../components/main-nav.php'; ?>

    <!-- Hero Section -->
    <section class="directory-hero">
        <div class="container">
            <h1><i class="<?php echo $config['icon']; ?>"></i> <?php echo htmlspecialchars($config['title']); ?></h1>
            <p class="lead"><?php echo htmlspecialchars($config['description']); ?></p>
            <a href="#directory-content" class="btn btn-lg btn-warning font-weight-bold mt-3">Explore Now</a>
        </div>
    </section>

    <!-- Search Section -->
    <section class="container py-4">
        <div class="search-section">
            <h3 class="mb-3">Search <?php echo ucfirst($directory_type); ?></h3>
            <form method="GET" action="">
                <input type="hidden" name="type" value="<?php echo $directory_type; ?>">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" name="search" class="form-control" placeholder="Search by name..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Directory Content -->
    <section class="container py-4" id="directory-content">
        <!-- Introduction -->
        <div class="row mb-4">
            <div class="col-12">
                <?php foreach ($config['intro_text'] as $paragraph): ?>
                    <p class="text-justify"><?php echo htmlspecialchars($paragraph); ?></p>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Directory Items -->
        <h2 class="text-center mb-4"><?php echo ucfirst($directory_type); ?> Along OMR Road</h2>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="row">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <!-- Structured Data for SEO -->
                    <script type="application/ld+json">
                    <?php echo generate_structured_data($config, $row); ?>
                    </script>

                    <div class="col-md-6 mb-4">
                        <div class="directory-item">
                            <h5><?php echo htmlspecialchars($row[$config['fields']['name']]); ?></h5>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($row[$config['fields']['address']]); ?></p>
                            <?php if (isset($row[$config['fields']['landmark']]) && !empty($row[$config['fields']['landmark']])): ?>
                                <p><strong>Landmark:</strong> <?php echo htmlspecialchars($row[$config['fields']['landmark']]); ?></p>
                            <?php endif; ?>
                            <?php if (isset($config['fields']['cuisine']) && isset($row[$config['fields']['cuisine']])): ?>
                                <p><strong>Cuisine:</strong> <?php echo htmlspecialchars($row[$config['fields']['cuisine']]); ?></p>
                            <?php endif; ?>
                            <?php if (isset($config['fields']['rating']) && isset($row[$config['fields']['rating']])): ?>
                                <p><strong>Rating:</strong> <?php echo $row[$config['fields']['rating']]; ?> <i class="fas fa-star text-warning"></i></p>
                            <?php endif; ?>
                            <?php if (isset($config['fields']['cost_for_two']) && isset($row[$config['fields']['cost_for_two']])): ?>
                                <p><strong>Cost for Two:</strong> ₹<?php echo $row[$config['fields']['cost_for_two']]; ?></p>
                            <?php endif; ?>
                            <?php if (isset($row[$config['fields']['contact']]) && !empty($row[$config['fields']['contact']])): ?>
                                <div class="contact">
                                    <i class="fas fa-phone"></i> <?php echo htmlspecialchars($row[$config['fields']['contact']]); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?type=<?php echo $directory_type; ?>&page=<?php echo $page - 1; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                <a class="page-link" href="?type=<?php echo $directory_type; ?>&page=<?php echo $i; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?type=<?php echo $directory_type; ?>&page=<?php echo $page + 1; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center">
                <p>No <?php echo $directory_type; ?> found matching your criteria.</p>
                <a href="?type=<?php echo $directory_type; ?>" class="btn btn-primary">Clear Search</a>
            </div>
        <?php endif; ?>
    </section>

    <!-- Call to Action -->
    <section class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-success shadow-sm text-center p-4" style="background: linear-gradient(90deg, #FDBB2D 0%, #3A1C71 100%); color: #fff;">
                    <h2 class="mb-3">Join the OMR Community!</h2>
                    <p class="lead">Share your experiences, suggest new listings, or get involved in community initiatives.</p>
                    <p>Email us at <a href="mailto:myomrnews@gmail.com" class="text-white font-weight-bold">myomrnews@gmail.com</a></p>
                    <div class="mt-3">
                        <?php include '../components/social-icons.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include '../components/footer.php'; ?>

    <!-- Bootstrap JS, jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
