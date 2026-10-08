<?php
/**
 * views/admin/product.php — View
 * Admin-only. Add OR edit a product (with file upload).
 */
require_once __DIR__ . '/../../core/core.php';
require_admin();

require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();

// Dropdowns data
$categories = $controller->getAllCategories();
$brands     = $controller->getAllBrands();
$products   = $controller->getAllProducts();

// Edit mode?
$edit_id  = $_GET['edit_id'] ?? null;
$product  = null;
if ($edit_id !== null) {
    $product = $controller->getProductById($edit_id);
    if (!$product) {
        $_SESSION['error'] = 'Product not found.';
        redirect(BASE_URL . '/views/admin/product.php');
    }
}
$is_edit = ($product !== null);

require_once __DIR__ . '/../layout/header.php';
?>

<main class="container">
    <h1>Manage Products</h1>

    <?php if (!empty($_SESSION['success'])): ?>
        <p class="form-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></p>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <p class="form-error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <h2><?php echo $is_edit ? 'Edit Product' : 'Add a Product'; ?></h2>

    <form action="<?php echo BASE_URL; ?>/actions/<?php echo $is_edit ? 'update_product_action.php' : 'add_product_action.php'; ?>"
          method="POST"
          enctype="multipart/form-data">

        <?php if ($is_edit): ?>
            <input type="hidden" name="product_id" value="<?php echo (int)$product['product_id']; ?>">
        <?php endif; ?>

        <p>
            <label>Product Title<br>
                <input type="text" name="product_title" required maxlength="200"
                       value="<?php echo $is_edit ? htmlspecialchars($product['product_title']) : ''; ?>">
            </label>
        </p>

        <p>
            <label>Price<br>
                <input type="number" name="product_price" step="0.01" min="0" required
                       value="<?php echo $is_edit ? htmlspecialchars($product['product_price']) : ''; ?>">
            </label>
        </p>

        <p>
            <label>Category<br>
                <select name="product_cat" required>
                    <option value="">— Select —</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?php echo (int)$c['cat_id']; ?>"
                            <?php echo $is_edit && (int)$product['product_cat'] === (int)$c['cat_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c['cat_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </p>

        <p>
            <label>Brand<br>
                <select name="product_brand" required>
                    <option value="">— Select —</option>
                    <?php foreach ($brands as $b): ?>
                        <option value="<?php echo (int)$b['brand_id']; ?>"
                            <?php echo $is_edit && (int)$product['product_brand'] === (int)$b['brand_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($b['brand_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </p>

        <p>
            <label>Description<br>
                <textarea name="product_desc" rows="4" cols="50"><?php echo $is_edit ? htmlspecialchars($product['product_desc']) : ''; ?></textarea>
            </label>
        </p>

        <p>
            <label>Keywords (comma-separated)<br>
                <input type="text" name="product_keywords" maxlength="200"
                       value="<?php echo $is_edit ? htmlspecialchars($product['product_keywords']) : ''; ?>">
            </label>
        </p>

        <p>
            <label>Product Image <?php echo $is_edit ? '(leave blank to keep current)' : ''; ?><br>
                <input type="file" name="product_image" accept="image/*" <?php echo $is_edit ? '' : 'required'; ?>>
            </label>
        </p>

        <?php if ($is_edit && !empty($product['product_image'])): ?>
            <p>
                Current image:<br>
                <img src="<?php echo BASE_URL; ?>/images/products/<?php echo htmlspecialchars($product['product_image']); ?>"
                     alt="product" style="max-width:160px;border:1px solid #ccc;padding:4px;">
            </p>
        <?php endif; ?>

        <p>
            <button type="submit"><?php echo $is_edit ? 'Update Product' : 'Add Product'; ?></button>
            <?php if ($is_edit): ?>
                <a href="<?php echo BASE_URL; ?>/views/admin/product.php" style="margin-left:1rem;">Cancel</a>
            <?php endif; ?>
        </p>
    </form>

        <h2 style="margin-top:2rem;">Existing Products</h2>
    <?php if (empty($products)): ?>
        <p><em>No products yet. Add the first one above.</em></p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?php echo (int)$p['product_id']; ?></td>
                        <td>
                            <?php if (!empty($p['product_image'])): ?>
                                <img src="<?php echo BASE_URL; ?>/images/products/<?php echo htmlspecialchars($p['product_image']); ?>"
                                     alt="" style="max-width:60px;max-height:60px;">
                            <?php else: ?>
                                <em>—</em>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($p['product_title']); ?></td>
                        <td><?php echo htmlspecialchars($p['product_price']); ?></td>
                        <td><?php echo htmlspecialchars($p['category_name'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($p['brand_name'] ?? '—'); ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/views/admin/product.php?edit_id=<?php echo (int)$p['product_id']; ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>