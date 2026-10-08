<?php
/**
 * controllers/ProductController.php — Controller
 * Traffic director for brands, categories, and products.
 * No SQL, no HTML, no echo.
 */
require_once __DIR__ . '/../classes/ProductClass.php';

class ProductController
{
    private $product;

    public function __construct()
    {
        $this->product = new ProductClass();
    }

    // ───────────── BRANDS ─────────────

    /**
     * @return array ['success' => true, 'brand_id' => int]
     *             | ['success' => false, 'error' => string]
     */
    public function addBrand($name)
    {
        $name = trim($name);

        if ($name === '') {
            return ['success' => false, 'error' => 'Brand name is required.'];
        }
        if (strlen($name) > 100) {
            // brands.brand_name is VARCHAR(100) in the schema
            return ['success' => false, 'error' => 'Brand name is too long (max 100 characters).'];
        }

        $id = $this->product->addBrand($name);
        if ($id === false) {
            return ['success' => false, 'error' => 'Could not add brand. Try again.'];
        }
        return ['success' => true, 'brand_id' => $id];
    }

    /**
     * @return array  list of brands
     */
    public function getAllBrands()
    {
        return $this->product->getAllBrands();
    }
    /**
     * @return array|false  brand row or false
     */
    public function getBrandById($id)
    {
        if (!is_numeric($id) || (int)$id <= 0) {
            return false;
        }
        return $this->product->getBrandById((int)$id);
    }

    /**
     * @return array ['success' => true]
     *             | ['success' => false, 'error' => string]
     */
    public function updateBrand($id, $name)
    {
        if (!is_numeric($id) || (int)$id <= 0) {
            return ['success' => false, 'error' => 'Invalid brand ID.'];
        }

        $name = trim($name);
        if ($name === '') {
            return ['success' => false, 'error' => 'Brand name is required.'];
        }
        if (strlen($name) > 100) {
            return ['success' => false, 'error' => 'Brand name is too long (max 100 characters).'];
        }

        // Ensure the brand exists
        $existing = $this->product->getBrandById((int)$id);
        if (!$existing) {
            return ['success' => false, 'error' => 'Brand not found.'];
        }

        $ok = $this->product->updateBrand((int)$id, $name);
        if (!$ok) {
            return ['success' => false, 'error' => 'Could not update brand.'];
        }
        return ['success' => true];
    }
    // ───────────── CATEGORIES ─────────────

    /**
     * @return array ['success' => true, 'cat_id' => int]
     *             | ['success' => false, 'error' => string]
     */
    public function addCategory($name)
    {
        $name = trim($name);

        if ($name === '') {
            return ['success' => false, 'error' => 'Category name is required.'];
        }
        if (strlen($name) > 100) {
            return ['success' => false, 'error' => 'Category name is too long (max 100 characters).'];
        }

        $id = $this->product->addCategory($name);
        if ($id === false) {
            return ['success' => false, 'error' => 'Could not add category. Try again.'];
        }
        return ['success' => true, 'cat_id' => $id];
    }

    /**
     * @return array  list of categories
     */
    public function getAllCategories()
    {
        return $this->product->getAllCategories();
    }
    /**
     * @return array|false  category row or false
     */
    public function getCategoryById($id)
    {
        if (!is_numeric($id) || (int)$id <= 0) {
            return false;
        }
        return $this->product->getCategoryById((int)$id);
    }

    /**
     * @return array ['success' => true]
     *             | ['success' => false, 'error' => string]
     */
    public function updateCategory($id, $name)
    {
        if (!is_numeric($id) || (int)$id <= 0) {
            return ['success' => false, 'error' => 'Invalid category ID.'];
        }

        $name = trim($name);
        if ($name === '') {
            return ['success' => false, 'error' => 'Category name is required.'];
        }
        if (strlen($name) > 100) {
            return ['success' => false, 'error' => 'Category name is too long (max 100 characters).'];
        }

        $existing = $this->product->getCategoryById((int)$id);
        if (!$existing) {
            return ['success' => false, 'error' => 'Category not found.'];
        }

        $ok = $this->product->updateCategory((int)$id, $name);
        if (!$ok) {
            return ['success' => false, 'error' => 'Could not update category.'];
        }
        return ['success' => true];
    }
    // ───────────── PRODUCTS ─────────────

    /**
     * Validate product fields (shared by add and update).
     * @return array  ['ok' => bool, 'errors' => string[]]
     */
    private function validateProduct($data)
    {
        $errors = [];

        $title = trim($data['title'] ?? '');
        if ($title === '') {
            $errors[] = 'Product title is required.';
        }
        if (strlen($title) > 200) {
            $errors[] = 'Product title is too long (max 200 chars).';
        }

        $price = $data['price'] ?? '';
        if (!is_numeric($price) || (float)$price < 0) {
            $errors[] = 'Price must be a non-negative number.';
        }

        $cat = $data['cat'] ?? '';
        if (!is_numeric($cat) || (int)$cat <= 0) {
            $errors[] = 'Please choose a category.';
        }

        $brand = $data['brand'] ?? '';
        if (!is_numeric($brand) || (int)$brand <= 0) {
            $errors[] = 'Please choose a brand.';
        }

        return ['ok' => empty($errors), 'errors' => $errors];
    }

    /**
     * @return array ['success' => true, 'product_id' => int]
     *             | ['success' => false, 'error' => string]
     */
    public function addProduct($data)
    {
        $v = $this->validateProduct($data);
        if (!$v['ok']) {
            return ['success' => false, 'error' => implode(' ', $v['errors'])];
        }

        $id = $this->product->addProduct(
            (int)$data['cat'],
            (int)$data['brand'],
            trim($data['title']),
            (float)$data['price'],
            $data['desc']     ?? '',
            $data['image']    ?? '',
            $data['keywords'] ?? ''
        );

        if ($id === false) {
            return ['success' => false, 'error' => 'Could not add product.'];
        }
        return ['success' => true, 'product_id' => $id];
    }

    /**
     * @return array ['success' => true]
     *             | ['success' => false, 'error' => string]
     */
    public function updateProduct($id, $data)
    {
        if (!is_numeric($id) || (int)$id <= 0) {
            return ['success' => false, 'error' => 'Invalid product ID.'];
        }

        $v = $this->validateProduct($data);
        if (!$v['ok']) {
            return ['success' => false, 'error' => implode(' ', $v['errors'])];
        }

        $existing = $this->product->getProductById((int)$id);
        if (!$existing) {
            return ['success' => false, 'error' => 'Product not found.'];
        }

        $ok = $this->product->updateProduct(
            (int)$id,
            (int)$data['cat'],
            (int)$data['brand'],
            trim($data['title']),
            (float)$data['price'],
            $data['desc']     ?? '',
            $data['image']    ?? $existing['product_image'],
            $data['keywords'] ?? ''
        );

        if (!$ok) {
            return ['success' => false, 'error' => 'Could not update product.'];
        }
        return ['success' => true];
    }

    /**
     * @return array|false
     */
    public function getProductById($id)
    {
        if (!is_numeric($id) || (int)$id <= 0) {
            return false;
        }
        return $this->product->getProductById((int)$id);
    }
    /**
     * @return array
     */
    public function getAllProducts()
    {
        return $this->product->getAllProducts();
    }
}
?>