<?php
require_once __DIR__ . '/../../core/core.php';
require_admin();
require_once __DIR__ . '/../layout/header.php';
?>
<main class="container">
    <h1>Manage Products</h1>
    <p><em>Product management (add/edit products with image uploads) is coming in Task 9.</em></p>
    <p><a href="<?php echo BASE_URL; ?>/views/admin/brand.php">← Manage Brands</a></p>
</main>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>