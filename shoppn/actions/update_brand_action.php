<?php
/**
 * actions/update_brand_action.php — Action
 * Admin-only. POST-only. Validates, calls controller, redirects.
 */
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

// 1. Admin gate first
require_admin();

// 2. POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/views/admin/brand.php');
}

// 3. Read + sanitize
$brand_id = $_POST['brand_id'] ?? '';
$name     = trim(strip_tags($_POST['brand_name'] ?? ''));

// 4. Validate ID before touching the controller
if (!is_numeric($brand_id) || (int)$brand_id <= 0) {
    $_SESSION['error'] = 'Invalid brand selected.';
    redirect(BASE_URL . '/views/admin/brand.php');
}

// 5. Call controller
$controller = new ProductController();
$result = $controller->updateBrand((int)$brand_id, $name);

// 6. Handle result
if ($result['success']) {
    $_SESSION['success'] = 'Brand updated.';
} else {
    $_SESSION['error'] = $result['error'] ?? 'Could not update brand.';
}

redirect(BASE_URL . '/views/admin/brand.php');
?>