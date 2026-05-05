<?php
// index.php
require_once 'config/database.php';
$pageTitle = "Home";
include 'includes/header.php';

// Fetch featured properties
$stmt = $pdo->prepare("
    SELECT p.*, pi.image_path 
    FROM properties p 
    LEFT JOIN property_images pi ON p.id = pi.property_id AND pi.is_thumbnail = 1
    WHERE p.status = 'active' AND p.featured = 1 
    ORDER BY p.created_at DESC 
    LIMIT 6
");
$stmt->execute();
$featuredProperties = $stmt->fetchAll();

// Fetch locations for search dropdown
$stmtLoc = $pdo->query("SELECT DISTINCT city FROM locations ORDER BY city ASC");
$cities = $stmtLoc->fetchAll();
?>

<!-- Hero Section -->
<section class="hero" id="hero-slider">
    <div class="container">
        <h1>Find Your Dream Home</h1>
        <p>Explore top-rated properties in your favorite cities. Buy, sell, or rent with confidence.</p>
        
        <form action="properties.php" method="GET" class="hero-search">
            <div class="form-group">
                <select name="city" class="form-control">
                    <option value="">Select City</option>
                    <?php foreach ($cities as $city): ?>
                        <option value="<?= htmlspecialchars($city['city']) ?>"><?= htmlspecialchars($city['city']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <select name="type" class="form-control">
                    <option value="">Property Type</option>
                    <option value="Buy">Buy</option>
                    <option value="Rent">Rent</option>
                </select>
            </div>
            <button type="submit" class="btn btn-accent">Search</button>
        </form>
    </div>
</section>

<!-- Background Slider Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hero = document.getElementById('hero-slider');
    const images = [
        'uploads/properties/villa.png',
        'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1600&q=80',
        'uploads/properties/apartment.png',
        'https://images.unsplash.com/photo-1448630360428-6e9318822482?auto=format&fit=crop&w=1600&q=80',
        'uploads/properties/studio.png'
    ];
    
    let currentIndex = 0;

    function changeBackground() {
        // Apply the gradient and the next image
        hero.style.backgroundImage = `linear-gradient(rgba(10, 31, 68, 0.7), rgba(10, 31, 68, 0.7)), url('${images[currentIndex]}')`;
        currentIndex = (currentIndex + 1) % images.length;
    }

    // Initialize the first image
    changeBackground();
    
    // Change every 2 seconds
    setInterval(changeBackground, 2000);
});
</script>

<!-- Featured Properties Section -->
<section class="section container">
    <div class="text-center" style="margin-bottom: 3rem;">
        <h2 class="text-primary">Featured Properties</h2>
        <p class="text-light">Handpicked exclusive properties by our team.</p>
    </div>

    <div class="grid-3">
        <?php if (count($featuredProperties) > 0): ?>
            <?php foreach ($featuredProperties as $property): ?>
                <div class="property-card">
                    <div class="property-img-wrapper">
                        <div class="property-badges">
                            <?php if ($property['type'] == 'Buy'): ?>
                                <span class="badge badge-buy">For Sale</span>
                            <?php else: ?>
                                <span class="badge badge-rent">For Rent</span>
                            <?php endif; ?>
                            <span class="badge badge-featured">Featured</span>
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
            <div class="col-12 text-center">
                <p>No featured properties available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="text-center" style="margin-top: 3rem;">
        <a href="properties.php" class="btn btn-primary">Browse All Properties</a>
    </div>
</section>

<!-- Call to Action -->
<section class="section" style="background-color: var(--primary-color); color: var(--white); text-align: center;">
    <div class="container">
        <h2 style="color: var(--white);">Looking to Sell or Rent your Property?</h2>
        <p style="opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Reach thousands of potential buyers and tenants by listing your property on our platform.</p>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="post-property.php" class="btn btn-accent">Post a Property</a>
        <?php else: ?>
            <a href="register.php" class="btn btn-accent">Register to Post</a>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
