<?php
session_start(); // Start the session
require_once('../../model/database.php'); // Ensure database connection
require_once('../../model/employees.php'); // Ensure access to employee functions

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $db; // Make sure $db is accessible

    // Get input values from the form
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $job_title_name = filter_input(INPUT_POST, 'job_title_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $is_active = isset($_POST['is_active']) ? 1 : 0; // Checkbox defaults to 0 if not checked
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Validate required fields
    if (!$first_name || !$last_name || !$email || !$password) {
        die("Error: Missing required fields.");
    }

    // Insert the new employee into the database
    $stmt = $db->prepare("INSERT INTO employees (first_name, last_name, job_title_name, is_active, email, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $job_title_name, $is_active, $email, password_hash($password, PASSWORD_DEFAULT)]); // Hash the password

    // Redirect back to the employee list
    header('Location: index.php');
    exit();
} else {
    die("Error: Invalid request method.");
}
?>
