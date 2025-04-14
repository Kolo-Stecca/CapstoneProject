<?php

require('../model/tasks.php');
require('../model/client.php');
require('../model/projects.php');
require_once('../model/employees.php');
include('../view/header.php');

$employee_id = $_SESSION['employee_id'];
$projects = get_projects_by_employee($employee_id);
$employees = get_all_employees();
$clients = get_all_clients();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h1>Add New Task</h1>
    <form action="submit_task.php" method="POST" class="add-task-form">
        <div class="form-group">
            <label for="task_name">Task Name:</label>
            <input type="text" name="task_name" required>
        </div>

        <!-- Client Selection -->
        <div class="form-group">
            <label for="client_id">Client:</label>
            <select name="client_id" id="client_id" required onchange="toggleNewClient()">
                <option value="">Select Client</option>
                <option value="new_client">New Client</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= htmlspecialchars($client['CLIENT_ID']) ?>">
                        <?= htmlspecialchars($client['CLIENT_NAME']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- New Client Fields (Hidden by Default) -->
        <div class="form-group" id="new-client-section" style="display: none;">
            <label for="new_client_name">New Client Name:</label>
            <input type="text" name="new_client_name" required>

            <label for="contact_email">Contact Email:</label>
            <input type="email" name="contact_email" required>

            <label for="phone_number">Phone Number:</label>
            <input type="text" name="phone_number">

            <label for="street">Street:</label>
            <input type="text" name="street" required>

            <label for="city">City:</label>
            <input type="text" name="city" required>

            <label for="zipcode">Zip Code:</label>
            <input type="text" name="zipcode" required>
        </div>

        <!-- Project Selection -->
        <div class="form-group">
            <label for="project_id">Project:</label>
            <select name="project_id" id="project_id" required onchange="toggleNewProject()">
                <option value="">Select Project</option>
                <option value="new_project">New Project</option>
                <?php foreach ($projects as $project): ?>
                    <option value="<?= htmlspecialchars($project['PROJECT_ID']) ?>">
                        <?= htmlspecialchars($project['PROJECT_NAME']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- New Project Fields (Hidden by Default) -->
        <div class="form-group" id="new-project-section" style="display: none;">
            <label for="new_project_name">New Project Name:</label>
            <input type="text" name="new_project_name">
        </div>

        <div class="form-group">
            <label for="new_employee_assigned_task">Assign Employee:</label>
            <select name="new_employee_assigned_task">
                <option value="">None</option>
                <?php foreach ($employees as $employee): ?>
                    <option value="<?= htmlspecialchars($employee['employee_id']) ?>">
                        <?= htmlspecialchars($employee['employee_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Centered Is Billable Checkbox -->
        <div class="is-billable-container">
            <label for="is_billable">
                <input type="checkbox" name="is_billable" value="1" checked>
                Is Billable
            </label>
        </div>

        <input type="submit" class="button" value="Submit">
        <input type="button" class="button cancel" value="Cancel" onclick="window.location.href='all_tasks.php'">
    </form>

<script>
    function toggleNewProject() {
        document.getElementById('new-project-section').style.display =
            document.getElementById('project_id').value === 'new_project' ? 'block' : 'none';
    }
</script>

<script>
    function toggleNewClient() {
        document.getElementById('new-client-section').style.display =
            document.getElementById('client_id').value === 'new_client' ? 'block' : 'none';
    }
</script>

</body>
</html>
