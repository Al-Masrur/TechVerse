<?php
require_once __DIR__ . '/config/bootstrap.php';
if (is_logged_in()) {
    redirect_to('index.php');
}
$pageTitle = page_title('Register');
require_once __DIR__ . '/includes/header.php';
?>
<section class="auth-wrap">
    <div class="auth-card">
        <div class="page-crumbs">
            <a href="<?= url('index.php') ?>">Home</a>
            <span>/</span>
            <span>Register</span>
        </div>
        <span class="eyebrow">Create Account</span>
        <h2>Start shopping</h2>
        <form action="<?= url('actions/auth.php') ?>" method="post">
            <input type="hidden" name="action" value="register">
            <div class="form-grid">
                <div class="field">
                    <label>Full name</label>
                    <input class="form-control" type="text" name="name" value="<?= old_value('name') ?>">
                </div>
                <div class="field">
                    <label>Phone</label>
                    <input class="form-control" type="text" name="phone" value="<?= old_value('phone') ?>">
                </div>
            </div>
            <div class="field">
                <label>Email</label>
                <input class="form-control" type="email" name="email" value="<?= old_value('email') ?>">
            </div>
            <div class="field">
                <label>Address</label>
                <textarea class="form-textarea" name="address" rows="3"><?= old_value('address') ?></textarea>
            </div>
            <div class="form-grid">
                <div class="field">
                    <label>City</label>
                    <input class="form-control" type="text" name="city" value="<?= old_value('city') ?>">
                </div>
                <div class="field">
                    <label>Postal code</label>
                    <input class="form-control" type="text" name="postal_code" value="<?= old_value('postal_code') ?>">
                </div>
            </div>
            <div class="form-grid">
                <div class="field">
                    <label>Password</label>
                    <input class="form-control" type="password" name="password">
                </div>
                <div class="field">
                    <label>Confirm password</label>
                    <input class="form-control" type="password" name="confirm_password">
                </div>
            </div>
            <button class="btn full-width" type="submit">Register</button>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
