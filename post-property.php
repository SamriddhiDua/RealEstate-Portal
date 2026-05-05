<?php
// post-property.php
require_once 'config/database.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $type = $_POST['type'] ?? '';
    $price = $_POST['price'] ?? 0;
    $city = trim($_POST['city'] ?? '');
    $locality = trim($_POST['locality'] ?? '');
    $area_sqft = $_POST['area_sqft'] ?? 0;
    $bedrooms = $_POST['bedrooms'] ?? 0;
    $bathrooms = $_POST['bathrooms'] ?? 0;
    $amenities = $_POST['amenities'] ?? [];

    if (empty($title) || empty($type) || empty($price) || empty($city) || empty($locality)) {
        $error = "Please fill in all required fields.";
    } else {
        try {
            $pdo->beginTransaction();
            
            // Insert Location if not exists
            $stmtLoc = $pdo->prepare("SELECT id FROM locations WHERE city = ? AND locality = ?");
            $stmtLoc->execute([$city, $locality]);
            if ($stmtLoc->rowCount() == 0) {
                $stmtInsLoc = $pdo->prepare("INSERT INTO locations (city, locality) VALUES (?, ?)");
                $stmtInsLoc->execute([$city, $locality]);
            }
            
            // Insert Property
            $stmt = $pdo->prepare("INSERT INTO properties (user_id, title, description, type, price, city, locality, area_sqft, bedrooms, bathrooms) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $title, $description, $type, $price, $city, $locality, $area_sqft, $bedrooms, $bathrooms]);
            $property_id = $pdo->lastInsertId();
            
            // Insert Amenities
            if (!empty($amenities)) {
                $stmtAm = $pdo->prepare("INSERT INTO amenities (property_id, amenity_name) VALUES (?, ?)");
                foreach ($amenities as $amenity) {
                    $stmtAm->execute([$property_id, trim($amenity)]);
                }
            }
            
            // Handle Image Uploads
            if (!empty($_FILES['images']['name'][0])) {
                $uploadDir = UPLOAD_DIR;
                $isFirst = true;
                
                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    $fileName = basename($_FILES['images']['name'][$key]);
                    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
                    
                    if (in_array($fileExt, $allowedExts)) {
                        $newFileName = uniqid() . '_' . time() . '.' . $fileExt;
                        $destination = $uploadDir . $newFileName;
                        
                        if (move_uploaded_file($tmp_name, $destination)) {
                            $stmtImg = $pdo->prepare("INSERT INTO property_images (property_id, image_path, is_thumbnail) VALUES (?, ?, ?)");
                            $stmtImg->execute([$property_id, $newFileName, $isFirst ? 1 : 0]);
                            $isFirst = false;
                        }
                    }
                }
            }
            
            $pdo->commit();
            $success = "Property listed successfully! It is now active.";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Failed to list property. Error: " . $e->getMessage();
        }
    }
}

$pageTitle = "Post Property";
include 'includes/header.php';
?>

<div class="container" style="padding: 2rem 0;">
    <div style="max-width: 800px; margin: 0 auto; background: var(--white); padding: 2rem; border-radius: 8px; box-shadow: var(--shadow-md);">
        <h2 style="margin-bottom: 1.5rem; text-align: center; color: var(--primary-color);">Post a New Property</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <div class="text-center" style="margin-bottom: 2rem;">
                <a href="my-properties.php" class="btn btn-outline">View My Properties</a>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" enctype="multipart/form-data">
            <h4 style="margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Basic Information</h4>
            <div class="form-group">
                <label class="form-label">Property Title *</label>
                <input type="text" name="title" class="form-control" required placeholder="e.g. Modern Apartment in Downtown">
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Property Type *</label>
                    <select name="type" class="form-control" required>
                        <option value="">Select Type</option>
                        <option value="Buy">For Sale</option>
                        <option value="Rent">For Rent</option>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Price ($) *</label>
                    <input type="number" name="price" class="form-control" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="5"></textarea>
            </div>
            
            <h4 style="margin: 2rem 0 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Location</h4>
            <div style="display: flex; gap: 1rem;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">City *</label>
                    <input type="text" name="city" class="form-control" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Locality / Neighborhood *</label>
                    <input type="text" name="locality" class="form-control" required>
                </div>
            </div>
            
            <h4 style="margin: 2rem 0 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Property Details</h4>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 150px;">
                    <label class="form-label">Area (sqft)</label>
                    <input type="number" name="area_sqft" class="form-control">
                </div>
                <div class="form-group" style="flex: 1; min-width: 150px;">
                    <label class="form-label">Bedrooms</label>
                    <input type="number" name="bedrooms" class="form-control">
                </div>
                <div class="form-group" style="flex: 1; min-width: 150px;">
                    <label class="form-label">Bathrooms</label>
                    <input type="number" name="bathrooms" class="form-control">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Amenities (Select all that apply)</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 0.5rem;">
                    <?php 
                    $amenityOptions = ['Parking', 'Swimming Pool', 'Gym', 'Garden', 'Security', 'Elevator', 'Balcony', 'Furnished', 'Air Conditioning'];
                    foreach ($amenityOptions as $am): 
                    ?>
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="amenities[]" value="<?= $am ?>"> <?= $am ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <h4 style="margin: 2rem 0 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Images</h4>
            <div class="form-group">
                <label class="form-label">Upload Images (Max 5, JPG/PNG)</label>
                <input type="file" name="images[]" class="form-control" multiple accept="image/*" style="padding-bottom: 2.2rem;">
                <small style="color: var(--text-light);">The first image will be used as the thumbnail.</small>
            </div>
            
            <div style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary btn-block" style="font-size: 1.1rem; padding: 1rem;">Post Property</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
