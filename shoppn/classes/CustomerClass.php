<?php
/**
 * classes/CustomerClass.php — Model
 * All customer-related DB logic. No HTML, no $_POST, no echo.
 */
require_once __DIR__ . '/../core/db_class.php';

class CustomerClass extends Database
{
    /**
     * Check if an email is already registered.
     * @return bool
     */
    public function emailExists($email)
{
    $stmt = $this->conn->prepare(
        "SELECT customer_email FROM customer WHERE customer_email = ? LIMIT 1"
    );
    if (!$stmt) {
        error_log('emailExists prepare failed: ' . $this->conn->error);
        return false;
    }
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $exists = $res && $res->num_rows > 0;
    $stmt->close();
    return $exists;
}
    /**
     * Insert a new customer.
     * Password is hashed here with bcrypt before being stored.
     * @return int|false  new customer_id on success, false on failure
     */
    public function addCustomer($name, $email, $pass, $country, $city, $contact)
    {
        $hash = password_hash($pass, PASSWORD_BCRYPT);

        $stmt = $this->conn->prepare(
            "INSERT INTO customer
                (customer_name, customer_email, customer_pass,
                 customer_country, customer_city, customer_contact, user_role)
             VALUES (?, ?, ?, ?, ?, ?, 2)"
        );
        $stmt->bind_param('ssssss', $name, $email, $hash, $country, $city, $contact);

        if (!$stmt->execute()) {
            error_log('addCustomer failed: ' . $stmt->error);
            $stmt->close();
            return false;
        }

        $new_id = $stmt->insert_id;
        $stmt->close();
        return $new_id;
    }
    /**
     * Fetch a single customer row by email.
     * @return array|false  associative row on success, false if not found
     */
    public function getCustomerByEmail($email)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM customer WHERE customer_email = ? LIMIT 1"
        );
        if (!$stmt) {
            error_log('getCustomerByEmail prepare failed: ' . $this->conn->error);
            return false;
        }
        $stmt->bind_param('s', $email);
        $stmt->execute();

        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : false;
        $stmt->close();

        return $row;
    }

    /**
     * Verify email + password.
     * @return array|false  customer row on success, false on failure
     */
    public function login($email, $pass)
    {
        $row = $this->getCustomerByEmail($email);
        if (!$row) {
            return false;
        }
        if (!password_verify($pass, $row['customer_pass'])) {
            return false;
        }
        return $row;
    }
}
?>