<?php
/**
 * classes/CartClass.php — Model
 * All cart-related DB logic.
 * Cart rows are keyed by (p_id, ip_add) for guests, optionally by c_id for logged-in users.
 * No HTML, no $_POST, no echo.
 */
require_once __DIR__ . '/../core/db_class.php';

class CartClass extends Database
{
    /**
     * Is this product already in the cart for this IP?
     */
    public function isInCart($product_id, $ip)
    {
        $stmt = $this->conn->prepare(
            "SELECT 1 FROM cart WHERE p_id = ? AND ip_add = ? LIMIT 1"
        );
        if (!$stmt) {
            error_log('isInCart prepare failed: ' . $this->conn->error);
            return false;
        }
        $stmt->bind_param('is', $product_id, $ip);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    /**
     * Insert a cart row.
     * @return bool
     */
    public function addToCart($product_id, $qty, $ip, $customer_id = null)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO cart (p_id, ip_add, c_id, qty) VALUES (?, ?, ?, ?)"
        );
        if (!$stmt) {
            error_log('addToCart prepare failed: ' . $this->conn->error);
            return false;
        }
        // c_id can be null — bind_param doesn't handle NULL well with 'i', so
        // we use a separate branch.
        if ($customer_id === null) {
            $stmt->bind_param('isii', $product_id, $ip, $null, $qty);
            $null = null;
        } else {
            $stmt->bind_param('isii', $product_id, $ip, $customer_id, $qty);
        }

        $ok = $stmt->execute();
        if (!$ok) {
            error_log('addToCart execute failed: ' . $stmt->error);
        }
        $stmt->close();
        return $ok;
    }

    /**
     * Fetch cart rows with product details joined in.
     * @return array
     */
    public function getCartItems($ip)
    {
        $rows = [];
        $stmt = $this->conn->prepare(
            "SELECT cart.p_id, cart.qty, cart.c_id,
                    products.product_title, products.product_price, products.product_image
             FROM cart
             INNER JOIN products ON cart.p_id = products.product_id
             WHERE cart.ip_add = ?
             ORDER BY cart.p_id ASC"
        );
        if (!$stmt) {
            error_log('getCartItems prepare failed: ' . $this->conn->error);
            return $rows;
        }
        $stmt->bind_param('s', $ip);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
        return $rows;
    }

    /**
     * Number of items in the cart.
     */
    public function getCartCount($ip)
    {
        $stmt = $this->conn->prepare(
            "SELECT COALESCE(SUM(qty), 0) AS n FROM cart WHERE ip_add = ?"
        );
        if (!$stmt) {
            error_log('getCartCount prepare failed: ' . $this->conn->error);
            return 0;
        }
        $stmt->bind_param('s', $ip);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : ['n' => 0];
        $stmt->close();
        return (int)($row['n'] ?? 0);
    }

    /**
     * Total price of all items in the cart.
     */
    public function getCartTotal($ip)
    {
        $stmt = $this->conn->prepare(
            "SELECT COALESCE(SUM(cart.qty * products.product_price), 0) AS total
             FROM cart
             INNER JOIN products ON cart.p_id = products.product_id
             WHERE cart.ip_add = ?"
        );
        if (!$stmt) {
            error_log('getCartTotal prepare failed: ' . $this->conn->error);
            return 0.0;
        }
        $stmt->bind_param('s', $ip);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : ['total' => 0];
        $stmt->close();
        return (float)($row['total'] ?? 0);
    }

    /**
     * Remove a cart row.
     */
    public function removeFromCart($product_id, $ip)
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM cart WHERE p_id = ? AND ip_add = ?"
        );
        if (!$stmt) {
            error_log('removeFromCart prepare failed: ' . $this->conn->error);
            return false;
        }
        $stmt->bind_param('is', $product_id, $ip);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Update the quantity for a cart row.
     */
    public function updateQty($product_id, $new_qty, $ip)
    {
        if ($new_qty < 1) $new_qty = 1;
        $stmt = $this->conn->prepare(
            "UPDATE cart SET qty = ? WHERE p_id = ? AND ip_add = ?"
        );
        if (!$stmt) {
            error_log('updateQty prepare failed: ' . $this->conn->error);
            return false;
        }
        $stmt->bind_param('iis', $new_qty, $product_id, $ip);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
?>