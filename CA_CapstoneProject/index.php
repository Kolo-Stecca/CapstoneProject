<?php
require('model/database.php');
include 'view/header.php';

$employee_id = $_SESSION['employee_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

// Fetch All Projects assigned to the employee
$queryProjects = '
SELECT p.PROJECT_ID, p.PROJECT_NAME 
FROM project p 
JOIN project_assignments pa ON p.PROJECT_ID = pa.PROJECT_ID 
WHERE pa.EMPLOYEE_ID = :employee_id';
$statement = $db->prepare($queryProjects);
$statement->bindParam(':employee_id', $employee_id);
$statement->execute();
$projects = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();

// Fetch Timecard Entries for the current week
$queryTimeLogs = '
SELECT TIME_LOG_ID, TASK_ID, START_TIME, END_TIME, 
       TIMESTAMPDIFF(HOUR, START_TIME, END_TIME) AS HOURS_WORKED 
FROM time_logs 
WHERE EMPLOYEE_ID = :employee_id 
AND YEARWEEK(START_TIME, 1) = YEARWEEK(CURDATE(), 1)';
$statement = $db->prepare($queryTimeLogs);
$statement->bindParam(':employee_id', $employee_id);
$statement->execute();
$time_logs = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();

// Fetch Assigned Tasks for the employee
$queryTasks = 'SELECT TASK_ID, TASK_NAME, IS_BILLABLE, TASK_STATUS 
               FROM tasks 
               WHERE EMPLOYEE_ID = :employee_id';
$statement = $db->prepare($queryTasks);
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
    <title>All Tasks</title>
    <link rel="stylesheet" type="text/css" href="/styles.css"> 
</head>
<body>

<main>
    <h2>Welcome <?= htmlspecialchars($first_name) ?> <?= htmlspecialchars($last_name) ?></h2>

    <div class="container">
        <section class="projects">
            <h3>Your Projects</h3>
            <?php if (empty($projects)): ?>
                <p>You have no assigned projects.</p>
            <?php else: ?>
                <ul class="project-list">
                    <?php foreach ($projects as $project): ?>
                        <li><?= htmlspecialchars($project['PROJECT_NAME']) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>

        <section class="time-logs">
            <h3>Your Time Logs (This Week)</h3>
            <?php if (empty($time_logs)): ?>
                <p>No time logs found for this week.</p>
            <?php else: ?>
                <table class="time-log-table">
                    <thead>
                        <tr>
                            <th>Task ID</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Hours Worked</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($time_logs as $log): ?>
                            <tr>
                                <td><?= htmlspecialchars($log['TASK_ID']) ?></td>
                                <td><?= htmlspecialchars($log['START_TIME']) ?></td>
                                <td><?= htmlspecialchars($log['END_TIME']) ?></td>
                                <td><?= htmlspecialchars($log['HOURS_WORKED']) ?> Hours</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>

        <section class="tasks">
            <h3>Your Assigned Tasks</h3>
            <?php if (empty($tasks)): ?>
                <p>You have no assigned tasks.</p>
            <?php else: ?>
                <table class="task-table">
                    <thead>
                        <tr>
                            <th>Task Name</th>
                            <th>Is Billable</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><?= htmlspecialchars($task['TASK_NAME']) ?></td>
                                <td><?= htmlspecialchars($task['IS_BILLABLE'] ? 'Yes' : 'No') ?></td>
                                <td><?= htmlspecialchars($task['TASK_STATUS']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php include 'view/footer.php'; ?>

</body>
</html>


