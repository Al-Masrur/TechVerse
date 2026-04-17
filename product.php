<?php
require_once __DIR__ . '/config/bootstrap.php';

$slug = (string) ($_GET['slug'] ?? '');
$product = product_by_slug_or_id($slug);

if (!$product || !(int) $product['is_active']) {
    set_flash('danger', 'Product not found.');
    redirect_to('shop.php');
}

$pageTitle = page_title($product['name']);
$relatedStatement = database_connection()->prepare(
    'SELECT p.*, b.name AS brand_name,
            (SELECT image_path FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, id ASC LIMIT 1) AS primary_image
     FROM products p
     LEFT JOIN brands b ON b.id = p.brand_id
     WHERE p.category_id = ? AND p.id != ? AND p.is_active = 1
     ORDER BY p.created_at DESC
     LIMIT 4'
);
$relatedStatement->execute([$product['category_id'], $product['id']]);
$relatedProducts = $relatedStatement->fetchAll();

require_once __DIR__ . '/includes/header.php';
$primaryImage = $product['images'][0]['image_path'] ?? product_image_path($product, (string) ($product['category_slug'] ?? ''));
?>
<section class="section">
    <div class="container product-detail-grid">
        <div>
            <div class="page-crumbs">
                <a href="<?= url('index.php') ?>">Home</a>
                <span>/</span>
                <a href="<?= url('shop.php') ?>">Shop</a>
                <span>/</span>
                <span><?= e($product['category_name']) ?></span>
            </div>
            <div class="product-gallery-header">
                <span class="eyebrow eyebrow-soft">Product Gallery</span>
                <span class="meta-line">Immersive preview panel</span>
            </div>
            <div class="gallery-main">
                <img src="<?= asset($primaryImage) ?>" alt="<?= e($product['name']) ?>" data-gallery-main>
            </div>
            <div class="gallery-thumbs">
                <?php foreach ($product['images'] as $image): ?>
                    <button class="gallery-thumb" type="button" data-thumb="<?= asset($image['image_path']) ?>">
                        <img src="<?= asset($image['image_path']) ?>" alt="<?= e($product['name']) ?>">
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="detail-card">
            <div class="product-card-topline" style="margin-bottom:14px;">
                <span class="product-chip"><?= e($product['brand_name']) ?></span>
                <span class="product-chip subtle"><?= e($product['category_name']) ?></span>
            </div>
            <h1><?= e($product['name']) ?></h1>
            <p class="muted-text"><?= e($product['short_description']) ?></p>
            <div class="price-row">
                <strong><?= format_price((float) $product['price']) ?></strong>
                <?php if ((float) $product['old_price'] > 0): ?><span class="old-price"><?= format_price((float) $product['old_price']) ?></span><?php endif; ?>
                <?php if (product_discount($product) > 0): ?><span class="badge badge-info"><?= product_discount($product) ?>% OFF</span><?php endif; ?>
            </div>
            <div class="product-highlights">
                <span>SKU: <?= e($product['sku']) ?></span>
                <span>Stock: <?= (int) $product['stock'] ?></span>
                <span>Secure checkout</span>
            </div>
            <p><?= nl2br(e($product['description'])) ?></p>
            <form action="<?= url('actions/cart.php') ?>" method="post" class="form-grid">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                <input type="hidden" name="product_slug" value="<?= e($product['slug']) ?>">
                <div class="field">
                    <label>Quantity</label>
                    <input class="form-control" type="number" name="quantity" min="1" max="<?= (int) $product['stock'] ?>" value="1">
                </div>
                <div class="field" style="display:flex;align-items:end;">
                    <button class="btn full-width" type="submit">Add to cart</button>
                </div>
            </form>
            <div class="section-card">
                <h3>Key Specifications</h3>
                <div class="spec-list">
                    <?php foreach (product_specs($product) as $label => $value): ?>
                        <div class="spec-row">
                            <span class="muted-text"><?= e($label) ?></span>
                            <strong><?= e($value) ?></strong>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading"><div><h2>Related Products</h2></div></div>
        <div class="grid products-grid related-grid">
            <?php foreach ($relatedProducts as $item): ?>
                <article class="product-card product-card-premium product-card-compact product-card-clickable" data-card-url="<?= url('product.php?slug=' . urlencode($item['slug'])) ?>" tabindex="0" aria-label="View <?= e($item['name']) ?>">
                    <div class="product-card-media">
                        <img src="<?= asset(product_image_path($item)) ?>" alt="<?= e($item['name']) ?>">
                    </div>
                    <div class="product-card-body">
                        <div class="product-card-topline"><span class="product-chip"><?= e($item['brand_name']) ?></span></div>
                        <h3><a href="<?= url('product.php?slug=' . urlencode($item['slug'])) ?>"><?= e($item['name']) ?></a></h3>
                        <div class="price-row"><strong><?= format_price((float) $item['price']) ?></strong></div>
                        <div class="product-card-actions">
                            <a class="btn-outline full-width" href="<?= url('product.php?slug=' . urlencode($item['slug'])) ?>">View product</a>
                            <form action="<?= url('actions/cart.php') ?>" method="post">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?= (int) $item['id'] ?>">
                                <input type="hidden" name="product_slug" value="<?= e($item['slug']) ?>">
                                <button class="btn full-width" type="submit">Add to cart</button>
                            </form>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
