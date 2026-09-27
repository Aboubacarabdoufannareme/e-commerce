<?php
/**
 * core/db_class.php
 * Base database class. Every Model extends this.
 * Uses MySQLi. Loads credentials from db_cred.php (gitignored).
 */
class Database
{
    protected $conn;

    public function __construct()
    {
        require_once __DIR__ . '/db_cred.php';

        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->conn->connect_error) {
            error_log('DB connection failed: ' . $this->conn->connect_error);
            die('Database connection failed. Check error log.');
        }

        // Ensure clean UTF-8 handling
        $this->conn->set_charset('utf8mb4');
    }
}
?>