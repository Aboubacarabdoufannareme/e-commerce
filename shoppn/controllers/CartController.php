<?php
/**
 * controllers/CartController.php — Controller
 * Traffic director for cart operations. No SQL, no HTML, no echo.
 */
require_once __DIR__ . '/../classes/CartClass.php';

class CartController
{
    private $cart;

    public function __construct()
    {
        $this->cart = new CartClass();
    }

    /**
     * @return array ['success' => true, 'cart_count' => int]
     *             | ['success' => false, 'error' => string]
     */
    public function addToCart($product_id, $qty, $ip, $customer_id = null)
    {
        if (!is_numeric($product_id) || (int)$product_id <= 0) {
            return ['success' => false, 'error' => 'Invalid product.'];
        }
        if (!is_numeric($qty) || (int)$qty < 1) {
            $qty = 1;
        }

        $product_id = (int)$product_id;
        $qty        = (int)$qty;

        if ($this->cart->isInCart($product_id, $ip)) {
            return ['success' => false, 'error' => 'This item is already in your cart. Go to cart to update quantity.'];
        }

        $ok = $this->cart->addToCart($product_id, $qty, $ip, $customer_id);
        if (!$ok) {
            return ['success' => false, 'error' => 'Could not add to cart.'];
        }

        return ['success' => true, 'cart_count' => $this->cart->getCartCount($ip)];
    }

    public function getCartItems($ip)
    {
        return $this->cart->getCartItems($ip);
    }

    public function getCartCount($ip)
    {
        return $this->cart->getCartCount($ip);
    }

    public function getCartTotal($ip)
    {
        return $this->cart->getCartTotal($ip);
    }

    public function isInCart($product_id, $ip)
    {
        return $this->cart->isInCart((int)$product_id, $ip);
    }

    public function removeFromCart($product_id, $ip)
    {
        if (!is_numeric($product_id) || (int)$product_id <= 0) {
            return false;
        }
        return $this->cart->removeFromCart((int)$product_id, $ip);
    }

    public function updateQty($product_id, $new_qty, $ip)
    {
        if (!is_numeric($product_id) || (int)$product_id <= 0) {
            return false;
        }
        if (!is_numeric($new_qty) || (int)$new_qty < 1) {
            $new_qty = 1;
        }
        return $this->cart->updateQty((int)$product_id, (int)$new_qty, $ip);
    }
}
?>