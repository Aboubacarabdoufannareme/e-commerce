<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/layout/header.php';
?>

<main class="container">
    <h1>Create an Account</h1>

    <?php if (!empty($_SESSION['error'])): ?>
        <p class="form-error"><?php echo htmlspecialchars($_SESSION['error']);
                                unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <form id="register-form" action="<?php echo BASE_URL; ?>/actions/register_action.php" method="POST" novalidate>
        <p>
            <label>Full Name<br>
                <input type="text" name="customer_name" id="customer_name" required>
                <span class="field-error" id="err-name"></span>
            </label>
        </p>

        <p>
            <label>Email<br>
                <input type="email" name="customer_email" id="customer_email" required>
                <span class="field-error" id="err-email"></span>
            </label>
        </p>

        <p>
            <label>Password <small>(min 8 chars, at least one digit)</small><br>
                <input type="password" name="customer_pass" id="customer_pass" required>
                <span class="field-error" id="err-pass"></span>
            </label>
        </p>

        <p>
            <label>Country<br>
                <select name="customer_country" id="customer_country" required>
                    <option value="">— Select —</option>
                    <option value="Ghana">Ghana</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="Kenya">Kenya</option>
                    <option value="South Africa">South Africa</option>
                    <option value="United States">United States</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="Other">Other</option>
                </select>
                <span class="field-error" id="err-country"></span>
            </label>
        </p>

        <p>
            <label>City<br>
                <input type="text" name="customer_city" id="customer_city" required>
                <span class="field-error" id="err-city"></span>
            </label>
        </p>

        <p>
            <label>Contact Number<br>
                <input type="text" name="customer_contact" id="customer_contact" required>
                <span class="field-error" id="err-contact"></span>
            </label>
        </p>

        <p>
            <button type="submit" id="register-submit">Register</button>
        </p>
    </form>

    <p>Already have an account? <a href="<?php echo BASE_URL; ?>/views/login.php">Login here</a>.</p>
</main>

<?php require_once __DIR__ . '/layout/footer.php'; ?>