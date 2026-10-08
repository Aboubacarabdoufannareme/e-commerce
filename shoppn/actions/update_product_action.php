<?php
/**
 * actions/update_product_action.php — Action
 * Admin-only, POST-only. Handles OPTIONAL image replacement.
 */
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/views/admin/product.php');
}

$product_id = $_POST['product_id'] ?? '';

if (!is_numeric($product_id) || (int)$product_id <= 0) {
    $_SESSION['error'] = 'Invalid product.';
    redirect(BASE_URL . '/views/admin/product.php');
}

$data = [
    'cat'      => $_POST['product_cat']      ?? '',
    'brand'    => $_POST['product_brand']    ?? '',
    'title'    => trim(strip_tags($_POST['product_title']    ?? '')),
    'price'    => $_POST['product_price']    ?? '',
    'desc'     => trim(strip_tags($_POST['product_desc']     ?? '')),
    'keywords' => trim(strip_tags($_POST['product_keywords'] ?? '')),
    'image'    => '',   // empty => controller keeps existing
];

// Handle OPTIONAL image upload
if (!empty($_FILES['product_image']['name'])) {
    $file = $_FILES['product_image'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'Image upload failed (code ' . $file['error'] . ').';
        redirect(BASE_URL . '/views/admin/product.php?edit_id=' . (int)$product_id);
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        $_SESSION['error'] = 'Image is too large (max 2 MB).';
        redirect(BASE_URL . '/views/admin/product.php?edit_id=' . (int)$product_id);
    }

    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo   = finfo_open(FILEINFO_MIME_TYPE);
    $mime    = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowed, true)) {
        $_SESSION['error'] = 'Only JPG, PNG, GIF, or WEBP images are allowed.';
        redirect(BASE_URL . '/views/admin/product.php?edit_id=' . (int)$product_id);
    }

    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $safe_ext = in_array($ext, ['jpg','jpeg','png','gif','webp'], true) ? $ext : 'jpg';
    $filename = uniqid('prod_', true) . '.' . $safe_ext;

    $dest = __DIR__ . '/../images/products/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        $_SESSION['error'] = 'Could not save uploaded image.';
        redirect(BASE_URL . '/views/admin/product.php?edit_id=' . (int)$product_id);
    }
    $data['image'] = $filename;
}

$controller = new ProductController();
$result = $controller->updateProduct((int)$product_id, $data);

if ($result['success']) {
    $_SESSION['success'] = 'Product updated.';
} else {
    if ($data['image'] !== '') {
        @unlink(__DIR__ . '/../images/products/' . $data['image']);
    }
    $_SESSION['error'] = $result['error'] ?? 'Could not update product.';
}

redirect(BASE_URL . '/views/admin/product.php');
?>