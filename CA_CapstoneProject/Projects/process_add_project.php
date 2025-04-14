<?php
session_start();
require('../model/database.php');
require('../model/projects.php');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_name = trim($_POST['project_name']);
    $client_id = trim($_POST['client_id']);
    $category = trim($_POST['category']);
    $start_date = trim($_POST['start_date']);
    $estimated_finish_date = trim($_POST['estimated_finish_date']);
    $notes = trim($_POST['notes']);

    // Debugging output for form values
    echo "Project Name: " . htmlspecialchars($project_name) . "<br>";
    echo "Client ID: " . htmlspecialchars($client_id) . "<br>";
    echo "Category: " . htmlspecialchars($category) . "<br>";
    echo "Start Date: " . htmlspecialchars($start_date) . "<br>";
    echo "Estimated Finish Date: " . htmlspecialchars($estimated_finish_date) . "<br>";
    echo "Notes: " . htmlspecialchars($notes) . "<br>";

    // Validate input
    if (empty($project_name) || empty($client_id) || empty($start_date) || empty($category)) {
        echo "Please fill in all required fields.";
        exit;
    }

    // Prepare the SQL insert statement for the project
    $query = 'INSERT INTO project (PROJECT_NAME, CLIENT_ID, CATEGORY, START_DATE, ESTIMATED_PROJECT_DUE_DATE, PROJECT_STATUS, IS_BILLABLE, NOTES)
              VALUES (:project_name, :client_id, :category, :start_date, :estimated_finish_date, "Not Started", 0, :notes)';

    $statement = $db->prepare($query);
    $statement->bindParam(':project_name', $project_name);
    $statement->bindParam(':client_id', $client_id);
    $statement->bindParam(':category', $category);
    $statement->bindParam(':start_date', $start_date);
    $statement->bindParam(':estimated_finish_date', $estimated_finish_date);
    $statement->bindParam(':notes', $notes);

    // Check if the SQL execution is successful
    if ($statement->execute()) {
        // Get the last inserted project ID
        $project_id = $db->lastInsertId();

        // Assign the project to the current user
        $employee_id = $_SESSION['employee_id']; // Assuming employee_id is stored in session
        $queryAssignProject = 'INSERT INTO project_assignments (PROJECT_ID, EMPLOYEE_ID) VALUES (:project_id, :employee_id)';
        $assignmentStatement = $db->prepare($queryAssignProject);
        $assignmentStatement->bindParam(':project_id', $project_id);
        $assignmentStatement->bindParam(':employee_id', $employee_id); // Current user's employee ID

        // Execute the assignment query
        $assignmentStatement->execute();
        $assignmentStatement->closeCursor();

        echo "Insert successful. Redirecting to index.php...<br>";
        header('Location: index.php'); // Redirect back to the project list after success
        exit;
    } else {
        // Output error message if execution fails
        echo "Error: " . implode(", ", $statement->errorInfo());
        exit; // Stop execution if there's an error
    }
}
?>
