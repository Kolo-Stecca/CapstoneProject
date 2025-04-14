<?php
session_start();
require('../model/database.php');  // Ensure database connection
require('../model/tasks.php');     // Ensure access to add_task function

// Function to format phone number
function format_phone_number($phone) {
    // Remove all non-digit characters
    $digits = preg_replace('/\D/', '', $phone);
    // Format to 000-000-0000
    if (strlen($digits) === 10) {
        return substr($digits, 0, 3) . '-' . substr($digits, 3, 3) . '-' . substr($digits, 6);
    }
    return $phone; // Return original if not valid
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $db;  // Make sure $db is accessible

    // Get input values from the form
    $task_name = filter_input(INPUT_POST, 'task_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $project_id = filter_input(INPUT_POST, 'project_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Can be "new_project"
    $is_billable = isset($_POST['is_billable']) ? 1 : 0;  // Ensures unchecked checkbox defaults to 0

    // Ensure employee ID is set in session
    if (!isset($_SESSION['employee_id'])) {
        die("Error: Employee not logged in.");
    }
    $employee_id = $_SESSION['employee_id'];

    // Validate required inputs
    if (!$task_name || !$project_id) {
        die("Error: Missing required fields.");
    }

    // If "New Project" was selected, insert it into the database
    if ($project_id === "new_project" && !empty($_POST['new_project_name'])) {
        $new_project_name = filter_input(INPUT_POST, 'new_project_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $client_id = filter_input(INPUT_POST, 'client_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Get the selected client ID

        // Ensure client_id is valid before inserting
        if ($client_id === 'new_client') {
            // Handle adding a new client
            $new_client_name = filter_input(INPUT_POST, 'new_client_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $contact_email = filter_input(INPUT_POST, 'contact_email', FILTER_SANITIZE_EMAIL);
            $phone_number = format_phone_number(filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_STRING)); // Format phone number

            // Get street, city, and zipcode
            $street = filter_input(INPUT_POST, 'street', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $zipcode = filter_input(INPUT_POST, 'zipcode', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Validate the new client name
            if (empty($new_client_name) || empty($contact_email) || empty($street) || empty($city) || empty($zipcode)) {
                die("Error: All fields are required for the new client.");
            }

            // Format the address
            $address = $street . ', ' . $city . ', ' . $zipcode;

            // Validate the email
            if (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
                die("Error: Invalid email format. Please include an '@' symbol.");
            }

            // Insert the new client
            $insertClientStmt = $db->prepare("INSERT INTO client (CLIENT_NAME, CONTACT_EMAIL, PHONE_NUMBER, ADDRESS) VALUES (:client_name, :contact_email, :phone_number, :address)");
            $insertClientStmt->bindParam(':client_name', $new_client_name);
            $insertClientStmt->bindParam(':contact_email', $contact_email);
            $insertClientStmt->bindParam(':phone_number', $phone_number);
            $insertClientStmt->bindParam(':address', $address);
            $insertClientStmt->execute();

            // Get the new client ID
            $client_id = $db->lastInsertId();
        } else {
            // Validate existing client_id
            $client_id = filter_var($client_id, FILTER_VALIDATE_INT);
            if (!$client_id) {
                die("Error: Invalid client selected.");
            }
        }

        // Insert new project
        $stmt = $db->prepare("INSERT INTO project (PROJECT_NAME, CLIENT_ID) VALUES (?, ?)");
        $stmt->execute([$new_project_name, $client_id]); // Insert with CLIENT_ID

        // Get the newly inserted project's ID
        $project_id = $db->lastInsertId();
    } else {
        // Ensure project_id is a valid integer (if it's not a new project)
        $project_id = filter_var($project_id, FILTER_VALIDATE_INT);
        if (!$project_id) {
            die("Error: Invalid project selected.");
        }
    }

    // Add the task
    add_task($task_name, $project_id, $is_billable, $employee_id, $client_id); // Include $client_id here

    // Redirect back to task list
    header('Location: index.php');
    exit();
} else {
    die("Error: Invalid request method.");
}


?>
