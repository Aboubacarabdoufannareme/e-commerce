<?php
/**
 * controllers/CustomerController.php — Controller
 * Traffic director. No SQL. No HTML. No echo.
 */
require_once __DIR__ . '/../classes/CustomerClass.php';

class CustomerController
{
    private $customer;

    public function __construct()
    {
        $this->customer = new CustomerClass();
    }

    /**
     * Register a new customer.
     * $data should be an associative array with:
     *   name, email, password, country, city, contact
     *
     * @return array ['success' => true, 'customer_id' => int]
     *             | ['success' => false, 'error' => string]
     */
    public function register($data)
    {
        // Defensive: required keys present?
        $required = ['name', 'email', 'password', 'country', 'city', 'contact'];
        foreach ($required as $key) {
            if (!isset($data[$key]) || $data[$key] === '') {
                return ['success' => false, 'error' => 'Missing field: ' . $key];
            }
        }

        if ($this->customer->emailExists($data['email'])) {
            return ['success' => false, 'error' => 'Email already registered.'];
        }

        $new_id = $this->customer->addCustomer(
            $data['name'],
            $data['email'],
            $data['password'],
            $data['country'],
            $data['city'],
            $data['contact']
        );

        if ($new_id === false) {
            return ['success' => false, 'error' => 'Could not create account. Try again.'];
        }

        return ['success' => true, 'customer_id' => $new_id];
    }
    /**
     * Attempt login.
     * @return array ['success' => true, 'customer' => array]
     *             | ['success' => false, 'error' => string]
     */
    public function login($email, $pass)
    {
        if ($email === '' || $pass === '') {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        $row = $this->customer->login($email, $pass);

        if ($row === false) {
            return ['success' => false, 'error' => 'Invalid email or password.'];
        }

        return ['success' => true, 'customer' => $row];
    }
}
?>