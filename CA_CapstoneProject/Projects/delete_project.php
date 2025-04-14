<?php
require('../model/database.php');
require('../model/projects.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['project_id'])) {
    $project_id = $_POST['project_id'];

    // Prepare the SQL delete statement
    $query = 'DELETE FROM project WHERE PROJECT_ID = :project_id';
    $statement = $db->prepare($query);
    $statement->bindParam(':project_id', $project_id);

    if ($statement->execute()) {
        header('Location: index.php'); // Redirect back to the projects list after deletion
        exit;
    } else {
        echo "Error deleting project.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>
