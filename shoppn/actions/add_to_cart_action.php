<?php
/**
 * actions/add_to_cart_action.php — Action
 * Adds a product to the cart. Reads ?add_cart=N from GET.
 * Cart rows are keyed by (product_id, client_ip) — guest carts are IP-based.
 * No SQL here — calls CartController.
 */
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

// 1. Read the product id
$product_id = $_GET['add_cart'] ?? null;
if (!is_numeric($product_id) || (int)$product_id <= 0) {
    $_SESSION['error'] = 'Invalid product.';
    redirect(BASE_URL . '/index.php');
}
$product_id = (int)$product_id;

// 2. Client IP for guest cart tracking
$ip = get_ip();

// 3. Optional customer_id if logged in
$customer_id = is_logged_in() ? (int)$_SESSION['customer_id'] : null;

// 4. Call controller
$controller = new CartController();
$result = $controller->addToCart($product_id, 1, $ip, $customer_id);

// 5. Flash message
if ($result['success']) {
    $_SESSION['success'] = 'Added to cart.';
} else {
    $_SESSION['error'] = $result['error'] ?? 'Could not add to cart.';
}

// 6. Redirect home
redirect(BASE_URL . '/index.php');
?>