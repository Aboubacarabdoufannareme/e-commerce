<?php
/**
 * actions/remove_from_cart_action.php — Action
 * Removes ONE cart row. Receives product_id from the hidden form.
 * No SQL here — calls CartController.
 */
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

// POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/views/cart.php');
}

$product_id = $_POST['product_id'] ?? null;
if (!is_numeric($product_id) || (int)$product_id <= 0) {
    $_SESSION['error'] = 'Invalid product.';
    redirect(BASE_URL . '/views/cart.php');
}

$ip = get_ip();

$controller = new CartController();
$ok = $controller->removeFromCart((int)$product_id, $ip);

if ($ok) {
    $_SESSION['success'] = 'Item removed from cart.';
} else {
    $_SESSION['error'] = 'Could not remove item.';
}

redirect(BASE_URL . '/views/cart.php');
?>