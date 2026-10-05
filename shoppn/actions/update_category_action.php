<?php
/**
 * actions/update_category_action.php — Action
 * Admin-only. POST-only. Validates, calls controller, redirects.
 */
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/views/admin/category.php');
}

$cat_id = $_POST['cat_id'] ?? '';
$name   = trim(strip_tags($_POST['cat_name'] ?? ''));

if (!is_numeric($cat_id) || (int)$cat_id <= 0) {
    $_SESSION['error'] = 'Invalid category selected.';
    redirect(BASE_URL . '/views/admin/category.php');
}

$controller = new ProductController();
$result = $controller->updateCategory((int)$cat_id, $name);

if ($result['success']) {
    $_SESSION['success'] = 'Category updated.';
} else {
    $_SESSION['error'] = $result['error'] ?? 'Could not update category.';
}

redirect(BASE_URL . '/views/admin/category.php');
?>