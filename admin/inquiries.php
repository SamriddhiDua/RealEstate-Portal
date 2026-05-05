<?php
// admin/inquiries.php
require_once '../config/database.php';
$pageTitle = "Manage Inquiries";
include 'includes/header.php';

// Handle Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    if ($action == 'delete') {
        $stmtDel = $pdo->prepare("DELETE FROM inquiries WHERE id = ?");
        $stmtDel->execute([$id]);
    } elseif ($action == 'mark_read') {
        $stmtToggle = $pdo->prepare("UPDATE inquiries SET status = 'read' WHERE id = ?");
        $stmtToggle->execute([$id]);
    } elseif ($action == 'mark_resolved') {
        $stmtToggle = $pdo->prepare("UPDATE inquiries SET status = 'resolved' WHERE id = ?");
        $stmtToggle->execute([$id]);
    }
    
    header("Location: inquiries.php");
    exit;
}

// Fetch all inquiries
$stmt = $pdo->query("
    SELECT i.*, p.title as property_title 
    FROM inquiries i 
    LEFT JOIN properties p ON i.property_id = p.id 
    ORDER BY i.created_at DESC
");
$inquiries = $stmt->fetchAll();
?>

<div class="card">
    <div class="card-header">
        <h3 style="font-size: 1.1rem;">All Contact Inquiries</h3>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Sender</th>
                    <th>Property</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($inquiries) > 0): ?>
                    <?php foreach ($inquiries as $inq): ?>
                    <tr>
                        <td style="white-space: nowrap;"><?= date('M j, Y', strtotime($inq['created_at'])) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($inq['sender_name']) ?></strong><br>
                            <a href="mailto:<?= htmlspecialchars($inq['sender_email']) ?>" style="font-size: 0.9rem; color: var(--primary-color);"><?= htmlspecialchars($inq['sender_email']) ?></a>
                        </td>
                        <td>
                            <?php if($inq['property_id']): ?>
                                <a href="../property-detail.php?id=<?= $inq['property_id'] ?>" target="_blank" style="color: var(--primary-color);">
                                    <?= htmlspecialchars($inq['property_title']) ?>
                                </a>
                            <?php else: ?>
                                General Inquiry
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="max-width: 300px; max-height: 60px; overflow-y: auto; font-size: 0.9rem;">
                                <?= nl2br(htmlspecialchars($inq['message'])) ?>
                            </div>
                        </td>
                        <td>
                            <?php 
                                $statusBadge = 'badge-inactive';
                                if($inq['status'] == 'read') $statusBadge = 'badge-active';
                                if($inq['status'] == 'resolved') $statusBadge = 'badge-sold';
                            ?>
                            <span class="badge <?= $statusBadge ?>"><?= ucfirst($inq['status']) ?></span>
                        </td>
                        <td style="white-space: nowrap;">
                            <?php if($inq['status'] == 'unread'): ?>
                                <a href="?action=mark_read&id=<?= $inq['id'] ?>" class="btn btn-sm btn-primary" title="Mark as Read"><i class="fas fa-check"></i></a>
                            <?php endif; ?>
                            <?php if($inq['status'] != 'resolved'): ?>
                                <a href="?action=mark_resolved&id=<?= $inq['id'] ?>" class="btn btn-sm btn-success" title="Mark as Resolved"><i class="fas fa-check-double"></i></a>
                            <?php endif; ?>
                            <a href="?action=delete&id=<?= $inq['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this inquiry?');" title="Delete"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">No inquiries found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    </div> <!-- End content-wrapper -->
</main> <!-- End main-content -->
</body>
</html>
