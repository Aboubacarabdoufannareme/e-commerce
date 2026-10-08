<?php
/**
 * classes/ProductClass.php — Model
 * Brand + Category + Product DB logic.
 * Task 5 adds brand methods. Categories (Task 7) and products (Task 9) will extend this file.
 * No HTML, no $_POST, no echo.
 */
require_once __DIR__ . '/../core/db_class.php';

class ProductClass extends Database
{
    // ───────────── BRANDS (Task 5 & 6) ─────────────

    /**
     * Insert a new brand.
     * @return int|false  new brand_id on success, false on failure
     */
    public function addBrand($name)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO brands (brand_name) VALUES (?)"
        );
        if (!$stmt) {
            error_log('addBrand prepare failed: ' . $this->conn->error);
            return false;
        }
        $stmt->bind_param('s', $name);

        if (!$stmt->execute()) {
            error_log('addBrand execute failed: ' . $stmt->error);
            $stmt->close();
            return false;
        }
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    /**
     * Return all brands, ordered alphabetically.
     * @return array  list of ['brand_id' => int, 'brand_name' => string]
     */
    public function getAllBrands()
    {
        $rows = [];
        $result = $this->conn->query("SELECT brand_id, brand_name FROM brands ORDER BY brand_name ASC");
        if (!$result) {
            error_log('getAllBrands failed: ' . $this->conn->error);
            return $rows;
        }
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
    /**
     * Fetch a single brand by ID.
     * @return array|false  ['brand_id' => int, 'brand_name' => string] or false
     */
    public function getBrandById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT brand_id, brand_name FROM brands WHERE brand_id = ? LIMIT 1"
        );
        if (!$stmt) {
            error_log('getBrandById prepare failed: ' . $this->conn->error);
            return false;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : false;
        $stmt->close();
        return $row;
    }

    /**
     * Update a brand's name.
     * @return bool  true on success, false on failure
     */
    public function updateBrand($id, $name)
    {
        $stmt = $this->conn->prepare(
            "UPDATE brands SET brand_name = ? WHERE brand_id = ?"
        );
        if (!$stmt) {
            error_log('updateBrand prepare failed: ' . $this->conn->error);
            return false;
        }
        $stmt->bind_param('si', $name, $id);

        if (!$stmt->execute()) {
            error_log('updateBrand execute failed: ' . $stmt->error);
            $stmt->close();
            return false;
        }
        $ok = $stmt->affected_rows >= 0; // 0 means "same value", not an error
        $stmt->close();
        return $ok;
    }
    // ───────────── CATEGORIES (Task 7 & 8) ─────────────

    /**
     * Insert a new category.
     * @return int|false  new cat_id on success, false on failure
     */
    public function addCategory($name)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO categories (cat_name) VALUES (?)"
        );
        if (!$stmt) {
            error_log('addCategory prepare failed: ' . $this->conn->error);
            return false;
        }
        $stmt->bind_param('s', $name);

        if (!$stmt->execute()) {
            error_log('addCategory execute failed: ' . $stmt->error);
            $stmt->close();
            return false;
        }
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    /**
     * Return all categories, ordered alphabetically.
     * @return array  list of ['cat_id' => int, 'cat_name' => string]
     */
    public function getAllCategories()
    {
        $rows = [];
        $result = $this->conn->query("SELECT cat_id, cat_name FROM categories ORDER BY cat_name ASC");
        if (!$result) {
            error_log('getAllCategories failed: ' . $this->conn->error);
            return $rows;
        }
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
    /**
     * Fetch a single category by ID.
     * @return array|false  ['cat_id' => int, 'cat_name' => string] or false
     */
    public function getCategoryById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT cat_id, cat_name FROM categories WHERE cat_id = ? LIMIT 1"
        );
        if (!$stmt) {
            error_log('getCategoryById prepare failed: ' . $this->conn->error);
            return false;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : false;
        $stmt->close();
        return $row;
    }

    /**
     * Update a category's name.
     * @return bool  true on success, false on failure
     */
    public function updateCategory($id, $name)
    {
        $stmt = $this->conn->prepare(
            "UPDATE categories SET cat_name = ? WHERE cat_id = ?"
        );
        if (!$stmt) {
            error_log('updateCategory prepare failed: ' . $this->conn->error);
            return false;
        }
        $stmt->bind_param('si', $name, $id);

        if (!$stmt->execute()) {
            error_log('updateCategory execute failed: ' . $stmt->error);
            $stmt->close();
            return false;
        }
        $ok = $stmt->affected_rows >= 0;
        $stmt->close();
        return $ok;
    }
    // ───────────── PRODUCTS (Task 9) ─────────────

    /**
     * Insert a new product.
     * @return int|false  new product_id on success, false on failure
     */
    public function addProduct($cat, $brand, $title, $price, $desc, $image, $keywords)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO products
                (product_cat, product_brand, product_title, product_price,
                 product_desc, product_image, product_keywords)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        if (!$stmt) {
            error_log('addProduct prepare failed: ' . $this->conn->error);
            return false;
        }
        $stmt->bind_param(
            'iisdsss',
            $cat, $brand, $title, $price, $desc, $image, $keywords
        );

        if (!$stmt->execute()) {
            error_log('addProduct execute failed: ' . $stmt->error);
            $stmt->close();
            return false;
        }
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    /**
     * Update an existing product.
     * @return bool
     */
    public function updateProduct($id, $cat, $brand, $title, $price, $desc, $image, $keywords)
    {
        $stmt = $this->conn->prepare(
            "UPDATE products SET
                product_cat = ?, product_brand = ?, product_title = ?,
                product_price = ?, product_desc = ?, product_image = ?,
                product_keywords = ?
             WHERE product_id = ?"
        );
        if (!$stmt) {
            error_log('updateProduct prepare failed: ' . $this->conn->error);
            return false;
        }
        $stmt->bind_param(
            'iisdsssi',
            $cat, $brand, $title, $price, $desc, $image, $keywords, $id
        );

        if (!$stmt->execute()) {
            error_log('updateProduct execute failed: ' . $stmt->error);
            $stmt->close();
            return false;
        }
        $ok = $stmt->affected_rows >= 0;
        $stmt->close();
        return $ok;
    }

    /**
     * Fetch one product with its category + brand names joined in.
     * @return array|false
     */
    public function getProductById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT p.*,
                    c.cat_name   AS category_name,
                    b.brand_name AS brand_name
             FROM products p
             LEFT JOIN categories c ON p.product_cat   = c.cat_id
             LEFT JOIN brands     b ON p.product_brand = b.brand_id
             WHERE p.product_id = ?
             LIMIT 1"
        );
        if (!$stmt) {
            error_log('getProductById prepare failed: ' . $this->conn->error);
            return false;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : false;
        $stmt->close();
        return $row;
    }
    /**
     * Return all products with category + brand names, newest first.
     * @return array
     */
    public function getAllProducts()
    {
        $rows = [];
        $sql = "SELECT p.product_id, p.product_title, p.product_price, p.product_image,
                       p.product_cat, p.product_brand,
                       c.cat_name AS category_name,
                       b.brand_name AS brand_name
                FROM products p
                LEFT JOIN categories c ON p.product_cat   = c.cat_id
                LEFT JOIN brands     b ON p.product_brand = b.brand_id
                ORDER BY p.product_id DESC";
        $result = $this->conn->query($sql);
        if (!$result) {
            error_log('getAllProducts failed: ' . $this->conn->error);
            return $rows;
        }
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
    // ───────────── PRODUCT DISPLAY (Task 10) ─────────────

    /**
     * Random selection for the home page.
     * @return array
     */
    public function getFeaturedProducts($limit = 6)
    {
        $rows = [];
        $limit = (int)$limit;
        if ($limit < 1) $limit = 6;

        $sql = "SELECT product_id, product_title, product_price, product_image
                FROM products
                ORDER BY RAND()
                LIMIT $limit";
        $result = $this->conn->query($sql);
        if (!$result) {
            error_log('getFeaturedProducts failed: ' . $this->conn->error);
            return $rows;
        }
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Products in a given category.
     * @return array
     */
    public function getProductsByCategory($cat_id)
    {
        $rows = [];
        $stmt = $this->conn->prepare(
            "SELECT product_id, product_title, product_price, product_image
             FROM products WHERE product_cat = ?
             ORDER BY product_title ASC"
        );
        if (!$stmt) {
            error_log('getProductsByCategory prepare failed: ' . $this->conn->error);
            return $rows;
        }
        $stmt->bind_param('i', $cat_id);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
        return $rows;
    }

    /**
     * Products from a given brand.
     * @return array
     */
    public function getProductsByBrand($brand_id)
    {
        $rows = [];
        $stmt = $this->conn->prepare(
            "SELECT product_id, product_title, product_price, product_image
             FROM products WHERE product_brand = ?
             ORDER BY product_title ASC"
        );
        if (!$stmt) {
            error_log('getProductsByBrand prepare failed: ' . $this->conn->error);
            return $rows;
        }
        $stmt->bind_param('i', $brand_id);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
        return $rows;
    }

    /**
     * Search products by title OR keywords (LIKE).
     * @return array
     */
    public function searchProducts($query)
    {
        $rows = [];
        $needle = '%' . $query . '%';

        $stmt = $this->conn->prepare(
            "SELECT product_id, product_title, product_price, product_image
             FROM products
             WHERE product_title LIKE ? OR product_keywords LIKE ?
             ORDER BY product_title ASC"
        );
        if (!$stmt) {
            error_log('searchProducts prepare failed: ' . $this->conn->error);
            return $rows;
        }
        $stmt->bind_param('ss', $needle, $needle);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
        return $rows;
    }
}
?>