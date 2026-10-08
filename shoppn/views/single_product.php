<?php
/**
 * views/single_product.php — View
 * Detail page for one product. Requires ?pro_id=N.
 */
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$controller = new ProductController();

$pro_id = $_GET['pro_id'] ?? null;
if ($pro_id === null || !is_numeric($pro_id) || (int)$pro_id <= 0) {
    redirect(BASE_URL . '/index.php');
}

$product = $controller->getProductById((int)$pro_id);
if (!$product) {
    $_SESSION['error'] = 'Product not found.';
    redirect(BASE_URL . '/index.php');
}

require_once __DIR__ . '/layout/header.php';
?>

<div class="page-wrap">
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

    <section class="content">
        <h1><?php echo htmlspecialchars($product['product_title']); ?></h1>

        <div class="single-product">
            <div>
                <?php if (!empty($product['product_image'])): ?>
                    <img src="<?php echo BASE_URL; ?>/images/products/<?php echo htmlspecialchars($product['product_image']); ?>"
                         alt="<?php echo htmlspecialchars($product['product_title']); ?>">
                <?php else: ?>
                    <div style="height:340px;background:#f4f4f4;border-radius:6px;"></div>
                <?php endif; ?>
            </div>
            <div>
                <p class="meta">Category: <?php echo htmlspecialchars($product['category_name'] ?? '—'); ?></p>
                <p class="meta">Brand: <?php echo htmlspecialchars($product['brand_name'] ?? '—'); ?></p>
                <p class="price">GHS <?php echo number_format((float)$product['product_price'], 2); ?></p>

                <p><?php echo nl2br(htmlspecialchars($product['product_desc'] ?? '')); ?></p>

                <?php if (!empty($product['product_keywords'])): ?>
                    <p class="meta">Keywords: <?php echo htmlspecialchars($product['product_keywords']); ?></p>
                <?php endif; ?>

                <p>
                    <a class="add" style="background:#111;color:#fff;padding:0.5rem 1rem;border-radius:4px;text-decoration:none;"
                       href="<?php echo BASE_URL; ?>/actions/add_to_cart_action.php?add_cart=<?php echo (int)$product['product_id']; ?>">
                        Add to Cart
                    </a>
                </p>

                <p><a href="<?php echo BASE_URL; ?>/index.php">← Continue shopping</a></p>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>