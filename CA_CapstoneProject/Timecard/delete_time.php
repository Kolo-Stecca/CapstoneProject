<?php
// delete_time.php
require('../model/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $time_log_id = $_GET['id'];

    // Prepare the delete statement
    $query = 'DELETE FROM time_logs WHERE TIME_LOG_ID = :time_log_id';
    $statement = $db->prepare($query);
    $statement->bindParam(':time_log_id', $time_log_id);

    // Try to execute the delete statement
    if ($statement->execute()) {
        // Redirect to the time logs view page after successful deletion
        header('Location: time_logs_view.php');
        exit;
    } else {
        // Output an error message if the deletion fails
        echo "Error deleting record: " . htmlspecialchars($statement->errorInfo()[2]);
    }
} else {
    // Handle case when id is not provided
    echo "Invalid request.";
}
?>
