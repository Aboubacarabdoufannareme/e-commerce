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
}
?>