<?php
require_once __DIR__ . '/config/bootstrap.php';
require_login();

$user = current_user();
$pageTitle = page_title('Checkout');
$totals = cart_totals((int) $user['id']);

if (!$totals['count']) {
    set_flash('warning', 'Add products before checkout.');
    redirect_to('cart.php');
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container">
        <div class="page-crumbs">
            <a href="<?= url('index.php') ?>">Home</a>
            <span>/</span>
            <a href="<?= url('cart.php') ?>">Cart</a>
            <span>/</span>
            <span>Checkout</span>
        </div>
        <div class="page-banner page-banner-soft">
            <div>
                <span class="eyebrow">Checkout Desk</span>
                <h2>Complete shipping, payment, and order confirmation in one flow.</h2>
                <p>Manual mobile payment support and order summary stay visible for a smoother premium checkout experience.</p>
            </div>
            <div class="page-banner-stats">
                <div><strong><?= $totals['count'] ?></strong><span>Items</span></div>
                <div><strong><?= format_price($totals['grand_total']) ?></strong><span>Payable</span></div>
            </div>
        </div>
        <div class="section-heading">
            <div>
                <h2>Checkout</h2>
                <p>Complete your shipping details and choose a payment method.</p>
            </div>
        </div>
        <div class="checkout-grid">
            <div class="section-card">
                <form action="<?= url('actions/checkout.php') ?>" method="post">
                    <div class="form-grid">
                        <div class="field">
                            <label>Full name</label>
                            <input class="form-control" type="text" name="customer_name" value="<?= old_value('customer_name', $user['name']) ?>">
                        </div>
                        <div class="field">
                            <label>Email</label>
                            <input class="form-control" type="email" name="customer_email" value="<?= old_value('customer_email', $user['email']) ?>">
                        </div>
                        <div class="field">
                            <label>Phone</label>
                            <input class="form-control" type="text" name="customer_phone" value="<?= old_value('customer_phone', $user['phone'] ?? '') ?>">
                        </div>
                        <div class="field">
                            <label>City</label>
                            <input class="form-control" type="text" name="city" value="<?= old_value('city', $user['city'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="field">
                        <label>Shipping address</label>
                        <textarea class="form-textarea" name="shipping_address" rows="4"><?= old_value('shipping_address', $user['address'] ?? '') ?></textarea>
                    </div>
                    <div class="field">
                        <label>Billing address</label>
                        <textarea class="form-textarea" name="billing_address" rows="4"><?= old_value('billing_address', $user['address'] ?? '') ?></textarea>
                    </div>
                    <div class="form-grid">
                        <div class="field">
                            <label>Postal code</label>
                            <input class="form-control" type="text" name="postal_code" value="<?= old_value('postal_code', $user['postal_code'] ?? '') ?>">
                        </div>
                        <div class="field">
                            <label>Payment method</label>
                            <select class="form-select" name="payment_method" data-payment-method>
                                <?php foreach (payment_methods() as $key => $label): ?>
                                    <option value="<?= e($key) ?>" <?= old_value('payment_method', 'cod') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="section-card" data-manual-payment>
                        <h3>Manual Payment Details</h3>
                        <div class="form-grid">
                            <div class="field">
                                <label>Sender number</label>
                                <input class="form-control" type="text" name="sender_number" value="<?= old_value('sender_number') ?>">
                            </div>
                            <div class="field">
                                <label>Transaction ID</label>
                                <input class="form-control" type="text" name="transaction_id" value="<?= old_value('transaction_id') ?>">
                            </div>
                        </div>
                        <small>Required for bKash, Nagad, and Rocket.</small>
                    </div>
                    <div class="field">
                        <label>Order notes</label>
                        <textarea class="form-textarea" name="notes" rows="4"><?= old_value('notes') ?></textarea>
                    </div>
                    <button class="btn full-width" type="submit">Place order</button>
                </form>
            </div>
            <aside class="summary-box">
                <h3>Items Summary</h3>
                <?php foreach ($totals['items'] as $item): ?>
                    <div class="order-summary-line">
                        <span><?= e($item['name']) ?> x <?= (int) $item['quantity'] ?></span>
                        <strong><?= format_price((float) $item['price'] * (int) $item['quantity']) ?></strong>
                    </div>
                <?php endforeach; ?>
                <div class="order-summary-line"><span>Subtotal</span><strong><?= format_price($totals['subtotal']) ?></strong></div>
                <div class="order-summary-line"><span>Shipping</span><strong><?= format_price($totals['shipping']) ?></strong></div>
                <div class="order-summary-line"><span>Total</span><strong><?= format_price($totals['grand_total']) ?></strong></div>
            </aside>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
