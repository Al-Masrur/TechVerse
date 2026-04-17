<?php
require_once __DIR__ . '/config/bootstrap.php';

$pageTitle = page_title('Home');
$featured = featured_products(8);
$latest = latest_products(8);
$categories = fetch_categories();
$brands = fetch_brands();
$heroSlides = array_values(array_slice($featured, 0, min(3, count($featured))));

if (!$heroSlides) {
    $heroSlides = array_values(array_slice($latest, 0, min(3, count($latest))));
}

$categoryShelves = [];
foreach (array_slice($categories, 0, 3) as $category) {
    $items = products_for_category_slug((string) $category['slug'], 4);
    if ($items) {
        $categoryShelves[] = ['category' => $category, 'items' => $items];
    }
}

$brandShowcase = [];
foreach (array_slice($brands, 0, 4) as $brand) {
    $items = products_for_brand_slug((string) $brand['slug'], 3);
    if ($items) {
        $brandShowcase[] = ['brand' => $brand, 'items' => $items];
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <div class="container hero-grid">
        <div class="hero-panel hero-slider" data-hero-slider>
            <?php foreach ($heroSlides as $index => $slide): ?>
                <article class="hero-slide <?= $index === 0 ? 'is-active' : '' ?>" data-hero-slide>
                    <div class="hero-slide-copy">
                        <span class="eyebrow">New trending electronic items</span>
                        <h1><?= e($slide['name']) ?></h1>
                        <p><?= e($slide['short_description']) ?></p>
                        <div class="inline-actions">
                            <a href="<?= url('product.php?slug=' . urlencode($slide['slug'])) ?>" class="btn">View product</a>
                            <a href="<?= url('shop.php?featured=1') ?>" class="btn-outline">See offers</a>
                        </div>
                        <div class="stats-grid">
                            <div class="stat-box"><strong><?= format_price((float) $slide['price']) ?></strong><span>Current price</span></div>
                            <div class="stat-box"><strong><?= product_discount($slide) ?>%</strong><span>Offer value</span></div>
                            <div class="stat-box"><strong><?= (int) $slide['stock'] ?></strong><span>In stock</span></div>
                        </div>
                    </div>
                    <div class="hero-slide-media">
                        <img src="<?= asset(product_image_path($slide)) ?>" alt="<?= e($slide['name']) ?>">
                    </div>
                </article>
            <?php endforeach; ?>
            <div class="hero-slider-dots">
                <?php foreach ($heroSlides as $index => $slide): ?>
                    <button type="button" class="hero-dot <?= $index === 0 ? 'is-active' : '' ?>" data-hero-dot="<?= $index ?>" aria-label="Show slide <?= $index + 1 ?>"></button>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="hero-side">
            <div class="promo-card primary">
                <span class="eyebrow">Flash Tech Drop</span>
                <strong>Save up to 28%</strong>
                <p>Featured devices, cleaner cards, bolder catalog sections, and front-page merchandising that actually feels premium.</p>
                <a class="btn-outline hero-ghost-button" href="<?= url('shop.php?featured=1') ?>">Browse featured</a>
            </div>
            <div class="promo-card secondary">
                <span class="eyebrow">Manual Payment Ready</span>
                <strong>bKash, Nagad, Rocket</strong>
                <p>Manual verification with sender number and transaction ID, wrapped in a cleaner, higher-end checkout presentation.</p>
            </div>
        </div>
    </div>
    <div class="container trust-strip">
        <div class="trust-item">100% genuine IT products</div>
        <div class="trust-item">Fast citywide delivery</div>
        <div class="trust-item">Admin-managed stock control</div>
        <div class="trust-item">Secure hashed logins</div>
    </div>
</section>

<section class="section">
    <div class="container showcase-grid">
        <article class="showcase-card showcase-card-wide">
            <span class="eyebrow eyebrow-soft">Marketplace Vision</span>
            <h3>Sharper discovery, cleaner hierarchy, stronger visual merchandising.</h3>
            <p>Everything from product rails to category links is rebalanced to feel more like a polished multi-vendor marketplace than a lab template.</p>
        </article>
        <article class="showcase-card">
            <span class="eyebrow eyebrow-soft">Fast Filters</span>
            <h3>Search, sort, and price controls</h3>
            <p>Shop pages now support better discovery with stronger visual grouping.</p>
        </article>
        <article class="showcase-card">
            <span class="eyebrow eyebrow-soft">Admin Ready</span>
            <h3>Clean management surface</h3>
            <p>Product management now feels closer to a real dashboard with previews and richer tables.</p>
        </article>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading">
            <div>
                <h2>Shop by Categories</h2>
                <p>Browse the core IT product lines this store supports.</p>
            </div>
            <a href="<?= url('shop.php') ?>" class="btn-outline">View all</a>
        </div>
        <div class="grid categories-grid">
            <?php foreach ($categories as $category): ?>
                <?php $visual = category_visuals((string) $category['slug']); ?>
                <a href="<?= url('shop.php?category=' . urlencode($category['slug'])) ?>" class="category-card">
                    <div class="category-card-visual" style="background: <?= e($visual['accent']) ?>;">
                        <img src="<?= asset(category_image_path($category)) ?>" alt="<?= e($category['name']) ?>">
                    </div>
                    <div class="category-card-copy">
                        <span class="eyebrow eyebrow-soft"><?= e($category['icon'] ?: 'Category') ?></span>
                        <h3><?= e($category['name']) ?></h3>
                        <small><?= e($category['description']) ?></small>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container promo-banner-grid">
        <article class="promo-banner promo-banner-large">
            <div>
                <span class="eyebrow">Marketplace Deals</span>
                <h3>Laptops, phones, and wearables with stronger campaign-style presentation.</h3>
                <p>Bring the front page closer to a real gadget marketplace with image-led offers and cleaner merchandising blocks.</p>
                <a class="btn" href="<?= url('shop.php?featured=1') ?>">Explore offers</a>
            </div>
            <img src="<?= asset('assets/images/placeholder-laptop.svg') ?>" alt="Featured laptop deal">
        </article>
        <article class="promo-banner promo-banner-small">
            <div>
                <span class="eyebrow eyebrow-soft">Category Focus</span>
                <h3>Shop by top gadget lines</h3>
                <p>Phones, laptops, watches, audio, and accessories with a more visual catalog feel.</p>
            </div>
        </article>
    </div>
</section>

<?php foreach ($categoryShelves as $shelf): ?>
    <?php $visual = category_visuals((string) $shelf['category']['slug']); ?>
    <section class="section">
        <div class="container category-shelf">
            <aside class="category-shelf-banner" style="background: <?= e($visual['accent']) ?>;">
                <img src="<?= asset(category_image_path($shelf['category'])) ?>" alt="<?= e($shelf['category']['name']) ?>">
                <div>
                    <span class="eyebrow eyebrow-soft"><?= e($shelf['category']['icon'] ?: 'Category') ?></span>
                    <h3><?= e($shelf['category']['name']) ?></h3>
                    <p><?= e($shelf['category']['description']) ?></p>
                    <a class="btn-outline" href="<?= url('shop.php?category=' . urlencode($shelf['category']['slug'])) ?>">Explore <?= e($shelf['category']['name']) ?></a>
                </div>
            </aside>
            <div class="category-shelf-products">
                <?php foreach ($shelf['items'] as $product): ?>
                    <article class="product-card product-card-premium product-card-catalog product-card-clickable" data-card-url="<?= url('product.php?slug=' . urlencode($product['slug'])) ?>" tabindex="0" aria-label="View <?= e($product['name']) ?>">
                        <div class="product-card-media">
                            <?php if (product_discount($product) > 0): ?><span class="discount-badge"><?= product_discount($product) ?>% OFF</span><?php endif; ?>
                            <img src="<?= asset(product_image_path($product, (string) ($product['category_slug'] ?? ''))) ?>" alt="<?= e($product['name']) ?>">
                        </div>
                        <div class="product-card-body">
                            <div class="product-card-topline">
                                <span class="product-chip"><?= e($product['brand_name'] ?: 'Brand') ?></span>
                                <span class="product-chip subtle"><?= e($product['category_name'] ?: 'Category') ?></span>
                            </div>
                            <h3><a href="<?= url('product.php?slug=' . urlencode($product['slug'])) ?>"><?= e($product['name']) ?></a></h3>
                            <div class="price-row">
                                <strong><?= format_price((float) $product['price']) ?></strong>
                                <?php if ((float) $product['old_price'] > 0): ?><span class="old-price"><?= format_price((float) $product['old_price']) ?></span><?php endif; ?>
                            </div>
                            <div class="product-card-actions">
                                <a class="btn-outline full-width" href="<?= url('product.php?slug=' . urlencode($product['slug'])) ?>">View product</a>
                                <form action="<?= url('actions/cart.php') ?>" method="post">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                                    <input type="hidden" name="product_slug" value="<?= e($product['slug']) ?>">
                                    <button class="btn full-width" type="submit">Add to cart</button>
                                </form>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endforeach; ?>

<?php if ($brandShowcase): ?>
<section class="section">
    <div class="container">
        <div class="section-heading">
            <div>
                <h2>Popular Brands</h2>
                <p>Brand-led discovery with relevant products instead of plain text-only links.</p>
            </div>
        </div>
        <div class="brand-showcase-grid">
            <?php foreach ($brandShowcase as $brandBlock): ?>
                <article class="brand-showcase-card">
                    <div class="brand-showcase-head">
                        <img src="<?= asset(brand_visual((string) $brandBlock['brand']['slug'])) ?>" alt="<?= e($brandBlock['brand']['name']) ?>">
                        <div>
                            <span class="eyebrow"><?= e($brandBlock['brand']['name']) ?></span>
                            <h3><?= e($brandBlock['brand']['name']) ?></h3>
                            <p><?= e($brandBlock['brand']['description']) ?></p>
                        </div>
                    </div>
                    <div class="brand-mini-list">
                        <?php foreach ($brandBlock['items'] as $item): ?>
                            <a href="<?= url('product.php?slug=' . urlencode($item['slug'])) ?>" class="brand-mini-item">
                                <img src="<?= asset(product_image_path($item, (string) ($item['category_slug'] ?? ''))) ?>" alt="<?= e($item['name']) ?>">
                                <div>
                                    <strong><?= e($item['name']) ?></strong>
                                    <span><?= format_price((float) $item['price']) ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <a class="btn-outline full-width" href="<?= url('shop.php?brand=' . urlencode($brandBlock['brand']['slug'])) ?>">Browse <?= e($brandBlock['brand']['name']) ?></a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section">
    <div class="container">
        <div class="section-heading">
            <div>
                <h2>Featured Products</h2>
                <p>Premium picks with stronger pricing and homepage priority.</p>
            </div>
        </div>
        <div class="grid products-grid">
            <?php foreach ($featured as $product): ?>
                <article class="product-card product-card-premium product-card-home product-card-clickable" data-card-url="<?= url('product.php?slug=' . urlencode($product['slug'])) ?>" tabindex="0" aria-label="View <?= e($product['name']) ?>">
                    <div class="product-card-media">
                        <?php if (product_discount($product) > 0): ?><span class="discount-badge"><?= product_discount($product) ?>% OFF</span><?php endif; ?>
                        <span class="stock-badge"><?= (int) $product['stock'] ?> in stock</span>
                        <img src="<?= asset(product_image_path($product)) ?>" alt="<?= e($product['name']) ?>">
                    </div>
                    <div class="product-card-body">
                        <div class="product-card-topline">
                            <span class="product-chip"><?= e($product['brand_name']) ?></span>
                            <span class="product-chip subtle"><?= e($product['category_name']) ?></span>
                        </div>
                        <h3><a href="<?= url('product.php?slug=' . urlencode($product['slug'])) ?>"><?= e($product['name']) ?></a></h3>
                        <div class="price-row">
                            <strong><?= format_price((float) $product['price']) ?></strong>
                            <?php if ((float) $product['old_price'] > 0): ?><span class="old-price"><?= format_price((float) $product['old_price']) ?></span><?php endif; ?>
                        </div>
                        <div class="product-card-actions">
                            <a class="btn-outline full-width" href="<?= url('product.php?slug=' . urlencode($product['slug'])) ?>">View product</a>
                            <form action="<?= url('actions/cart.php') ?>" method="post">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                                <input type="hidden" name="product_slug" value="<?= e($product['slug']) ?>">
                                <button class="btn full-width" type="submit">Add to cart</button>
                            </form>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading">
            <div>
                <h2>Latest Products</h2>
                <p>New arrivals across phones, laptops, audio, and accessories.</p>
            </div>
        </div>
        <div class="grid products-grid">
            <?php foreach ($latest as $product): ?>
                <article class="product-card product-card-premium product-card-home product-card-clickable" data-card-url="<?= url('product.php?slug=' . urlencode($product['slug'])) ?>" tabindex="0" aria-label="View <?= e($product['name']) ?>">
                    <div class="product-card-media">
                        <?php if (product_discount($product) > 0): ?><span class="discount-badge"><?= product_discount($product) ?>% OFF</span><?php endif; ?>
                        <img src="<?= asset(product_image_path($product)) ?>" alt="<?= e($product['name']) ?>">
                    </div>
                    <div class="product-card-body">
                        <div class="product-card-topline">
                            <span class="product-chip"><?= e($product['brand_name']) ?></span>
                            <span class="product-chip subtle">New arrival</span>
                        </div>
                        <h3><a href="<?= url('product.php?slug=' . urlencode($product['slug'])) ?>"><?= e($product['name']) ?></a></h3>
                        <div class="price-row">
                            <strong><?= format_price((float) $product['price']) ?></strong>
                            <?php if ((float) $product['old_price'] > 0): ?><span class="old-price"><?= format_price((float) $product['old_price']) ?></span><?php endif; ?>
                        </div>
                        <div class="product-card-actions">
                            <a class="btn-outline full-width" href="<?= url('product.php?slug=' . urlencode($product['slug'])) ?>">View product</a>
                            <form action="<?= url('actions/cart.php') ?>" method="post">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                                <input type="hidden" name="product_slug" value="<?= e($product['slug']) ?>">
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
