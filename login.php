<?php
require_once __DIR__ . '/config/bootstrap.php';
if (is_logged_in()) {
    redirect_to('index.php');
}
$pageTitle = page_title('Login');
require_once __DIR__ . '/includes/header.php';
?>
<section class="auth-wrap">
    <div class="auth-card">
        <div class="page-crumbs">
            <a href="<?= url('index.php') ?>">Home</a>
            <span>/</span>
            <span>Login</span>
        </div>
        <span class="eyebrow">Customer Login</span>
        <h2>Welcome back</h2>
        <p class="muted-text">Sign in to manage your cart, checkout, and orders.</p>
        <form action="<?= url('actions/auth.php') ?>" method="post">
            <input type="hidden" name="action" value="login">
            <input type="hidden" name="context" value="customer">
            <div class="field">
                <label>Email</label>
                <input class="form-control" type="email" name="email" value="<?= old_value('email') ?>">
            </div>
            <div class="field">
                <label>Password</label>
                <input class="form-control" type="password" name="password">
            </div>
            <button class="btn full-width" type="submit">Login</button>
        </form>
        <p class="muted-text">Demo customer: customer@techverse.local / Customer@123</p>
        <div class="auth-links"><a href="<?= url('register.php') ?>">Create account</a><a href="<?= url('admin/login.php') ?>">Admin login</a></div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
