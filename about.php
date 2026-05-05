<?php
// about.php
$pageTitle = "About Us";
include 'includes/header.php';
?>

<div style="background-color: var(--primary-color); color: var(--white); padding: 4rem 0; text-align: center;">
    <div class="container">
        <h1 style="color: var(--white); margin-bottom: 1rem;">About RealEstate</h1>
        <p style="font-size: 1.2rem; opacity: 0.9; max-width: 600px; margin: 0 auto;">Your trusted partner in finding the perfect property since 2010.</p>
    </div>
</div>

<section class="section container">
    <div style="display: flex; gap: 4rem; align-items: center; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
            <h2 style="color: var(--primary-color);">Who We Are</h2>
            <p style="margin-bottom: 1.5rem; line-height: 1.8;">RealEstate is a leading property portal dedicated to empowering consumers with data, inspiration, and knowledge around the place they call home, and connecting them with the best local professionals who can help.</p>
            <p style="margin-bottom: 1.5rem; line-height: 1.8;">Our platform serves the full lifecycle of owning and living in a home: buying, selling, renting, financing, remodeling, and more. It starts with our living database of thousands of Indian homes - including homes for sale, homes for rent, and homes not currently on the market, with a special focus on regions like Chandigarh.</p>
            <ul style="list-style-type: disc; margin-left: 1.5rem; line-height: 1.8;">
                <li>Verified properties and authentic listings</li>
                <li>Over 10 years of market experience</li>
                <li>Award-winning customer service</li>
            </ul>
        </div>
        <div style="flex: 1; min-width: 300px;">
            <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Office" style="border-radius: 8px; box-shadow: var(--shadow-lg);">
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
