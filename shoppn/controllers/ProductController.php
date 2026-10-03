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
}
?>