<?php
/**
 * views/admin/category.php — View
 * Admin-only. Handles BOTH:
 *   - Add mode (default): empty form, POSTs to add_category_action.php
 *   - Edit mode (?edit_id=N): pre-filled form, POSTs to update_category_action.php
 * NO SQL here.
 */
require_once __DIR__ . '/../../core/core.php';
require_admin();

require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();
$categories = $controller->getAllCategories();

// Determine mode
$edit_id         = $_GET['edit_id'] ?? null;
$edit_category   = null;

if ($edit_id !== null) {
    $edit_category = $controller->getCategoryById($edit_id);
    if (!$edit_category) {
        $_SESSION['error'] = 'Category not found.';
        redirect(BASE_URL . '/views/admin/category.php');
    }
}
$is_edit = ($edit_category !== null);

require_once __DIR__ . '/../layout/header.php';
?>

<main class="container">
    <h1>Manage Categories</h1>

    <?php if (!empty($_SESSION['success'])): ?>
        <p class="form-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <p class="form-error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <h2><?php echo $is_edit ? 'Edit Category' : 'Add a Category'; ?></h2>

    <form action="<?php echo BASE_URL; ?>/actions/<?php echo $is_edit ? 'update_category_action.php' : 'add_category_action.php'; ?>" method="POST">
        <?php if ($is_edit): ?>
            <input type="hidden" name="cat_id" value="<?php echo (int)$edit_category['cat_id']; ?>">
        <?php endif; ?>

        <p>
            <label>Category Name<br>
                <input type="text" name="cat_name" required maxlength="100"
                       value="<?php echo $is_edit ? htmlspecialchars($edit_category['cat_name']) : ''; ?>">
            </label>
        </p>

        <p>
            <button type="submit"><?php echo $is_edit ? 'Update Category' : 'Add Category'; ?></button>
            <?php if ($is_edit): ?>
                <a href="<?php echo BASE_URL; ?>/views/admin/category.php" style="margin-left:1rem;">Cancel</a>
            <?php endif; ?>
        </p>
    </form>

    <h2>Existing Categories</h2>
    <?php if (empty($categories)): ?>
        <p><em>No categories yet. Add the first one above.</em></p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $c): ?>
                    <tr>
                        <td><?php echo (int)$c['cat_id']; ?></td>
                        <td><?php echo htmlspecialchars($c['cat_name']); ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/views/admin/category.php?edit_id=<?php echo (int)$c['cat_id']; ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>