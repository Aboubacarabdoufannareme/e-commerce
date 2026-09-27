<?php
/**
 * actions/register_action.php — Action
 * Handles the register form POST. Validates, calls Controller, redirects.
 * No SQL. No HTML output.
 */
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

// 1. POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/register.php');
}

// 2. Sanitize
$name    = trim(strip_tags($_POST['customer_name']    ?? ''));
$email   = trim(strip_tags($_POST['customer_email']   ?? ''));
$pass    = $_POST['customer_pass']                    ?? '';
$country = trim(strip_tags($_POST['customer_country'] ?? ''));
$city    = trim(strip_tags($_POST['customer_city']    ?? ''));
$contact = trim(strip_tags($_POST['customer_contact'] ?? ''));

// 3. Server-side validation
$errors = [];

if (strlen($name) < 2) {
    $errors[] = 'Full name is too short.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email address.';
}
if (strlen($email) > 100) {
    // customer_email is VARCHAR(100) in the schema
    $errors[] = 'Email is too long (max 100 characters).';
}
if (strlen($pass) < 8 || !preg_match('/\d/', $pass)) {
    $errors[] = 'Password must be at least 8 characters and contain a digit.';
}
if ($country === '') {
    $errors[] = 'Please select a country.';
}
if ($city === '') {
    $errors[] = 'City is required.';
}
if (!preg_match('/^[0-9+\-\s]{7,15}$/', $contact)) {
    $errors[] = 'Contact number must be 7–15 digits (spaces, +, - allowed).';
}

if (!empty($errors)) {
    $_SESSION['error'] = implode(' ', $errors);
    redirect('../views/register.php');
}

// 4. Call the controller
$controller = new CustomerController();
$result = $controller->register([
    'name'     => $name,
    'email'    => $email,
    'password' => $pass,
    'country'  => $country,
    'city'     => $city,
    'contact'  => $contact,
]);

// 5. Handle result
if ($result['success']) {
    $_SESSION['customer_id']   = $result['customer_id'];
    $_SESSION['customer_name'] = $name;
    $_SESSION['customer_email']= $email;
    $_SESSION['user_role']     = 2;  // customer

    $_SESSION['success'] = 'Account created. Welcome!';
    redirect('../views/account/my_account.php');
}

// Failure
$_SESSION['error'] = $result['error'] ?? 'Registration failed.';
redirect('../views/register.php');
?>