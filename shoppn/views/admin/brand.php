<?php
/**
 * views/admin/brand.php — View
 * Admin-only. Handles BOTH:
 *   - Add mode (default): empty form, POSTs to add_brand_action.php
 *   - Edit mode (?edit_id=N): pre-filled form, POSTs to update_brand_action.php
 * NO SQL here — data comes from the controller.
 */
require_once __DIR__ . '/../../core/core.php';
require_admin();

require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();
$brands = $controller->getAllBrands();

// Determine mode
$edit_id    = $_GET['edit_id'] ?? null;
$edit_brand = null;

if ($edit_id !== null) {
    $edit_brand = $controller->getBrandById($edit_id);
    if (!$edit_brand) {
        $_SESSION['error'] = 'Brand not found.';
        redirect(BASE_URL . '/views/admin/brand.php');
    }
}
$is_edit = ($edit_brand !== null);

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

    <h2><?php echo $is_edit ? 'Edit Brand' : 'Add a Brand'; ?></h2>

    <form action="<?php echo BASE_URL; ?>/actions/<?php echo $is_edit ? 'update_brand_action.php' : 'add_brand_action.php'; ?>" method="POST">
        <?php if ($is_edit): ?>
            <input type="hidden" name="brand_id" value="<?php echo (int)$edit_brand['brand_id']; ?>">
        <?php endif; ?>

        <p>
            <label>Brand Name<br>
                <input type="text" name="brand_name" required maxlength="100"
                       value="<?php echo $is_edit ? htmlspecialchars($edit_brand['brand_name']) : ''; ?>">
            </label>
        </p>

        <p>
            <button type="submit"><?php echo $is_edit ? 'Update Brand' : 'Add Brand'; ?></button>
            <?php if ($is_edit): ?>
                <a href="<?php echo BASE_URL; ?>/views/admin/brand.php" style="margin-left:1rem;">Cancel</a>
            <?php endif; ?>
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
                        <td>
                            <a href="<?php echo BASE_URL; ?>/views/admin/brand.php?edit_id=<?php echo (int)$b['brand_id']; ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>