<?php
/**
 * core/core.php
 * Included on every page. Bootstraps session, config, and shared helpers.
 * NO business logic, NO SQL, NO HTML output.
 */

// ---- Session ----
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---- Timezone ----
date_default_timezone_set('Africa/Accra');
// Base URL path for this app (used for absolute redirects).
// If you ever rename the folder or deploy elsewhere, change this ONE line.
if (!defined('BASE_URL')) {
    define('BASE_URL', '/shoppn');
}

// ---- Base DB class ----
require_once __DIR__ . '/db_class.php';

// ---- Shared helpers ----

/**
 * Get the client's IP address (used for guest carts).
 */
function get_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // May contain multiple IPs — take the first
        $parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($parts[0]);
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

/**
 * Redirect to a URL and stop execution.
 */
function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

/**
 * Is a customer logged in?
 */
function is_logged_in()
{
    return isset($_SESSION['customer_id']);
}

/**
 * Is the logged-in user an admin?
 * user_role: 1 = admin, 2 = customer
 */
function is_admin()
{
    return isset($_SESSION['user_role']) && (int)$_SESSION['user_role'] === 1;
}

/**
 * Require a logged-in user. Redirects to login if not.
 */
function require_login()
{
    if (!is_logged_in()) {
        $_SESSION['error'] = 'Please log in to continue.';
        redirect(BASE_URL . '/views/login.php');
    }
}

function require_admin()
{
    if (!is_admin()) {
        $_SESSION['error'] = 'Admin access required.';
        redirect(BASE_URL . '/index.php');
    }
}
?>