<?php
session_start();
require('../CA_CapstoneProject/model/employees.php'); // Include employee model

// Redirect if already logged in
if (isset($_SESSION['email'])) {
    header('Location: /CA_CapstoneProject/index.php'); // Adjust the path to your main page
    exit();
}

// Handle any error messages passed in the URL
$errors = filter_input(INPUT_GET, 'errors', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if ($errors === null) {
    $errors = ''; // Set a default empty string
}
?>

<?php include "view/header_login.php"; // Include header for the login page ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="http://localhost/CA_CapstoneProject/styles.css">
</head>
<body>
<br/><br/>
<form action="/CA_CapstoneProject/users/index.php" method="post" class="login-form">
   Email: <input type="text" name="email" placeholder="Email" size="10" required>
   Password: <input type="password" name="password" placeholder="Password" size="10" required>
   <input type="hidden" name="action" value="login"/>
   <input type="submit" value="Submit" />
   <?php if (!empty($errors)): ?>
       <div class="errors"><?= htmlspecialchars($errors) ?></div>
   <?php endif; ?>
</form>
</body>
</html>
