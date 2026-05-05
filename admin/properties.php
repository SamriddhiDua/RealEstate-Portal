<?php
// admin/properties.php
require_once '../config/database.php';
$pageTitle = "Manage Properties";
include 'includes/header.php';

// Handle Actions (Delete, Toggle Status, Toggle Featured)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    if ($action == 'delete') {
        $stmtDel = $pdo->prepare("DELETE FROM properties WHERE id = ?");
        $stmtDel->execute([$id]);
    } elseif ($action == 'toggle_status') {
        $stmtToggle = $pdo->prepare("UPDATE properties SET status = IF(status='active', 'inactive', 'active') WHERE id = ?");
        $stmtToggle->execute([$id]);
    } elseif ($action == 'toggle_featured') {
        $stmtFeat = $pdo->prepare("UPDATE properties SET featured = NOT featured WHERE id = ?");
        $stmtFeat->execute([$id]);
    }
    
    header("Location: properties.php");
    exit;
}

// Fetch all properties
$stmt = $pdo->query("
    SELECT p.*, u.name as agent_name 
    FROM properties p 
    JOIN users u ON p.user_id = u.id 
    ORDER BY p.created_at DESC
");
$properties = $stmt->fetchAll();
?>

<div class="card">
    <div class="card-header">
        <h3 style="font-size: 1.1rem;">All Properties</h3>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title & Agent</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Featured</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($properties) > 0): ?>
                    <?php foreach ($properties as $prop): ?>
                    <tr>
                        <td>#<?= $prop['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($prop['title']) ?></strong><br>
                            <small class="text-light">By <?= htmlspecialchars($prop['agent_name']) ?></small>
                        </td>
                        <td><?= $prop['type'] ?></td>
                        <td>$<?= number_format($prop['price']) ?></td>
                        <td>
                            <span class="badge <?= $prop['status'] == 'active' ? 'badge-active' : 'badge-inactive' ?>">
                                <?= ucfirst($prop['status']) ?>
                            </span>
                        </td>
                        <td>
                            <?= $prop['featured'] ? '<span class="badge badge-active">Yes</span>' : '<span class="badge badge-inactive">No</span>' ?>
                        </td>
                        <td>
                            <a href="../property-detail.php?id=<?= $prop['id'] ?>" target="_blank" class="btn btn-sm btn-primary" title="View"><i class="fas fa-eye"></i></a>
                            <a href="?action=toggle_status&id=<?= $prop['id'] ?>" class="btn btn-sm btn-outline" title="Toggle Status"><i class="fas fa-exchange-alt"></i></a>
                            <a href="?action=toggle_featured&id=<?= $prop['id'] ?>" class="btn btn-sm btn-outline" title="Toggle Featured"><i class="fas fa-star"></i></a>
                            <a href="?action=delete&id=<?= $prop['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this property?');" title="Delete"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">No properties found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    </div> <!-- End content-wrapper -->
</main> <!-- End main-content -->
</body>
</html>
