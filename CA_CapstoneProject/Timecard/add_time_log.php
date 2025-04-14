<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('../model/database.php');

$query = 'SELECT PROJECT_ID, PROJECT_NAME FROM project ORDER BY PROJECT_NAME ASC';
$statement = $db->prepare($query);
$statement->execute();
$projects = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Time Log</title>
    <link rel="stylesheet" type="text/css" href="/CA_CapstoneProject/styles.css">
</head>
<body>
<header>
    <?php include('../view/header.php'); ?>
</header>
<main>
    <h1>Add New Time Log</h1>
    <form action="process_time_log.php" method="post" style="margin-bottom: 30px;">

        <!-- Project Dropdown -->
        <label for="task_id" style="margin-bottom: 10px"><strong>Project Name:</strong></label>
        <select id="task_id" name="task_id" required style="margin-bottom: 15px; margin-top: 10px;">
            <option value="">-- Select a Project --</option>
            <?php foreach ($projects as $project): ?>
                <option value="<?= htmlspecialchars($project['PROJECT_ID']) ?>">
                    <?= htmlspecialchars_decode($project['PROJECT_NAME']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>

        <!-- Start and End Time -->
        <label for="start_time">Start Time:</label>
        <input type="datetime-local" id="start_time" name="start_time" required>
        <br><br>

        <label for="end_time">End Time:</label>
        <input type="datetime-local" id="end_time" name="end_time" required>
        <br><br>

        <!-- Billable -->
        <label for="is_billable">Is Billable:</label>
        <select id="is_billable" name="is_billable">
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
        <br><br>

        <!-- Notes Section -->
        <label for="notes">Notes:</label><br>
        <textarea id="notes" name="notes" rows="4"></textarea>
        <br><br>

        <input type="hidden" name="action" value="add_time_log">
        <input type="submit" value="Submit" class="button"> <!-- Submit button -->
        <button type="button" class="button" onclick="window.location.href='time_logs_view.php'">Cancel</button>

    </form>
</main>
<footer>
    <?php include('../view/footer.php'); ?>
</footer>
</body>
</html>
