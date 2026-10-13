<?php
/**
 * actions/update_qty_action.php — Action
 * Loops the qty[] inputs from the cart form and updates each one.
 * No SQL here — calls CartController.
 */
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

// POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/views/cart.php');
}

$ip = get_ip();

// The cart form submits qty[<product_id>] = <number>
$qtys = $_POST['qty'] ?? [];

if (empty($qtys) || !is_array($qtys)) {
    $_SESSION['error'] = 'No quantities to update.';
    redirect(BASE_URL . '/views/cart.php');
}

$controller = new CartController();
$updated    = 0;
$errors     = [];

foreach ($qtys as $pid => $q) {
    $pid = (int)$pid;
    $q   = (int)$q;

    if ($pid <= 0) {
        continue;
    }
    if ($q < 1) {
        $q = 1;   // silently clamp — prevents accidental deletes
    }
    if ($q > 999) {
        $errors[] = 'Quantity for product #' . $pid . ' capped at 999.';
        $q = 999;
    }

    if ($controller->updateQty($pid, $q, $ip)) {
        $updated++;
    } else {
        $errors[] = 'Could not update product #' . $pid . '.';
    }
}

if (!empty($errors)) {
    $_SESSION['error'] = implode(' ', $errors);
} else {
    $_SESSION['success'] = $updated . ' item' . ($updated === 1 ? '' : 's') . ' updated.';
}

redirect(BASE_URL . '/views/cart.php');
?>