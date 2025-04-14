<?php
session_start(); // Start the session
require_once('../../model/database.php'); // Ensure database connection
require_once('../../model/employees.php'); // Ensure access to employee functions

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $db; // Make sure $db is accessible

    // Get input values from the form
    $employee_id = filter_input(INPUT_POST, 'employee_id', FILTER_VALIDATE_INT);
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $job_title_name = filter_input(INPUT_POST, 'job_title_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $is_active = isset($_POST['is_active']) ? 1 : 0; // Checkbox defaults to 0 if not checked
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Validate required fields
    if (!$employee_id || !$first_name || !$last_name || !$email) {
        die("Error: Missing required fields.");
    }

    // Prepare the SQL update statement
    $updateStmt = $db->prepare("UPDATE employees SET first_name = ?, last_name = ?, job_title_name = ?, is_active = ?, email = ?, password = ? WHERE employee_id = ?");

    // Check if password is provided; if not, do not change the password
    if (empty($password)) {
        // Update without changing password
        $updateStmt->execute([$first_name, $last_name, $job_title_name, $is_active, $email, $current_password_hash, $employee_id]);
    } else {
        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $updateStmt->execute([$first_name, $last_name, $job_title_name, $is_active, $email, $hashed_password, $employee_id]);
    }

    // Redirect back to the employee list
    header('Location: index.php');
    exit();
} else {
    die("Error: Invalid request method.");
}
?>
