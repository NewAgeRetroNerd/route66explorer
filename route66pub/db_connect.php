<?php
// Define constants for database connection parameters
define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_NAME', '');

// Create a new MySQLi connection using the defined parameters
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check for a connection error and terminate script if it occurs
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Output the error message
}
?>
