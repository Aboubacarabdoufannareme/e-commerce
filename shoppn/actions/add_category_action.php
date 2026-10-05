<?php
/**
 * actions/add_category_action.php — Action
 * Admin-only. POST-only. Sanitizes, calls controller, redirects.
 */
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

// 1. Admin gate
require_admin();

// 2. POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/views/admin/category.php');
}

// 3. Sanitize
$name = trim(strip_tags($_POST['cat_name'] ?? ''));

// 4. Call controller
$controller = new ProductController();
$result = $controller->addCategory($name);

// 5. Redirect with message
if ($result['success']) {
    $_SESSION['success'] = 'Category added.';
} else {
    $_SESSION['error'] = $result['error'] ?? 'Could not add category.';
}

redirect(BASE_URL . '/views/admin/category.php');
?>