<?php
require_once __DIR__ . '/config/bootstrap.php';
require_login();

$user = current_user();
$pageTitle = page_title('My Orders');
$statement = database_connection()->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$statement->execute([$user['id']]);
$orders = $statement->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container">
        <div class="page-crumbs">
            <a href="<?= url('index.php') ?>">Home</a>
            <span>/</span>
            <span>My Orders</span>
        </div>
        <div class="page-banner page-banner-soft">
            <div>
                <span class="eyebrow">Order Center</span>
                <h2>Track purchases, payment verification, and fulfillment progress.</h2>
                <p>Your order history now sits inside the same marketplace-style presentation used across shopping and checkout.</p>
            </div>
            <div class="page-banner-stats">
                <div><strong><?= count($orders) ?></strong><span>Orders</span></div>
            </div>
        </div>
        <div class="section-heading">
            <div>
                <h2>My Orders</h2>
                <p>Track your submitted purchases and payment progress.</p>
            </div>
        </div>
        <?php if (!$orders): ?>
            <div class="empty-state">
                <h3>No orders yet</h3>
                <p>Your future purchases will appear here.</p>
                <a class="btn" href="<?= url('shop.php') ?>">Start shopping</a>
            </div>
        <?php else: ?>
            <div class="table-card">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>Order</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= e($order['order_number']) ?></td>
                            <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                            <td><?= format_price((float) $order['grand_total']) ?></td>
                            <td><span class="<?= payment_status_badge($order['payment_status']) ?>"><?= e(ucfirst($order['payment_status'])) ?></span></td>
                            <td><span class="<?= order_status_badge($order['order_status']) ?>"><?= e(ucfirst($order['order_status'])) ?></span></td>
                            <td><a class="btn-outline" href="<?= url('order_details.php?id=' . (int) $order['id']) ?>">View</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
