<?php
session_start();
require('../model/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add_time_log') {
    $employee_id = $_SESSION['employee_id'];
    $task_id = filter_input(INPUT_POST, 'task_id', FILTER_VALIDATE_INT);
    $start_time = filter_input(INPUT_POST, 'start_time', FILTER_SANITIZE_STRING);
    $end_time = filter_input(INPUT_POST, 'end_time', FILTER_SANITIZE_STRING);
    $is_billable = filter_input(INPUT_POST, 'is_billable', FILTER_VALIDATE_BOOLEAN);
    $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);

    // Convert start and end times to proper datetime format (YYYY-MM-DD HH:MM:SS)
    $start_time = date('Y-m-d H:i:s', strtotime($start_time));
    $end_time = date('Y-m-d H:i:s', strtotime($end_time));

    $query = 'INSERT INTO time_logs (EMPLOYEE_ID, TASK_ID, START_TIME, END_TIME, DATE_LOGGED, IS_BILLABLE, NOTES) 
              VALUES (:employee_id, :task_id, :start_time, :end_time, NOW(), :is_billable, :notes)';
    
    $statement = $db->prepare($query);
    $statement->bindParam(':employee_id', $employee_id);
    $statement->bindParam(':task_id', $task_id);
    $statement->bindParam(':start_time', $start_time);
    $statement->bindParam(':end_time', $end_time);
    $statement->bindParam(':is_billable', $is_billable);
    $statement->bindParam(':notes', $notes);

    if ($statement->execute()) {
        header('Location: index.php'); // Redirect after success
        exit();
    } else {
        echo "Error inserting time log.";
    }
}
?>
