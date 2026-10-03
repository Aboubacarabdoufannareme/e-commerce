<?php
/**
 * actions/add_brand_action.php — Action
 * Admin-only. POST-only. Sanitizes, calls controller, redirects.
 */
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

// 1. Admin only — this MUST come before any output or DB work
require_admin();

// 2. POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/views/admin/brand.php');
}

// 3. Sanitize
$name = trim(strip_tags($_POST['brand_name'] ?? ''));

// 4. Call controller
$controller = new ProductController();
$result = $controller->addBrand($name);

// 5. Handle result
if ($result['success']) {
    $_SESSION['success'] = 'Brand added.';
} else {
    $_SESSION['error'] = $result['error'] ?? 'Could not add brand.';
}

redirect(BASE_URL . '/views/admin/brand.php');
?>