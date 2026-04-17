<?php
require_once __DIR__ . '/config/bootstrap.php';
require_login();

$user = current_user();
$orderId = (int) ($_GET['id'] ?? 0);
$pdo = database_connection();

$statement = $pdo->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ? LIMIT 1');
$statement->execute([$orderId, $user['id']]);
$order = $statement->fetch();

if (!$order) {
    set_flash('danger', 'Order not found.');
    redirect_to('orders.php');
}

$itemStatement = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ? ORDER BY id ASC');
$itemStatement->execute([$orderId]);
$items = $itemStatement->fetchAll();

$paymentStatement = $pdo->prepare('SELECT * FROM payments WHERE order_id = ? LIMIT 1');
$paymentStatement->execute([$orderId]);
$payment = $paymentStatement->fetch();

$pageTitle = page_title('Order Details');
require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container checkout-grid">
        <div class="section-card">
            <div class="page-crumbs">
                <a href="<?= url('index.php') ?>">Home</a>
                <span>/</span>
                <a href="<?= url('orders.php') ?>">My Orders</a>
                <span>/</span>
                <span><?= e($order['order_number']) ?></span>
            </div>
            <span class="eyebrow">Order <?= e($order['order_number']) ?></span>
            <h2>Order Details</h2>
            <div class="spec-list">
                <div class="spec-row"><span>Placed on</span><strong><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></strong></div>
                <div class="spec-row"><span>Order status</span><strong><span class="<?= order_status_badge($order['order_status']) ?>"><?= e(ucfirst($order['order_status'])) ?></span></strong></div>
                <div class="spec-row"><span>Payment status</span><strong><span class="<?= payment_status_badge($order['payment_status']) ?>"><?= e(ucfirst($order['payment_status'])) ?></span></strong></div>
                <div class="spec-row"><span>Payment method</span><strong><?= e(payment_methods()[$order['payment_method']] ?? strtoupper($order['payment_method'])) ?></strong></div>
            </div>
            <div class="table-card" style="margin-top:24px;">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= e($item['product_name']) ?></td>
                            <td><?= format_price((float) $item['product_price']) ?></td>
                            <td><?= (int) $item['quantity'] ?></td>
                            <td><?= format_price((float) $item['total']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <aside class="summary-box">
            <h3>Billing Summary</h3>
            <div class="order-summary-line"><span>Subtotal</span><strong><?= format_price((float) $order['subtotal']) ?></strong></div>
            <div class="order-summary-line"><span>Shipping</span><strong><?= format_price((float) $order['shipping_cost']) ?></strong></div>
            <div class="order-summary-line"><span>Total</span><strong><?= format_price((float) $order['grand_total']) ?></strong></div>
            <hr>
            <h4>Shipping</h4>
            <p class="muted-text"><?= nl2br(e($order['shipping_address'])) ?></p>
            <p class="muted-text"><?= e($order['city']) ?> <?= e($order['postal_code']) ?></p>
            <?php if ($payment): ?>
                <hr>
                <h4>Payment Reference</h4>
                <p class="muted-text">Sender: <?= e($payment['sender_number'] ?: 'N/A') ?></p>
                <p class="muted-text">Transaction: <?= e($payment['transaction_id'] ?: 'N/A') ?></p>
            <?php endif; ?>
        </aside>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
