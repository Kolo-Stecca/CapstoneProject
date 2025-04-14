<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('../model/database.php');
require_once('../model/projects.php');
require_once('../model/tasks.php');
require_once('../model/client.php');
require_once('../model/employees.php');

include('../view/header.php');

$categories = get_all_categories();
$employees = get_all_employees();
$clients = get_all_clients();

$employee_id = $_SESSION['employee_id'];
$projects = get_projects_by_employee($employee_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <style>
        .add-project-form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            width: 450px;
            margin: 40px auto;
            text-align: left;
        }

        .add-project-form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .add-project-form input[type="text"],
        .add-project-form select,
        .add-project-form textarea,
        .add-project-form input[type="datetime-local"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .add-project-form input[type="submit"],
        .cancel-button {
            width: 100%; /* Make both buttons the same width */
            background: #000000;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .add-project-form input[type="submit"]:hover,
        .cancel-button:hover {
            background: #0056b3; /* Change hover color for both buttons */
        }

        .cancel-button {
            background: #ff4d4d; /* Red color */
        }

        .cancel-button:hover {
            background: #c82333; /* Darker red on hover */
        }
    </style>
</head>
<body>

<h2 style="text-align: center;">Add New Project</h2>
<form action="process_add_project.php" method="POST" class="add-project-form">
    <div class="form-container">
        <div class="form-group">
            <label for="project_name">Project Name:</label>
            <input type="text" name="project_name" required>
        </div>
        <div class="form-group">
            <label for="client_id">Select Client:</label>
            <select name="client_id" id="client_id" required>
                <option value="">Select a client</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= htmlspecialchars($client['CLIENT_ID']) ?>"><?= htmlspecialchars($client['CLIENT_NAME']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="category">Category:</label>
            <select name="category" id="category" required>
                <option value="">Select a category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['CATEGORY']) ?>"><?= htmlspecialchars($category['CATEGORY']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="datetime-local" id="start_date" name="start_date" required>
        </div>
        <div class="form-group">
            <label for="estimated_finish_date">Estimated Finish Date:</label>
            <input type="datetime-local" id="estimated_finish_date" name="estimated_finish_date">
        </div>
        <div class="form-group">
            <label for="notes">Project Notes:</label>
            <textarea id="notes" name="notes" placeholder="Describe the project parameters..."></textarea>
        </div>
        <input type="submit" class="button" value="Submit">
        <!-- Cancel Button -->
        <button type="button" class="button" onclick="window.location.href='index.php'">Cancel</button>
        </div>
</form>



</body>
</html>
