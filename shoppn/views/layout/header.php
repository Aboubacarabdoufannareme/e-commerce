<?php
/**
 * views/layout/header.php
 * Shared page header: <head>, nav, (cart summary comes in Task 11).
 * NO SQL here — only session-based nav logic.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppn — E-Commerce</title>
    <link rel="stylesheet" href="/shoppn/css/style.css">
<script src="/shoppn/js/validate.js" defer></script>
</head>
<body>

<header class="site-header">
    <div class="container nav-wrap">

        <a href="/shoppn/index.php" class="logo">Shoppn</a>

        <form class="search-form" action="/shoppn/views/search_results.php" method="GET">
            <input type="text" name="user_query" placeholder="Search products…" required>
            <button type="submit">Search</button>
        </form>

        <nav class="main-nav">
            <ul>
                <li><a href="/shoppn/index.php">Home</a></li>

                <?php if (is_admin()): ?>
                    <li><a href="/shoppn/views/admin/brand.php">Brands</a></li>
                    <li><a href="/shoppn/views/admin/category.php">Categories</a></li>
                    <li><a href="/shoppn/views/admin/product.php">Products</a></li>
                <?php endif; ?>

                <?php if (is_logged_in()): ?>
                    <li>Welcome, <?php echo htmlspecialchars($_SESSION['customer_name'] ?? 'User'); ?></li>
                    <li><a href="/shoppn/views/account/my_account.php">My Account</a></li>
                    <li><a href="/shoppn/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="/shoppn/views/register.php">Register</a></li>
                    <li><a href="/shoppn/views/login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>

    </div>
</header>