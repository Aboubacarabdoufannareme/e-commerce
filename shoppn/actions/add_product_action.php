<?php
/**
 * actions/add_product_action.php — Action
 * Admin-only, POST-only, handles image upload + INSERT.
 */
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/views/admin/product.php');
}

// ─── 1. Sanitize text fields ───
$data = [
    'cat'      => $_POST['product_cat']      ?? '',
    'brand'    => $_POST['product_brand']    ?? '',
    'title'    => trim(strip_tags($_POST['product_title']    ?? '')),
    'price'    => $_POST['product_price']    ?? '',
    'desc'     => trim(strip_tags($_POST['product_desc']     ?? '')),
    'keywords' => trim(strip_tags($_POST['product_keywords'] ?? '')),
    'image'    => '',
];

// ─── 2. Handle image upload ───
if (!empty($_FILES['product_image']['name'])) {
    $file = $_FILES['product_image'];

    // 2a. Upload error?
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'Image upload failed (code ' . $file['error'] . ').';
        redirect(BASE_URL . '/views/admin/product.php');
    }

    // 2b. Size check (max 2 MB)
    $max_bytes = 2 * 1024 * 1024;
    if ($file['size'] > $max_bytes) {
        $_SESSION['error'] = 'Image is too large (max 2 MB).';
        redirect(BASE_URL . '/views/admin/product.php');
    }

    // 2c. MIME type check — never trust the extension
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo   = finfo_open(FILEINFO_MIME_TYPE);
    $mime    = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowed, true)) {
        $_SESSION['error'] = 'Only JPG, PNG, GIF, or WEBP images are allowed.';
        redirect(BASE_URL . '/views/admin/product.php');
    }

    // 2d. Build a unique, safe filename
    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $safe_ext = in_array($ext, ['jpg','jpeg','png','gif','webp'], true) ? $ext : 'jpg';
    $filename = uniqid('prod_', true) . '.' . $safe_ext;

    // 2e. Move it
    $dest = __DIR__ . '/../images/products/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        $_SESSION['error'] = 'Could not save uploaded image.';
        redirect(BASE_URL . '/views/admin/product.php');
    }
    $data['image'] = $filename;
}

// ─── 3. Call the controller ───
$controller = new ProductController();
$result = $controller->addProduct($data);

if ($result['success']) {
    $_SESSION['success'] = 'Product added.';
} else {
    // Roll back the uploaded file if the DB insert failed
    if ($data['image'] !== '') {
        @unlink(__DIR__ . '/../images/products/' . $data['image']);
    }
    $_SESSION['error'] = $result['error'] ?? 'Could not add product.';
}

redirect(BASE_URL . '/views/admin/product.php');
?>