<?php
/**
 * views/cart.php — View
 * Shows the cart contents.
 * Quantity update: one form wrapping the whole table.
 * Remove: one small form per row.
 */
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$controller = new CartController();
$ip         = get_ip();
$items      = $controller->getCartItems($ip);
$total      = $controller->getCartTotal($ip);

require_once __DIR__ . '/layout/header.php';
?>

<div class="page-wrap">
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

    <section class="content">
        <h1>Your Cart</h1>

        <?php if (!empty($_SESSION['success'])): ?>
            <p class="form-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></p>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <p class="form-error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <?php if (empty($items)): ?>
            <p><em>Your cart is empty.</em></p>
            <p><a href="<?php echo BASE_URL; ?>/index.php">← Continue shopping</a></p>
        <?php else: ?>

            <form action="<?php echo BASE_URL; ?>/actions/update_qty_action.php" method="POST">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Image</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $it): ?>
                            <?php
                                $price    = (float)$it['product_price'];
                                $qty      = (int)$it['qty'];
                                $subtotal = $price * $qty;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($it['product_title']); ?></td>
                                <td>
                                    <?php if (!empty($it['product_image'])): ?>
                                        <img src="<?php echo BASE_URL; ?>/images/products/<?php echo htmlspecialchars($it['product_image']); ?>" alt="">
                                    <?php else: ?>
                                        <div style="width:60px;height:60px;background:#f4f2ea;border-radius:4px;"></div>
                                    <?php endif; ?>
                                </td>
                                <td>GHS <?php echo number_format($price, 2); ?></td>
                                <td>
                                    <input class="qty-input" type="number" name="qty[<?php echo (int)$it['p_id']; ?>]"
                                           value="<?php echo $qty; ?>" min="1">
                                </td>
                                <td class="cart-subtotal">GHS <?php echo number_format($subtotal, 2); ?></td>
                                <td>
                                    <button type="button"
                                            class="btn-remove"
                                            data-pid="<?php echo (int)$it['p_id']; ?>"
                                            style="background:none;border:none;color:#b3261e;cursor:pointer;font-size:0.85rem;text-decoration:underline;">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="4" style="text-align:right;font-weight:600;">Total:</td>
                            <td class="cart-subtotal">GHS <?php echo number_format($total, 2); ?></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                <div class="cart-actions">
                    <button type="submit" class="primary">Update Quantities</button>
                    <a href="<?php echo BASE_URL; ?>/index.php">Continue Shopping</a>
                    <a class="primary" href="<?php echo BASE_URL; ?>/views/checkout.php">Checkout</a>
                </div>
            </form>

            <!-- Hidden per-row remove form, submitted by JS when a Remove button is clicked -->
            <form id="remove-form" action="<?php echo BASE_URL; ?>/actions/remove_from_cart_action.php" method="POST" style="display:none;">
                <input type="hidden" name="product_id" id="remove-product-id" value="">
            </form>

        <?php endif; ?>
    </section>
</div>

<script>
document.querySelectorAll('.btn-remove').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var pid = btn.getAttribute('data-pid');
        if (!pid) return;
        document.getElementById('remove-product-id').value = pid;
        document.getElementById('remove-form').submit();
    });
});
</script>

<?php require_once __DIR__ . '/layout/footer.php'; ?>