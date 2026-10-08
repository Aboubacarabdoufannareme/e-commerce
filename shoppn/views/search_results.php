<?php
/**
 * views/search_results.php — View
 * Reads ?user_query from the header search form and lists matching products.
 */
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$controller = new ProductController();

$query = trim($_GET['user_query'] ?? '');
$products = ($query === '') ? [] : $controller->searchProducts($query);

require_once __DIR__ . '/layout/header.php';
?>

<div class="page-wrap">
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

    <section class="content">
        <h1>Search Results</h1>

        <?php if ($query === ''): ?>
            <p><em>Please enter a search term.</em></p>
        <?php else: ?>
            <p class="meta">You searched for: <strong><?php echo htmlspecialchars($query); ?></strong></p>

            <?php if (empty($products)): ?>
                <p><em>No products matched your search.</em></p>
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
                                <div style="height:160px;background:#f4f4f4;border-radius:4px;"></div>
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
        <?php endif; ?>
    </section>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>