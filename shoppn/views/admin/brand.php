<?php
/**
 * views/admin/brand.php — View
 * Admin-only page. Add a brand and list existing brands.
 * NO SQL here — data comes from the controller.
 */
require_once __DIR__ . '/../../core/core.php';
require_admin();

require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();
$brands = $controller->getAllBrands();

require_once __DIR__ . '/../layout/header.php';
?>

<main class="container">
    <h1>Manage Brands</h1>

    <?php if (!empty($_SESSION['success'])): ?>
        <p class="form-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <p class="form-error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <h2>Add a Brand</h2>
    <form action="<?php echo BASE_URL; ?>/actions/add_brand_action.php" method="POST">
        <p>
            <label>Brand Name<br>
                <input type="text" name="brand_name" required maxlength="100">
            </label>
        </p>
        <p>
            <button type="submit">Add Brand</button>
        </p>
    </form>

    <h2>Existing Brands</h2>
    <?php if (empty($brands)): ?>
        <p><em>No brands yet. Add the first one above.</em></p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Brand Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($brands as $b): ?>
                    <tr>
                        <td><?php echo (int)$b['brand_id']; ?></td>
                        <td><?php echo htmlspecialchars($b['brand_name']); ?></td>
                        <td><em>Edit — Task 6</em></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>