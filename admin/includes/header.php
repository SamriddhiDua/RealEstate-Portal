<?php
// admin/includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check admin role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Real Estate Portal</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-header">
        <a href="index.php"><i class="fas fa-home text-accent"></i> AdminPanel</a>
    </div>
    <ul class="sidebar-menu">
        <li class="<?= $currentPage == 'index.php' ? 'active' : '' ?>">
            <a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        </li>
        <li class="<?= $currentPage == 'properties.php' ? 'active' : '' ?>">
            <a href="properties.php"><i class="fas fa-building"></i> Properties</a>
        </li>
        <li class="<?= $currentPage == 'users.php' ? 'active' : '' ?>">
            <a href="users.php"><i class="fas fa-users"></i> Users</a>
        </li>
        <li class="<?= $currentPage == 'inquiries.php' ? 'active' : '' ?>">
            <a href="inquiries.php"><i class="fas fa-envelope"></i> Inquiries</a>
        </li>
        <li>
            <a href="../index.php"><i class="fas fa-external-link-alt"></i> View Website</a>
        </li>
        <li>
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </li>
    </ul>
</aside>

<main class="main-content">
    <header class="topbar">
        <div class="topbar-title"><?= isset($pageTitle) ? $pageTitle : 'Dashboard' ?></div>
        <div class="topbar-actions">
            <span style="font-weight: 500;">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
        </div>
    </header>
    <div class="content-wrapper">
