<?php
require('../model/database.php');

$employee_id = $_SESSION['employee_id'];

$query = 'SELECT t.TASK_ID, t.TASK_NAME, p.PROJECT_NAME, t.IS_BILLABLE
          FROM tasks t
          JOIN project p ON t.PROJECT_ID = p.PROJECT_ID
          WHERE t.EMPLOYEE_ID = :employee_id';
$statement = $db->prepare($query);
$statement->bindParam(':employee_id', $employee_id);
$statement->execute();
$tasks = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Tasks</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>

<h2>Your Tasks</h2>

<!-- Container for the Tasks Table -->
<div class="table-container"> <!-- Add this div to create a container -->
    <table>
        <thead>
            <tr>
                <th>Task ID</th>
                <th>Task Name</th>
                <th>Project</th>
                <th>Is Billable</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tasks)): ?>
                <tr>
                    <td colspan="4">No tasks found. <a href="add_task.php">Add a task</a></td>
                </tr>
            <?php else: ?>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?= htmlspecialchars($task['TASK_ID']) ?></td>
                        <td><?= htmlspecialchars(html_entity_decode($task['TASK_NAME'])) ?></td>
                        <td><?= htmlspecialchars(html_entity_decode($task['PROJECT_NAME'] ?? 'N/A')) ?></td>
                        <td><?= isset($task['IS_BILLABLE']) ? ($task['IS_BILLABLE'] ? 'Yes' : 'No') : 'N/A' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div> <!-- Close the container -->

<?php
// Show the "Add Task" button only if the user is a Project Manager or has "Executive" in their title
if (isset($_SESSION['job_title']) && (stripos($_SESSION['job_title'], 'Project Manager') !== false || stripos($_SESSION['job_title'], 'Executive') !== false)) {
    echo '<a href="add_task.php" class="button">Add Task</a>';
}
?>

</body>
</html>
