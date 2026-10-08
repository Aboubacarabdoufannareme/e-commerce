<?php

/**
 * actions/add_to_cart_action.php — PLACEHOLDER
 * The real Add to Cart logic (CartClass, session/IP tracking, duplicate
 * detection) is coming in Task 11. For now this page exists so clicking
 * "Add to Cart" doesn't produce a 404.
 */
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../views/layout/header.php';

$product_id = $_GET['add_cart'] ?? null;
$product_id = (is_numeric($product_id) && (int)$product_id > 0) ? (int)$product_id : 0;
?>
<div class="page-wrap">
    <section class="content">
        <h1>Add to Cart</h1>
        <p><em>Shopping cart functionality is coming in Task 11.</em></p>

        <?php if ($product_id > 0): ?>
            <p>You tried to add product ID <strong><?php echo $product_id; ?></strong> to your cart.</p>
        <?php endif; ?>

        <p><a href="<?php echo BASE_URL; ?>/index.php">← Continue shopping</a></p>
    </section>
</div>
<?php require_once __DIR__ . '/../views/layout/footer.php'; ?>