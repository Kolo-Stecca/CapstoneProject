<?php
require_once('../../model/employees.php'); // Ensure this path is correct
include('../../view/header.php');

// Check if the user is logged in (optional)
if (!isset($_SESSION['employee_id'])) {
    header('Location: /CA_CapstoneProject/loginform.php?errors=Please log in first');
    exit;
}

// Fetch all employees
$employees = get_users();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Employees</title>
    <link rel="stylesheet" type="text/css" href="../../styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .filter-container {
            margin-bottom: 20px;
            text-align: left; /* Align filter container to the left */
        }
        #filterInput {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .add-button {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .add-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>All Tasks for Employee</h2>

<div class="filter-container">
    <input type="text" id="filterInput" placeholder="Filter by Task Name, Project, Employee, or Status" onkeyup="filterTable()">
</div>

<a href="add_task.php" class="add-button">Add Task</a>

<table>
    <thead>
        <tr>
            <th>Task ID</th>
            <th>Task Name</th>
            <th>Project</th>
            <th>Assigned Employee</th>
            <th>Is Billable</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($tasks)): ?>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?= htmlspecialchars($task['TASK_ID']) ?></td>
                    <td><?= htmlspecialchars(html_entity_decode($task['TASK_NAME'])) ?></td>
                    <td><?= htmlspecialchars(html_entity_decode($task['PROJECT_NAME'])) ?></td>
                    <td><?= htmlspecialchars(html_entity_decode($task['EMPLOYEE_NAME'])) ?></td>
                    <td><?= $task['IS_BILLABLE'] ? 'Yes' : 'No' ?></td>
                    <td><?= htmlspecialchars(html_entity_decode($task['TASK_STATUS'])) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No tasks found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
function filterTable() {
    const filter = document.getElementById("filterInput").value.toLowerCase();
    const table = document.querySelector("table");
    const rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) { // Start at 1 to skip the header row
        const cells = rows[i].getElementsByTagName("td");
        let showRow = false;

        // Check each cell in the row
        for (let j = 0; j < cells.length; j++) {
            const cellValue = cells[j].textContent || cells[j].innerText;
            if (cellValue.toLowerCase().includes(filter)) {
                showRow = true;
                break; // No need to check further if one cell matches
            }
        }

        rows[i].style.display = showRow ? "" : "none"; // Show or hide the row
    }
}
</script>

</body>
</html>
