<?php
// includes/navbar.php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
    <div class="container navbar-container">
        <a href="index.php" class="navbar-brand">
            <i class="fas fa-home"></i> RealEstate
        </a>
        
        <button class="mobile-menu-btn">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="navbar-nav">
            <li><a href="index.php" class="nav-link <?= $currentPage == 'index.php' ? 'text-accent' : '' ?>">Home</a></li>
            <li><a href="properties.php" class="nav-link <?= $currentPage == 'properties.php' ? 'text-accent' : '' ?>">Properties</a></li>
            <li><a href="about.php" class="nav-link <?= $currentPage == 'about.php' ? 'text-accent' : '' ?>">About</a></li>
            <li><a href="contact.php" class="nav-link <?= $currentPage == 'contact.php' ? 'text-accent' : '' ?>">Contact</a></li>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="my-properties.php" class="nav-link <?= $currentPage == 'my-properties.php' ? 'text-accent' : '' ?>">My Properties</a></li>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <li><a href="admin/index.php" class="nav-link">Admin Panel</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>

        <div class="nav-actions">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="post-property.php" class="btn btn-accent"><i class="fas fa-plus"></i> Post Property</a>
                <a href="logout.php" class="btn btn-outline">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline">Login</a>
                <a href="register.php" class="btn btn-primary">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
