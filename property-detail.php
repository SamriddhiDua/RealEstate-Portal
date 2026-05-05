<?php
// property-detail.php
require_once 'config/database.php';

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header("Location: properties.php");
    exit;
}

// Fetch property details with user info
$stmt = $pdo->prepare("
    SELECT p.*, u.name as agent_name, u.email as agent_email, u.phone as agent_phone 
    FROM properties p 
    JOIN users u ON p.user_id = u.id 
    WHERE p.id = ? AND p.status = 'active'
");
$stmt->execute([$id]);
$property = $stmt->fetch();

if (!$property) {
    echo "<div style='text-align:center; padding: 5rem;'><h2>Property not found or inactive.</h2><a href='properties.php'>Go Back</a></div>";
    exit;
}

// Fetch images
$stmtImg = $pdo->prepare("SELECT * FROM property_images WHERE property_id = ? ORDER BY is_thumbnail DESC");
$stmtImg->execute([$id]);
$images = $stmtImg->fetchAll();

// Fetch amenities
$stmtAmenity = $pdo->prepare("SELECT amenity_name FROM amenities WHERE property_id = ?");
$stmtAmenity->execute([$id]);
$amenities = $stmtAmenity->fetchAll(PDO::FETCH_COLUMN);

// Handle Inquiry submission
$inquirySuccess = '';
$inquiryError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_inquiry'])) {
    $senderName = trim($_POST['name'] ?? '');
    $senderEmail = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($senderName) || empty($senderEmail) || empty($message)) {
        $inquiryError = "Please fill in all inquiry fields.";
    } else {
        $stmtInq = $pdo->prepare("INSERT INTO inquiries (property_id, sender_name, sender_email, message) VALUES (?, ?, ?, ?)");
        if ($stmtInq->execute([$id, $senderName, $senderEmail, $message])) {
            $inquirySuccess = "Your inquiry has been sent successfully. The agent will contact you soon.";
        } else {
            $inquiryError = "Failed to send inquiry. Please try again.";
        }
    }
}

$pageTitle = $property['title'];
include 'includes/header.php';
?>

<div style="background-color: var(--primary-color); color: var(--white); padding: 2rem 0;">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <?php if ($property['type'] == 'Buy'): ?>
                        <span class="badge badge-buy">For Sale</span>
                    <?php else: ?>
                        <span class="badge badge-rent">For Rent</span>
                    <?php endif; ?>
                    <span class="badge" style="background: rgba(255,255,255,0.2);"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($property['locality'] . ', ' . $property['city']) ?></span>
                </div>
                <h1 style="color: var(--white); margin-bottom: 0;"><?= htmlspecialchars($property['title']) ?></h1>
            </div>
            <div>
                <h2 style="color: var(--accent-color); margin-bottom: 0; font-size: 2.5rem;">
                    $<?= number_format($property['price']) ?> <?= $property['type'] == 'Rent' ? '<span style="font-size: 1rem; color: #fff;">/mo</span>' : '' ?>
                </h2>
            </div>
        </div>
    </div>
</div>

<section class="section container" style="padding-top: 2rem;">
    <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
        
        <!-- Main Content -->
        <div style="flex: 1; min-width: 0; max-width: 100%;">
            
            <!-- Image Gallery -->
            <div style="margin-bottom: 2rem; border-radius: 8px; overflow: hidden; box-shadow: var(--shadow-sm); background: #000;">
                <?php if (count($images) > 0): ?>
                    <img id="mainImage" src="uploads/properties/<?= htmlspecialchars($images[0]['image_path']) ?>" style="width: 100%; height: 500px; object-fit: contain;" alt="Main Image">
                    
                    <?php if (count($images) > 1): ?>
                        <div style="display: flex; gap: 0.5rem; padding: 0.5rem; overflow-x: auto; background: var(--white);">
                            <?php foreach ($images as $img): ?>
                                <img src="uploads/properties/<?= htmlspecialchars($img['image_path']) ?>" 
                                     style="width: 100px; height: 75px; object-fit: cover; cursor: pointer; border: 2px solid transparent;"
                                     onclick="document.getElementById('mainImage').src = this.src; this.style.borderColor = 'var(--accent-color)'; "
                                     alt="Thumbnail">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <img src="assets/images/placeholder.jpg" style="width: 100%; height: 500px; object-fit: cover;" alt="Placeholder">
                <?php endif; ?>
            </div>
            
            <!-- Property Overview Box -->
            <div style="background: var(--white); padding: 1.5rem; border-radius: 8px; box-shadow: var(--shadow-sm); margin-bottom: 2rem; display: flex; justify-content: space-around; text-align: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <i class="fas fa-bed text-accent" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;"><?= $property['bedrooms'] ?? 'N/A' ?></div>
                    <div style="color: var(--text-light); font-size: 0.9rem;">Bedrooms</div>
                </div>
                <div>
                    <i class="fas fa-bath text-accent" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;"><?= $property['bathrooms'] ?? 'N/A' ?></div>
                    <div style="color: var(--text-light); font-size: 0.9rem;">Bathrooms</div>
                </div>
                <div>
                    <i class="fas fa-vector-square text-accent" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;"><?= $property['area_sqft'] ?? 'N/A' ?></div>
                    <div style="color: var(--text-light); font-size: 0.9rem;">Square Feet</div>
                </div>
                <div>
                    <i class="fas fa-calendar-alt text-accent" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600;"><?= date('M j, Y', strtotime($property['created_at'])) ?></div>
                    <div style="color: var(--text-light); font-size: 0.9rem;">Listed On</div>
                </div>
            </div>

            <!-- Description -->
            <div style="background: var(--white); padding: 2rem; border-radius: 8px; box-shadow: var(--shadow-sm); margin-bottom: 2rem;">
                <h3 style="border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; margin-bottom: 1rem;">Description</h3>
                <div style="line-height: 1.8;">
                    <?= nl2br(htmlspecialchars($property['description'])) ?>
                </div>
            </div>
            
            <!-- Amenities -->
            <?php if (count($amenities) > 0): ?>
            <div style="background: var(--white); padding: 2rem; border-radius: 8px; box-shadow: var(--shadow-sm); margin-bottom: 2rem;">
                <h3 style="border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; margin-bottom: 1rem;">Amenities</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                    <?php foreach ($amenities as $amenity): ?>
                        <div><i class="fas fa-check-circle text-accent" style="margin-right: 0.5rem;"></i> <?= htmlspecialchars($amenity) ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Google Map Embed (Static Example) -->
            <div style="background: var(--white); padding: 2rem; border-radius: 8px; box-shadow: var(--shadow-sm);">
                <h3 style="border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; margin-bottom: 1rem;">Location Map</h3>
                <div style="width: 100%; height: 300px; background: #eee; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                    <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=<?= urlencode($property['locality'] . ', ' . $property['city']) ?>&output=embed"></iframe>
                </div>
            </div>
            
        </div>
        
        <!-- Sidebar Contact Form -->
        <aside style="flex: 0 0 350px; width: 100%;">
            <div style="background: var(--white); padding: 2rem; border-radius: 8px; box-shadow: var(--shadow-lg); position: sticky; top: 100px;">
                <h3 style="margin-bottom: 1.5rem; text-align: center;">Contact Agent</h3>
                
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border-color);">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--primary-color); color: var(--white); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0;"><?= htmlspecialchars($property['agent_name']) ?></h4>
                        <div style="color: var(--text-light); font-size: 0.9rem;">Listed Agent</div>
                    </div>
                </div>

                <?php if ($inquirySuccess): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($inquirySuccess) ?></div>
                <?php endif; ?>
                <?php if ($inquiryError): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($inquiryError) ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">Your Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Message *</label>
                        <textarea name="message" class="form-control" rows="4" required>Hi, I am interested in [<?= htmlspecialchars($property['title']) ?>]. Please contact me.</textarea>
                    </div>
                    <button type="submit" name="submit_inquiry" class="btn btn-primary btn-block">Send Inquiry</button>
                </form>
            </div>
        </aside>
        
    </div>
</section>

<?php include 'includes/footer.php'; ?>
