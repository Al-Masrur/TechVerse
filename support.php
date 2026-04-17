<?php
require_once __DIR__ . '/config/bootstrap.php';
$pageTitle = page_title('Support');
require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container support-grid">
        <div class="info-card">
            <div class="page-crumbs">
                <a href="<?= url('index.php') ?>">Home</a>
                <span>/</span>
                <span>Support</span>
            </div>
            <span class="eyebrow">Need Help?</span>
            <h2>Support and Contact</h2>
            <p class="muted-text">For delivery updates, payment verification, or product inquiries, use the contact details below.</p>
            <div class="spec-list">
                <div class="spec-row"><span>Support phone</span><strong><?= e(support_phone()) ?></strong></div>
                <div class="spec-row"><span>Email</span><strong>support@techverse.local</strong></div>
                <div class="spec-row"><span>Address</span><strong>Tech Park, Dhaka</strong></div>
                <div class="spec-row"><span>Hours</span><strong>10:00 AM - 8:00 PM</strong></div>
            </div>
        </div>
        <div class="section-card">
            <h3>Before You Contact Us</h3>
            <div class="spec-list">
                <div class="spec-row"><span>Order tracking</span><strong>Check your latest order status in the order history page.</strong></div>
                <div class="spec-row"><span>Payment verification</span><strong>Use the exact sender number and transaction ID you submitted at checkout.</strong></div>
                <div class="spec-row"><span>Returns & issues</span><strong>Keep your order number ready for faster support.</strong></div>
            </div>
            <div class="promo-card secondary" style="margin-top:24px;">
                <span class="eyebrow">Course Project Ready</span>
                <strong>Clean demo flow</strong>
                <p>This page is included as a dedicated support/contact area for your e-commerce submission.</p>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
