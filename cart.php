<?php
require_once __DIR__ . '/config/bootstrap.php';
require_login();

$user = current_user();
$pageTitle = page_title('Cart');
$totals = cart_totals((int) $user['id']);

require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container">
        <div class="page-crumbs">
            <a href="<?= url('index.php') ?>">Home</a>
            <span>/</span>
            <span>Cart</span>
        </div>
        <div class="page-banner page-banner-soft">
            <div>
                <span class="eyebrow">Shopping Cart</span>
                <h2>Review your selected products before checkout.</h2>
                <p>Update quantities, remove items, and continue through the same premium retail flow across the full storefront.</p>
            </div>
            <div class="page-banner-stats">
                <div><strong><?= $totals['count'] ?></strong><span>Items</span></div>
                <div><strong><?= format_price($totals['grand_total']) ?></strong><span>Total</span></div>
            </div>
        </div>
        <div class="section-heading">
            <div>
                <h2>Your Cart</h2>
                <p>Review items, update quantities, and continue to checkout.</p>
            </div>
        </div>
        <?php if (!$totals['count']): ?>
            <div class="empty-state">
                <h3>Your cart is empty</h3>
                <p>Add a few devices to continue shopping.</p>
                <a class="btn" href="<?= url('shop.php') ?>">Browse products</a>
            </div>
        <?php else: ?>
            <div class="checkout-grid">
                <div class="table-card">
                    <form action="<?= url('actions/cart.php') ?>" method="post">
                        <input type="hidden" name="action" value="update">
                        <table class="cart-table">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Remove</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($totals['items'] as $item): ?>
                                <tr>
                                    <td>
                                        <div class="cart-line">
                                            <img src="<?= asset($item['primary_image'] ?: 'assets/images/placeholder-phone.svg') ?>" alt="<?= e($item['name']) ?>" style="width:72px;height:72px;object-fit:contain;">
                                            <div>
                                                <strong><?= e($item['name']) ?></strong>
                                                <div class="meta-line">Stock: <?= (int) $item['stock'] ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= format_price((float) $item['price']) ?></td>
                                    <td><input class="form-control" style="max-width:110px" type="number" min="0" max="<?= (int) $item['stock'] ?>" name="quantities[<?= (int) $item['id'] ?>]" value="<?= (int) $item['quantity'] ?>"></td>
                                    <td><?= format_price((float) $item['price'] * (int) $item['quantity']) ?></td>
                                    <td>
                                        <button class="btn-danger" formaction="<?= url('actions/cart.php') ?>" formmethod="post" type="submit" name="remove_item_id" value="<?= (int) $item['id'] ?>">Remove</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div style="margin-top:20px" class="inline-actions">
                            <button class="btn" type="submit">Update cart</button>
                            <a class="btn-outline" href="<?= url('checkout.php') ?>">Checkout</a>
                        </div>
                    </form>
                </div>
                <aside class="summary-box">
                    <h3>Order Summary</h3>
                    <div class="order-summary-line"><span>Subtotal</span><strong><?= format_price($totals['subtotal']) ?></strong></div>
                    <div class="order-summary-line"><span>Shipping</span><strong><?= format_price($totals['shipping']) ?></strong></div>
                    <div class="order-summary-line"><span>Grand total</span><strong><?= format_price($totals['grand_total']) ?></strong></div>
                    <a class="btn full-width" href="<?= url('checkout.php') ?>">Proceed to checkout</a>
                </aside>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
