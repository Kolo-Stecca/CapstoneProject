<?php
require('../model/database.php');
require('../model/tasks.php');

$tasks = get_all_tasks_with_employees();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Tasks</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
<header>
    <?php include('../view/header.php'); ?>
</header>
<main>

    <h1>All Tasks</h1>

    <div>
        <input type="text" id="filterInput" placeholder="Filter by Task Name, Project, Employee, or Status" onkeyup="filterTable()">
    </div>

    <a href="add_task.php" class="small-button" style="margin-top: 20px; margin-bottom: 20px">Add Task</a>

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
            <?php if (empty($tasks)): ?>
                <tr>
                    <td colspan="6">No tasks found.</td>
                </tr>
            <?php else: ?>
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
            <?php endif; ?>
        </tbody>
    </table>

</main>
<footer>
    <?php include('../view/footer.php'); ?>
</footer>

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
