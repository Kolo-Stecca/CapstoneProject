<?php
session_start();
require('../model/database.php'); // Include the database connection
require('../model/employees.php'); // Include the employee model

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = filter_input(INPUT_POST, 'action');

    if ($action === 'login') {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = md5(filter_input(INPUT_POST, 'password')); // Use MD5 for simplicity

        // Fetch the user record based on the provided email
        $user = get_employee_by_email($email); // Ensure this function is defined

        // Check if the user exists and verify the password
        if ($user && $user['password'] === $password) { // Compare hashed password directly
            // Set session variables
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['employee_id'] = $user['employee_id'];
            $_SESSION['job_title_name'] = $user['job_title_name'];

            // Redirect to the projects index page after successful login
            header('Location: /CA_CapstoneProject/index.php');
            exit();
        } else {
            // Invalid login, redirect back with an error message
            $errors = 'Incorrect login credentials.';
            header('Location: /CA_CapstoneProject/loginform.php?errors=' . urlencode($errors));
            exit();
        }
    }
}

// Default action to list users
$action = filter_input(INPUT_GET, 'action');
if ($action == NULL) {
    $action = 'list_users';
}

if ($action == 'list_users') {
    $users = get_users();
    // Include the users display page
    include('users_all_users.php');
}

// Function to get employee by email
function get_employee_by_email($email) {
    global $db;
    $query = 'SELECT * FROM employees WHERE email = :email'; // Adjust the query as per your database structure
    $statement = $db->prepare($query);
    $statement->bindParam(':email', $email);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}
?>
