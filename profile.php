<?php
require_once __DIR__ . '/config/bootstrap.php';
require_login();

$user = current_user();
$pageTitle = page_title('Profile');
require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container profile-grid">
        <div class="info-card">
            <div class="page-crumbs">
                <a href="<?= url('index.php') ?>">Home</a>
                <span>/</span>
                <span>Profile</span>
            </div>
            <span class="eyebrow">Account Overview</span>
            <h2><?= e($user['name']) ?></h2>
            <p class="muted-text"><?= e($user['email']) ?></p>
            <div class="spec-list">
                <div class="spec-row"><span>Phone</span><strong><?= e($user['phone'] ?: 'Not added') ?></strong></div>
                <div class="spec-row"><span>Role</span><strong><?= e(ucwords(str_replace('_', ' ', $user['role']))) ?></strong></div>
                <div class="spec-row"><span>City</span><strong><?= e($user['city'] ?: 'Not added') ?></strong></div>
            </div>
        </div>
        <div>
            <div class="section-card">
                <h3>Profile Information</h3>
                <form action="<?= url('actions/profile.php') ?>" method="post">
                    <input type="hidden" name="action" value="update_profile">
                    <div class="form-grid">
                        <div class="field">
                            <label>Full name</label>
                            <input class="form-control" type="text" name="name" value="<?= old_value('name', $user['name']) ?>">
                        </div>
                        <div class="field">
                            <label>Email</label>
                            <input class="form-control" type="email" name="email" value="<?= old_value('email', $user['email']) ?>">
                        </div>
                        <div class="field">
                            <label>Phone</label>
                            <input class="form-control" type="text" name="phone" value="<?= old_value('phone', $user['phone'] ?? '') ?>">
                        </div>
                        <div class="field">
                            <label>City</label>
                            <input class="form-control" type="text" name="city" value="<?= old_value('city', $user['city'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="field">
                        <label>Address</label>
                        <textarea class="form-textarea" name="address" rows="4"><?= old_value('address', $user['address'] ?? '') ?></textarea>
                    </div>
                    <div class="field">
                        <label>Postal code</label>
                        <input class="form-control" type="text" name="postal_code" value="<?= old_value('postal_code', $user['postal_code'] ?? '') ?>">
                    </div>
                    <button class="btn" type="submit">Save changes</button>
                </form>
            </div>
            <div class="section-card" style="margin-top:24px;">
                <h3>Change Password</h3>
                <form action="<?= url('actions/profile.php') ?>" method="post">
                    <input type="hidden" name="action" value="change_password">
                    <div class="field">
                        <label>Current password</label>
                        <input class="form-control" type="password" name="current_password">
                    </div>
                    <div class="form-grid">
                        <div class="field">
                            <label>New password</label>
                            <input class="form-control" type="password" name="new_password">
                        </div>
                        <div class="field">
                            <label>Confirm new password</label>
                            <input class="form-control" type="password" name="confirm_password">
                        </div>
                    </div>
                    <button class="btn-outline" type="submit">Update password</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
