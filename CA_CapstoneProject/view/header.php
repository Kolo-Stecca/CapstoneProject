<?php
// Start the session only if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: /CA_CapstoneProject/loginform.php'); // Redirect to login form if not logged in
    exit; // Stop execution after redirect
}

// Set session variables for the logged-in user
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$job_title = $_SESSION['job_title_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CA Engineering</title>
    <link rel="stylesheet" type="text/css" href="/CA_CapstoneProject/styles.css">
    <script>
        function toggleMenu() {
            const navLinks = document.getElementById("navLinks");
            navLinks.classList.toggle("active");
        }
    </script>
</head>
<body>
<header>
    <div class="navbar">
        <span class="hamburger" onclick="toggleMenu()">&#9776;</span> <!-- Hamburger icon -->
        <img src="/CA_CapstoneProject/src/logo.png" alt="Logo" class="logo">
        <div class="nav-container">
            <div class="nav-links" id="navLinks">
                <a href="/CA_CapstoneProject/index.php">Home</a>
                <a href="/CA_CapstoneProject/Timecard/index.php">Timecard</a>
                <a href="/CA_CapstoneProject/Projects/index.php">Projects</a>
                <a href="/CA_CapstoneProject/Tasks/index.php">Tasks</a>
            </div>

            <!-- Manager Links -->
            <?php if ($job_title === 'Executive Vice President' || $job_title === 'project manager'): ?>
                <div class="executive-links">
                    <a href="/CA_CapstoneProject/Tasks/all_tasks.php">All Tasks</a>
                    <!-- <a href="/CA_CapstoneProject/admin/Reports/index.php">Reports</a> -->
                    <a href="/CA_CapstoneProject/admin/Employees/index.php">Employees</a>
                    <a href="/CA_CapstoneProject/admin/clients/clients_view.php">Clients</a>
                </div>
            <?php endif; ?>
        </div>
        <div class="logout">
            <!-- <img src="/CA_CapstoneProject/src/profile.png" alt="Profile" class="profile-img"> -->
            <a href="/CA_CapstoneProject/logout.php" class="logout">Logout</a>
        </div>
    </div>
</header>
