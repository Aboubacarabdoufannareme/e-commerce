<?php
/**
 * views/layout/header.php
 * Shared page header: <head>, nav.
 * NO SQL here — only session-based nav logic.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// BASE_URL is defined in core.php. If header.php is loaded without core.php,
// fall back to a sensible default so the page still renders.
if (!defined('BASE_URL')) {
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $is_local = (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false);
    define('BASE_URL', $is_local ? '/shoppn' : '/~fannareme.abdou/e-commerce/shoppn');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppn — E-Commerce</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
    <script src="<?php echo BASE_URL; ?>/js/validate.js" defer></script>
</head>
<body>

<header class="site-header">
    <div class="container nav-wrap">

        <a href="<?php echo BASE_URL; ?>/index.php" class="logo">Shoppn</a>

        <form class="search-form" action="<?php echo BASE_URL; ?>/views/search_results.php" method="GET">
            <input type="text" name="user_query" placeholder="Search products…" required>
            <button type="submit">Search</button>
        </form>

        <nav class="main-nav">
            <ul>
                <li><a href="<?php echo BASE_URL; ?>/index.php">Home</a></li>

                <?php if (function_exists('is_admin') && is_admin()): ?>
                    <li><a href="<?php echo BASE_URL; ?>/views/admin/brand.php">Brands</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/views/admin/category.php">Categories</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/views/admin/product.php">Products</a></li>
                <?php endif; ?>

                <?php if (function_exists('is_logged_in') && is_logged_in()): ?>
                    <li>Welcome, <?php echo htmlspecialchars($_SESSION['customer_name'] ?? 'User'); ?></li>
                    <li><a href="<?php echo BASE_URL; ?>/views/account/my_account.php">My Account</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="<?php echo BASE_URL; ?>/views/register.php">Register</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/views/login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>

    </div>
</header>