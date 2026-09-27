<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/layout/header.php';
?>

<main class="container">
    <h1>Login</h1>

    <?php if (!empty($_SESSION['error'])): ?>
        <p class="form-error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <p class="form-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <form id="login-form" action="../actions/login_action.php" method="POST" novalidate>
        <p>
            <label>Email<br>
                <input type="email" name="customer_email" id="login_email" required>
                <span class="field-error" id="err-login_email"></span>
            </label>
        </p>

        <p>
            <label>Password<br>
                <input type="password" name="customer_pass" id="login_pass" required>
                <span class="field-error" id="err-login_pass"></span>
            </label>
        </p>

        <p>
            <button type="submit" id="login-submit">Login</button>
        </p>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</main>

<?php require_once __DIR__ . '/layout/footer.php'; ?>