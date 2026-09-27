<?php
require_once __DIR__ . '/../../core/core.php';

if (!is_logged_in()) {
    redirect('../login.php');
}

require_once __DIR__ . '/../layout/header.php';
?>

<main class="container">
    <h1>My Account</h1>
    <?php if (!empty($_SESSION['success'])): ?>
        <p class="form-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['customer_name'] ?? 'User'); ?></strong>.</p>
    <p>Email: <?php echo htmlspecialchars($_SESSION['customer_email'] ?? ''); ?></p>
    <p>Account features (edit profile, change password, delete account) come in a later task.</p>

    <p><a href="/shoppn/index.php">← Back to Home</a></p>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>