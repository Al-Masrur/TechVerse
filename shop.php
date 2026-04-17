<?php
require_once __DIR__ . '/config/bootstrap.php';

$pdo = database_connection();
$pageTitle = page_title('Shop');
$categories = fetch_categories();
$brands = fetch_brands();

$conditions = ['p.is_active = 1'];
$params = [];

$query = trim($_GET['q'] ?? '');
$category = trim($_GET['category'] ?? '');
$brand = trim($_GET['brand'] ?? '');
$featured = (int) ($_GET['featured'] ?? 0);
$sort = $_GET['sort'] ?? 'latest';
$minPrice = trim($_GET['min_price'] ?? '');
$maxPrice = trim($_GET['max_price'] ?? '');

if ($query !== '') {
    $conditions[] = '(p.name LIKE ? OR p.short_description LIKE ? OR p.description LIKE ?)';
    $like = '%' . $query . '%';
    array_push($params, $like, $like, $like);
}

if ($category !== '') {
    $conditions[] = 'c.slug = ?';
    $params[] = $category;
}

if ($brand !== '') {
    $conditions[] = 'b.slug = ?';
    $params[] = $brand;
}

if ($featured === 1) {
    $conditions[] = 'p.is_featured = 1';
}

if ($minPrice !== '' && is_numeric($minPrice)) {
    $conditions[] = 'p.price >= ?';
    $params[] = (float) $minPrice;
}

if ($maxPrice !== '' && is_numeric($maxPrice)) {
    $conditions[] = 'p.price <= ?';
    $params[] = (float) $maxPrice;
}

$orderBy = match ($sort) {
    'price_low' => 'p.price ASC',
    'price_high' => 'p.price DESC',
    'name' => 'p.name ASC',
    default => 'p.created_at DESC',
};

$sql = 'SELECT p.*, c.name AS category_name, c.slug AS category_slug, b.name AS brand_name, b.slug AS brand_slug,
               (SELECT image_path FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, id ASC LIMIT 1) AS primary_image
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
        LEFT JOIN brands b ON b.id = p.brand_id
        WHERE ' . implode(' AND ', $conditions) . '
        ORDER BY ' . $orderBy;
$statement = $pdo->prepare($sql);
$statement->execute($params);
$products = $statement->fetchAll();

$selectedCategory = null;
$selectedBrand = null;
$contextProducts = [];

if ($category !== '') {
    foreach ($categories as $item) {
        if ($item['slug'] === $category) {
            $selectedCategory = $item;
            $contextProducts = products_for_category_slug($category, 4);
            break;
        }
    }
}

if ($brand !== '') {
    foreach ($brands as $item) {
        if ($item['slug'] === $brand) {
            $selectedBrand = $item;
            $contextProducts = products_for_brand_slug($brand, 4);
            break;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="shop-hero section">
    <div class="container">
        <div class="page-crumbs">
            <a href="<?= url('index.php') ?>">Home</a>
            <span>/</span>
            <span>Shop</span>
            <?php if ($category !== ''): ?><span>/</span><span><?= e(ucfirst(str_replace('-', ' ', $category))) ?></span><?php endif; ?>
        </div>
        <div class="section-heading shop-heading">
            <div>
                <span class="eyebrow">Curated IT Catalogue</span>
                <h2>
                    <?php if ($selectedCategory): ?>
                        <?= e($selectedCategory['name']) ?> collection with relevant products and cleaner browsing.
                    <?php elseif ($selectedBrand): ?>
                        <?= e($selectedBrand['name']) ?> lineup with highlighted products and stronger discovery.
                    <?php else: ?>
                        Explore premium devices with a cleaner, marketplace-style discovery flow.
                    <?php endif; ?>
                </h2>
                <p>
                    <?php if ($selectedCategory): ?>
                        <?= e($selectedCategory['description']) ?>
                    <?php elseif ($selectedBrand): ?>
                        <?= e($selectedBrand['description']) ?>
                    <?php else: ?>
                        Sharper search, layered filters, richer product cards, and a more polished browsing rhythm from top to bottom.
                    <?php endif; ?>
                </p>
            </div>
            <div class="results-panel">
                <strong><?= count($products) ?></strong>
                <span>Products found</span>
            </div>
        </div>
        <?php if ($selectedCategory || $selectedBrand): ?>
            <div class="context-strip">
                <?php foreach ($contextProducts as $contextProduct): ?>
                    <a class="context-product" href="<?= url('product.php?slug=' . urlencode($contextProduct['slug'])) ?>">
                        <img src="<?= asset(product_image_path($contextProduct, (string) ($contextProduct['category_slug'] ?? ''))) ?>" alt="<?= e($contextProduct['name']) ?>">
                        <div>
                            <strong><?= e($contextProduct['name']) ?></strong>
                            <span><?= format_price((float) $contextProduct['price']) ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="shop-layout">
            <aside class="sidebar-filter">
                <div class="filter-intro">
                    <h3>Refine Selection</h3>
                    <p>Use premium discovery controls to narrow by search, category, brand, price, and sort order.</p>
                </div>
                <form action="<?= url('shop.php') ?>" method="get" class="filter-form">
                    <div class="filter-group">
                        <label>Search product</label>
                        <input class="form-control" type="search" name="q" placeholder="Search phones, laptops, audio..." value="<?= e($query) ?>">
                    </div>
                    <div class="filter-group">
                        <label>Category</label>
                        <select class="form-select" name="category">
                            <option value="">All categories</option>
                            <?php foreach ($categories as $item): ?>
                                <option value="<?= e($item['slug']) ?>" <?= $category === $item['slug'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Brand</label>
                        <select class="form-select" name="brand">
                            <option value="">All brands</option>
                            <?php foreach ($brands as $item): ?>
                                <option value="<?= e($item['slug']) ?>" <?= $brand === $item['slug'] ? 'selected' : '' ?>><?= e($item['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Sort by</label>
                        <select class="form-select" name="sort">
                            <option value="latest" <?= $sort === 'latest' ? 'selected' : '' ?>>Latest</option>
                            <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Price low to high</option>
                            <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Price high to low</option>
                            <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Name</option>
                        </select>
                    </div>
                    <div class="form-grid">
                        <div class="field">
                            <label>Min price</label>
                            <input class="form-control" type="number" name="min_price" min="0" value="<?= e($minPrice) ?>">
                        </div>
                        <div class="field">
                            <label>Max price</label>
                            <input class="form-control" type="number" name="max_price" min="0" value="<?= e($maxPrice) ?>">
                        </div>
                    </div>
                    <?php if ($featured === 1): ?>
                        <input type="hidden" name="featured" value="1">
                    <?php endif; ?>
                    <div class="filter-actions">
                        <button class="btn full-width" type="submit">Apply filters</button>
                        <a class="btn-outline full-width" href="<?= url('shop.php') ?>">Reset</a>
                    </div>
                </form>
                <div class="filter-quick-links">
                    <?php foreach (array_slice($categories, 0, 6) as $item): ?>
                        <a href="<?= url('shop.php?category=' . urlencode($item['slug'])) ?>"><?= e($item['name']) ?></a>
                    <?php endforeach; ?>
                </div>
            </aside>
            <div>
                <div class="catalog-toolbar">
                    <div class="catalog-toolbar-copy">
                        <strong>Marketplace Feed</strong>
                        <span>Balanced for premium product browsing.</span>
                    </div>
                    <div class="catalog-toolbar-pills">
                        <span>Curated</span>
                        <span>Responsive</span>
                        <span>High intent</span>
                    </div>
                </div>
                <?php if (!$products): ?>
                    <div class="empty-state">
                        <h3>No products matched your filters</h3>
                        <p>Try a different category, brand, or search keyword.</p>
                    </div>
                <?php else: ?>
                    <div class="grid products-grid">
                        <?php foreach ($products as $product): ?>
                            <article class="product-card product-card-premium product-card-catalog product-card-clickable" data-card-url="<?= url('product.php?slug=' . urlencode($product['slug'])) ?>" tabindex="0" aria-label="View <?= e($product['name']) ?>">
                                <div class="product-card-media">
                                    <?php if (product_discount($product) > 0): ?><span class="discount-badge"><?= product_discount($product) ?>% OFF</span><?php endif; ?>
                                    <span class="stock-badge"><?= (int) $product['stock'] ?> left</span>
                                    <img src="<?= asset(product_image_path($product, (string) ($product['category_slug'] ?? ''))) ?>" alt="<?= e($product['name']) ?>">
                                </div>
                                <div class="product-card-body">
                                    <div class="product-card-topline">
                                        <span class="product-chip"><?= e($product['brand_name'] ?: 'Brand') ?></span>
                                        <span class="product-chip subtle"><?= e($product['category_name'] ?: 'Category') ?></span>
                                    </div>
                                    <h3><a href="<?= url('product.php?slug=' . urlencode($product['slug'])) ?>"><?= e($product['name']) ?></a></h3>
                                    <p class="muted-text"><?= e($product['short_description']) ?></p>
                                    <div class="price-row">
                                        <strong><?= format_price((float) $product['price']) ?></strong>
                                        <?php if ((float) $product['old_price'] > 0): ?><span class="old-price"><?= format_price((float) $product['old_price']) ?></span><?php endif; ?>
                                    </div>
                                    <div class="meta-line">SKU: <?= e($product['sku']) ?></div>
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
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
