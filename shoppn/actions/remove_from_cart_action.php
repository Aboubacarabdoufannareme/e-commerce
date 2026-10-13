<?php
/**
 * actions/remove_from_cart_action.php — placeholder for Task 12.
 * Real implementation: validate product_id, call CartController::removeFromCart().
 */
require_once __DIR__ . '/../core/core.php';
$_SESSION['error'] = 'Remove from cart is coming in Task 12.';
redirect(BASE_URL . '/views/cart.php');
?>