<?php
/**
 * views/layout/sidebar.php
 * Categories + brands list for customer-facing pages.
 * Data comes from ProductController — NO SQL here.
 *
 * The page including this file must have already required core.php and
 * ProductController.php. If not, we require them defensively.
 */
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

// Instantiate a controller for the sidebar. A separate instance is fine —
// the DB connection is cheap and short-lived.
$sidebarController = new ProductController();
$sidebarCategories = $sidebarController->getAllCategories();
$sidebarBrands     = $sidebarController->getAllBrands();
?>
<aside class="sidebar">
    <h3>Categories</h3>
    <?php if (empty($sidebarCategories)): ?>
        <p><em>No categories yet.</em></p>
    <?php else: ?>
        <ul>
            <?php foreach ($sidebarCategories as $c): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>/index.php?cat=<?php echo (int)$c['cat_id']; ?>">
                        <?php echo htmlspecialchars($c['cat_name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h3>Brands</h3>
    <?php if (empty($sidebarBrands)): ?>
        <p><em>No brands yet.</em></p>
    <?php else: ?>
        <ul>
            <?php foreach ($sidebarBrands as $b): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>/index.php?brand=<?php echo (int)$b['brand_id']; ?>">
                        <?php echo htmlspecialchars($b['brand_name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</aside>