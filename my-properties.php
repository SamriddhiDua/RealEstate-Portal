<?php
// my-properties.php
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pageTitle = "My Properties";
include 'includes/header.php';

// Fetch user's properties
$stmt = $pdo->prepare("
    SELECT p.*, pi.image_path 
    FROM properties p 
    LEFT JOIN property_images pi ON p.id = pi.property_id AND pi.is_thumbnail = 1
    WHERE p.user_id = ? 
    ORDER BY p.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$properties = $stmt->fetchAll();
?>

<div style="background-color: var(--primary-color); color: var(--white); padding: 2rem 0;">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
        <h1 style="color: var(--white); margin: 0;">My Properties</h1>
        <a href="post-property.php" class="btn btn-accent"><i class="fas fa-plus"></i> Post New</a>
    </div>
</div>

<section class="section container" style="padding-top: 2rem;">
    <?php if (count($properties) > 0): ?>
        <div class="card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Listed On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($properties as $property): ?>
                            <tr>
                                <td style="width: 100px;">
                                    <?php 
                                    $imgPath = $property['image_path'] ? 'uploads/properties/' . $property['image_path'] : 'assets/images/placeholder.jpg';
                                    ?>
                                    <img src="<?= htmlspecialchars($imgPath) ?>" alt="thumb" style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px;" onerror="this.src='https://via.placeholder.com/80x60?text=No+Image'">
                                </td>
                                <td>
                                    <strong><a href="property-detail.php?id=<?= $property['id'] ?>" style="color: var(--primary-color);"><?= htmlspecialchars($property['title']) ?></a></strong>
                                    <div style="font-size: 0.8rem; color: var(--text-light);"><?= $property['type'] == 'Buy' ? 'For Sale' : 'For Rent' ?></div>
                                </td>
                                <td><?= htmlspecialchars($property['locality'] . ', ' . $property['city']) ?></td>
                                <td>$<?= number_format($property['price']) ?></td>
                                <td>
                                    <?php 
                                        $badgeClass = 'badge-active';
                                        if ($property['status'] == 'inactive') $badgeClass = 'badge-inactive';
                                        if ($property['status'] == 'sold' || $property['status'] == 'rented') $badgeClass = 'badge-sold';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= ucfirst($property['status']) ?></span>
                                </td>
                                <td><?= date('M j, Y', strtotime($property['created_at'])) ?></td>
                                <td>
                                    <a href="property-detail.php?id=<?= $property['id'] ?>" class="btn btn-sm btn-outline"><i class="fas fa-eye"></i> View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 4rem; background: var(--white); border-radius: 8px; box-shadow: var(--shadow-sm);">
            <i class="fas fa-home" style="font-size: 4rem; color: var(--border-color); margin-bottom: 1rem;"></i>
            <h3>You haven't listed any properties yet.</h3>
            <p style="color: var(--text-light); margin-bottom: 2rem;">Start reaching buyers and tenants today.</p>
            <a href="post-property.php" class="btn btn-primary">Post Your First Property</a>
        </div>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>
