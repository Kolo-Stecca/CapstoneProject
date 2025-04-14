<?php
require_once('../../model/employees.php'); // Ensure this path is correct
include('../../view/header.php');

// Check if the user is logged in (optional)
if (!isset($_SESSION['employee_id'])) {
    header('Location: /CA_CapstoneProject/loginform.php?errors=Please log in first');
    exit;
}

// Get the employee ID from the query string
$employee_id = filter_input(INPUT_GET, 'employee_id', FILTER_VALIDATE_INT);
if (!$employee_id) {
    die("Error: Invalid employee ID.");
}

// Fetch the employee's current data
$employee = get_employee_by_id($employee_id); // Make sure this function exists

// Check if the employee data was retrieved
if (!$employee) {
    die("Error: Employee not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link rel="stylesheet" type="text/css" href="../../styles.css">
</head>
<body>
    <h1>Edit Employee</h1>
    <form action="update_employee.php" method="POST">
        <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employee['employee_id']); ?>">
        <div>
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($employee['first_name'] ?? ''); ?>" required>
        </div>
        <div>
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($employee['last_name'] ?? ''); ?>" required>
        </div>
        <div>
            <label for="job_title_name">Job Title:</label>
            <input type="text" name="job_title_name" value="<?php echo htmlspecialchars($employee['job_title_name'] ?? ''); ?>">
        </div>
        <div>
            <label for="is_active">Is Active:</label>
            <input type="checkbox" name="is_active" value="1" <?php echo isset($employee['is_active']) && $employee['is_active'] ? 'checked' : ''; ?>>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($employee['email'] ?? ''); ?>" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" placeholder="Leave blank to keep current password">
        </div>
        <div>
            <label for="profile_image">Profile Image:</label>
            <input type="file" name="profile_image">
        </div>
        <input type="submit" value="Update Employee">
        <input type="button" value="Cancel" onclick="window.location.href='index.php'" class="button"> <!-- Cancel Button -->
    </form>
</body>
</html>
