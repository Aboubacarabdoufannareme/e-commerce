<?php
/**
 * actions/login_action.php — Action
 * POST-only. Sanitizes inputs, calls controller, sets session, redirects.
 */
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

// 1. POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/login.php');
}

// 2. Sanitize
$email = trim(strip_tags($_POST['customer_email'] ?? ''));
$pass  = $_POST['customer_pass'] ?? '';

// 3. Basic shape validation
$errors = [];
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email address.';
}
if ($pass === '') {
    $errors[] = 'Password is required.';
}

if (!empty($errors)) {
    $_SESSION['error'] = implode(' ', $errors);
    redirect('../views/login.php');
}

// 4. Call the controller
$controller = new CustomerController();
$result = $controller->login($email, $pass);

// 5. Handle result
if ($result['success']) {
    $c = $result['customer'];

    $_SESSION['customer_id']    = (int)$c['customer_id'];
    $_SESSION['customer_name']  = $c['customer_name'];
    $_SESSION['customer_email'] = $c['customer_email'];
    $_SESSION['user_role']      = (int)$c['user_role'];

    $_SESSION['success'] = 'Welcome back, ' . $c['customer_name'] . '!';

    // Admins land on the admin dashboard; customers on home.
    if ((int)$c['user_role'] === 1) {
        redirect('../views/admin/product.php');
    } else {
        redirect('../index.php');
    }
}

// Failure
$_SESSION['error'] = $result['error'] ?? 'Login failed.';
redirect('../views/login.php');
?>