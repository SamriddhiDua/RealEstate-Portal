<?php
// admin/index.php
require_once '../config/database.php';
$pageTitle = "Dashboard";
include 'includes/header.php';

// Fetch stats
$stmtProp = $pdo->query("SELECT COUNT(*) FROM properties");
$totalProperties = $stmtProp->fetchColumn();

$stmtUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'");
$totalUsers = $stmtUsers->fetchColumn();

$stmtInq = $pdo->query("SELECT COUNT(*) FROM inquiries");
$totalInquiries = $stmtInq->fetchColumn();

// Recent inquiries
$stmtRecentInq = $pdo->query("
    SELECT i.*, p.title as property_title 
    FROM inquiries i 
    LEFT JOIN properties p ON i.property_id = p.id 
    ORDER BY i.created_at DESC LIMIT 5
");
$recentInquiries = $stmtRecentInq->fetchAll();
?>

<div class="dashboard-cards">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-building"></i></div>
        <div class="stat-details">
            <h3><?= $totalProperties ?></h3>
            <p>Total Properties</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-details">
            <h3><?= $totalUsers ?></h3>
            <p>Registered Users</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-envelope"></i></div>
        <div class="stat-details">
            <h3><?= $totalInquiries ?></h3>
            <p>Total Inquiries</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 style="font-size: 1.1rem;">Recent Inquiries</h3>
        <a href="inquiries.php" class="btn btn-primary btn-sm">View All</a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Sender</th>
                    <th>Property</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($recentInquiries) > 0): ?>
                    <?php foreach ($recentInquiries as $inq): ?>
                    <tr>
                        <td><?= htmlspecialchars($inq['sender_name']) ?><br><small><?= htmlspecialchars($inq['sender_email']) ?></small></td>
                        <td><?= htmlspecialchars($inq['property_title'] ?? 'N/A') ?></td>
                        <td><?= date('M j, Y', strtotime($inq['created_at'])) ?></td>
                        <td>
                            <?php 
                                $statusBadge = 'badge-inactive';
                                if($inq['status'] == 'read') $statusBadge = 'badge-active';
                                if($inq['status'] == 'resolved') $statusBadge = 'badge-sold';
                            ?>
                            <span class="badge <?= $statusBadge ?>"><?= ucfirst($inq['status']) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center">No recent inquiries.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    </div> <!-- End content-wrapper -->
</main> <!-- End main-content -->
</body>
</html>
