<?php
/**
 * views/admin/category.php — View
 * Admin-only page. Add a category and list existing categories.
 * Edit mode will be added in Task 8.
 * NO SQL here.
 */
require_once __DIR__ . '/../../core/core.php';
require_admin();

require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();
$categories = $controller->getAllCategories();

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

    <h2>Add a Category</h2>
    <form action="<?php echo BASE_URL; ?>/actions/add_category_action.php" method="POST">
        <p>
            <label>Category Name<br>
                <input type="text" name="cat_name" required maxlength="100">
            </label>
        </p>
        <p>
            <button type="submit">Add Category</button>
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
                        <td><em>Edit — Task 8</em></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>