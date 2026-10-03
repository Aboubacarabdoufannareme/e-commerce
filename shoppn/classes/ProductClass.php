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
}
?>