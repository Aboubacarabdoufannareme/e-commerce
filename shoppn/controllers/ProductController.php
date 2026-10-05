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
}
?>