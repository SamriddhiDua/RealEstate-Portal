<?php
// properties.php
require_once 'config/database.php';
$pageTitle = "Properties";
include 'includes/header.php';

// Build Query based on filters
$where = ["p.status = 'active'"];
$params = [];

if (!empty($_GET['city'])) {
    $where[] = "p.city = ?";
    $params[] = $_GET['city'];
}
if (!empty($_GET['type'])) {
    $where[] = "p.type = ?";
    $params[] = $_GET['type'];
}
if (!empty($_GET['min_price'])) {
    $where[] = "p.price >= ?";
    $params[] = $_GET['min_price'];
}
if (!empty($_GET['max_price'])) {
    $where[] = "p.price <= ?";
    $params[] = $_GET['max_price'];
}
if (!empty($_GET['bedrooms'])) {
    $where[] = "p.bedrooms >= ?";
    $params[] = $_GET['bedrooms'];
}

$whereClause = implode(" AND ", $where);

// Pagination
$limit = 9;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total for pagination
$countQuery = "SELECT COUNT(*) FROM properties p WHERE $whereClause";
$stmtCount = $pdo->prepare($countQuery);
$stmtCount->execute($params);
$totalProperties = $stmtCount->fetchColumn();
$totalPages = ceil($totalProperties / $limit);

// Fetch properties
$query = "
    SELECT p.*, pi.image_path 
    FROM properties p 
    LEFT JOIN property_images pi ON p.id = pi.property_id AND pi.is_thumbnail = 1
    WHERE $whereClause 
    ORDER BY p.created_at DESC 
    LIMIT $limit OFFSET $offset
";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$properties = $stmt->fetchAll();

// Fetch cities for filter
$stmtLoc = $pdo->query("SELECT DISTINCT city FROM locations ORDER BY city ASC");
$cities = $stmtLoc->fetchAll();
?>

<section class="section container" style="padding-top: 2rem;">
    <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
        
        <!-- Sidebar Filters -->
        <aside style="flex: 0 0 300px; width: 100%;">
            <div style="background: var(--white); padding: 1.5rem; border-radius: 8px; box-shadow: var(--shadow-sm);">
                <h3 style="margin-bottom: 1.5rem;">Filter Properties</h3>
                <form action="properties.php" method="GET">
                    <div class="form-group">
                        <label class="form-label">Location</label>
                        <select name="city" class="form-control">
                            <option value="">All Cities</option>
                            <?php foreach ($cities as $city): ?>
                                <option value="<?= htmlspecialchars($city['city']) ?>" <?= (isset($_GET['city']) && $_GET['city'] == $city['city']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($city['city']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Property Type</label>
                        <select name="type" class="form-control">
                            <option value="">All Types</option>
                            <option value="Buy" <?= (isset($_GET['type']) && $_GET['type'] == 'Buy') ? 'selected' : '' ?>>Buy</option>
                            <option value="Rent" <?= (isset($_GET['type']) && $_GET['type'] == 'Rent') ? 'selected' : '' ?>>Rent</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Min Price</label>
                        <input type="number" name="min_price" class="form-control" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Max Price</label>
                        <input type="number" name="max_price" class="form-control" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Bedrooms</label>
                        <select name="bedrooms" class="form-control">
                            <option value="">Any</option>
                            <option value="1" <?= (isset($_GET['bedrooms']) && $_GET['bedrooms'] == '1') ? 'selected' : '' ?>>1+ BHK</option>
                            <option value="2" <?= (isset($_GET['bedrooms']) && $_GET['bedrooms'] == '2') ? 'selected' : '' ?>>2+ BHK</option>
                            <option value="3" <?= (isset($_GET['bedrooms']) && $_GET['bedrooms'] == '3') ? 'selected' : '' ?>>3+ BHK</option>
                            <option value="4" <?= (isset($_GET['bedrooms']) && $_GET['bedrooms'] == '4') ? 'selected' : '' ?>>4+ BHK</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Apply Filters</button>
                    <a href="properties.php" class="btn btn-outline btn-block" style="margin-top: 0.5rem;">Reset</a>
                </form>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div style="flex: 1; min-width: 0;">
            <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <h2 style="margin: 0;">Showing Properties (<?= $totalProperties ?>)</h2>
            </div>
            
            <div class="grid-3">
                <?php if (count($properties) > 0): ?>
                    <?php foreach ($properties as $property): ?>
                        <div class="property-card">
                            <div class="property-img-wrapper">
                                <div class="property-badges">
                                    <?php if ($property['type'] == 'Buy'): ?>
                                        <span class="badge badge-buy">For Sale</span>
                                    <?php else: ?>
                                        <span class="badge badge-rent">For Rent</span>
                                    <?php endif; ?>
                                    <?php if ($property['featured']): ?>
                                        <span class="badge badge-featured">Featured</span>
                                    <?php endif; ?>
                                </div>
                                <?php 
                                $imgPath = $property['image_path'] ? 'uploads/properties/' . $property['image_path'] : 'assets/images/placeholder.jpg';
                                ?>
                                <img src="<?= htmlspecialchars($imgPath) ?>" alt="<?= htmlspecialchars($property['title']) ?>" class="property-img" onerror="this.src='https://via.placeholder.com/600x400?text=No+Image'">
                                <div class="property-price">$<?= number_format($property['price']) ?> <?= $property['type'] == 'Rent' ? '/mo' : '' ?></div>
                            </div>
                            <div class="property-content">
                                <div class="property-location">
                                    <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($property['locality']) ?>, <?= htmlspecialchars($property['city']) ?>
                                </div>
                                <h3 class="property-title">
                                    <a href="property-detail.php?id=<?= $property['id'] ?>"><?= htmlspecialchars($property['title']) ?></a>
                                </h3>
                                <div class="property-features">
                                    <?php if ($property['bedrooms']): ?>
                                        <span class="feature-item"><i class="fas fa-bed"></i> <?= $property['bedrooms'] ?> Beds</span>
                                    <?php endif; ?>
                                    <?php if ($property['bathrooms']): ?>
                                        <span class="feature-item"><i class="fas fa-bath"></i> <?= $property['bathrooms'] ?> Baths</span>
                                    <?php endif; ?>
                                    <?php if ($property['area_sqft']): ?>
                                        <span class="feature-item"><i class="fas fa-vector-square"></i> <?= $property['area_sqft'] ?> sqft</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: var(--white); border-radius: 8px;">
                        <i class="fas fa-search" style="font-size: 3rem; color: var(--text-light); margin-bottom: 1rem;"></i>
                        <h3>No properties found</h3>
                        <p>Try adjusting your search filters to find more properties.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div style="margin-top: 3rem; display: flex; justify-content: center; gap: 0.5rem;">
                    <?php 
                    $queryParams = $_GET;
                    for ($i = 1; $i <= $totalPages; $i++): 
                        $queryParams['page'] = $i;
                        $queryString = http_build_query($queryParams);
                    ?>
                        <a href="?<?= $queryString ?>" class="btn <?= $page === $i ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.5rem 1rem;">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
