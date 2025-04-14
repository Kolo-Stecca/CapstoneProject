<?php
require_once('../../model/employees.php'); // Ensure this path is correct
include('../../view/header.php');

// Check if the user is logged in (optional)
if (!isset($_SESSION['employee_id'])) {
    header('Location: /CA_CapstoneProject/loginform.php?errors=Please log in first');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Employee</title>
    <link rel="stylesheet" type="text/css" href="../../styles.css">
</head>
<body>
    <h1>Add New Employee</h1>
    <form action="submit_employee.php" method="POST">
        <div>
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" required>
        </div>
        <div>
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" required>
        </div>
        <div>
            <label for="job_title_name">Job Title:</label>
            <input type="text" name="job_title_name">
        </div>
        <div>
            <label for="is_active">Is Active:</label>
            <input type="checkbox" name="is_active" value="1" checked>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
        </div>
        <!-- <div>
            <label for="profile_image">Profile Image:</label>
            <input type="file" name="profile_image">
        </div> -->
        <input type="submit" value="Add Employee">
        <input type="button" value="Cancel" onclick="window.location.href='index.php'" class="button"> <!-- Cancel Button -->
    </form>
</body>
</html>
