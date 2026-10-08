<?php
/**
 * views/home.php — View
 * Customer-facing home page. Handles:
 *   - default: featured products (random)
 *   - ?cat=N : products in a category
 *   - ?brand=N : products from a brand
 * NO SQL here — data comes from ProductController.
 */
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$controller = new ProductController();

// Decide which dataset to show
$cat_id   = $_GET['cat']   ?? null;
$brand_id = $_GET['brand'] ?? null;

$products = [];
$heading  = 'Featured Products';

if ($cat_id !== null && is_numeric($cat_id) && (int)$cat_id > 0) {
    $products = $controller->getProductsByCategory((int)$cat_id);
    $cat      = $controller->getCategoryById((int)$cat_id);
    $heading  = $cat ? 'Category: ' . $cat['cat_name'] : 'Category';
} elseif ($brand_id !== null && is_numeric($brand_id) && (int)$brand_id > 0) {
    $products = $controller->getProductsByBrand((int)$brand_id);
    $brand    = $controller->getBrandById((int)$brand_id);
    $heading  = $brand ? 'Brand: ' . $brand['brand_name'] : 'Brand';
} else {
    $products = $controller->getFeaturedProducts(6);
}

require_once __DIR__ . '/layout/header.php';
?>

<div class="page-wrap">
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

    <section class="content">
        <h1><?php echo htmlspecialchars($heading); ?></h1>

        <?php if (empty($products)): ?>
            <p><em>No products found.</em></p>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($products as $p): ?>
                    <div class="product-card">
                        <?php if (!empty($p['product_image'])): ?>
                            <a href="<?php echo BASE_URL; ?>/views/single_product.php?pro_id=<?php echo (int)$p['product_id']; ?>">
                                <img src="<?php echo BASE_URL; ?>/images/products/<?php echo htmlspecialchars($p['product_image']); ?>"
                                     alt="<?php echo htmlspecialchars($p['product_title']); ?>">
                            </a>
                        <?php else: ?>
                            <img src="<?php echo BASE_URL; ?>/images/logo.gif" alt="no image">
                        <?php endif; ?>

                        <h3><?php echo htmlspecialchars($p['product_title']); ?></h3>
                        <p class="price">GHS <?php echo number_format((float)$p['product_price'], 2); ?></p>

                        <div class="actions">
                            <a class="details" href="<?php echo BASE_URL; ?>/views/single_product.php?pro_id=<?php echo (int)$p['product_id']; ?>">Details</a>
                            <a class="add" href="<?php echo BASE_URL; ?>/actions/add_to_cart_action.php?add_cart=<?php echo (int)$p['product_id']; ?>">Add to Cart</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>