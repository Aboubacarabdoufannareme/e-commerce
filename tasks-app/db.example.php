<?php

$host = "localhost";
$db_user = "your_db_username";
$db_pass = "your_db_password";
$db_name = "your_database_name";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
