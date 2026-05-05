<?php
// contact.php
$pageTitle = "Contact Us";
include 'includes/header.php';

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic form handling (in a real app, this would send an email)
    $success = "Thank you for reaching out! Our team will get back to you shortly.";
}
?>

<div style="background-color: var(--primary-color); color: var(--white); padding: 4rem 0; text-align: center;">
    <div class="container">
        <h1 style="color: var(--white); margin-bottom: 1rem;">Contact Us</h1>
        <p style="font-size: 1.2rem; opacity: 0.9;">We'd love to hear from you. Reach out to us for any inquiries.</p>
    </div>
</div>

<section class="section container">
    <div style="display: flex; gap: 4rem; flex-wrap: wrap;">
        
        <div style="flex: 1; min-width: 300px;">
            <h3>Get in Touch</h3>
            <p style="margin-bottom: 2rem; color: var(--text-light);">Have questions about buying, renting or selling? Our team of experts is ready to help you navigate the real estate market.</p>
            
            <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; align-items: flex-start;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(232, 160, 32, 0.1); color: var(--accent-color); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div>
                    <h4 style="margin-bottom: 0.25rem;">Head Office</h4>
                    <p style="color: var(--text-light);">Plot No. 12, Sector 17,<br>Chandigarh, India 160017</p>
                </div>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; align-items: flex-start;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(232, 160, 32, 0.1); color: var(--accent-color); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                    <i class="fas fa-phone"></i>
                </div>
                <div>
                    <h4 style="margin-bottom: 0.25rem;">Phone</h4>
                    <p style="color: var(--text-light);">+91 172 123 4567<br>Mon-Fri 9am to 6pm</p>
                </div>
            </div>
            
            <div style="display: flex; gap: 1rem; align-items: flex-start;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(232, 160, 32, 0.1); color: var(--accent-color); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                    <i class="fas fa-envelope"></i>
                </div>
                <div>
                    <h4 style="margin-bottom: 0.25rem;">Email</h4>
                    <p style="color: var(--text-light);">info@realestate.com<br>support@realestate.com</p>
                </div>
            </div>
        </div>
        
        <div style="flex: 1; min-width: 300px; background: var(--white); padding: 2.5rem; border-radius: 8px; box-shadow: var(--shadow-md);">
            <h3 style="margin-bottom: 1.5rem;">Send us a Message</h3>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Subject</label>
                    <input type="text" name="subject" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Message *</label>
                    <textarea name="message" class="form-control" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Send Message</button>
            </form>
        </div>
        
    </div>
</section>

<?php include 'includes/footer.php'; ?>
