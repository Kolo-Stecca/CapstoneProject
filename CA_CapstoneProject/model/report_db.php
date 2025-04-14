<?php
require('../../model/database.php'); // Correct path to your database connection


function getProjectProgressReport($db) {
    $query = "
        SELECT
            p.PROJECT_ID,
            p.PROJECT_NAME,
            COUNT(t.TASK_ID) AS TOTAL_TASKS,
            SUM(CASE WHEN t.COMPLETION_DATE IS NOT NULL THEN 1 ELSE 0 END) AS COMPLETED_TASKS
        FROM
            project p
        LEFT JOIN
            tasks t ON p.PROJECT_ID = t.PROJECT_ID
        GROUP BY
            p.PROJECT_ID, p.PROJECT_NAME
    ";

    $statement = $db->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}


// Function to get Employee Workload Report
function getEmployeeWorkloadReport($db) {
    $query = "
        SELECT
            e.employee_id,
            CONCAT(e.first_name, ' ', e.last_name) AS employee_name,
            COUNT(t.TASK_ID) AS total_tasks,
            SUM(CASE WHEN t.STATUS = 'Completed' THEN 1 ELSE 0 END) AS completed_tasks
        FROM employees e
        LEFT JOIN tasks t ON e.employee_id = t.assigned_employee_id
        GROUP BY e.employee_id, employee_name
        ORDER BY total_tasks DESC;
    ";

    $statement = $db->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch data for reports
$projectProgressData = getProjectProgressReport($db);
$employeeWorkloadData = getEmployeeWorkloadReport($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Reports</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .report-section {
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
    <h2>Generate Reports</h2>

    <!-- Filters -->
    <div class="filter-container">
        <label for="dateFilter">Date:</label>
        <input type="date" id="dateFilter">

        <label for="employeeFilter">Employee:</label>
        <select id="employeeFilter">
            <option value="">All</option>
        </select>

        <label for="projectFilter">Project:</label>
        <select id="projectFilter">
            <option value="">All</option>
        </select>

        <label for="dueDateFilter">Estimated Due Date:</label>
        <input type="date" id="dueDateFilter">

        <button onclick="applyFilters()">Apply Filters</button>
    </div>

    <!-- Project Progress Report -->
    <div class="report-section">
        <h3>Project Progress Report</h3>
        <canvas id="projectProgressChart"></canvas>
        <table id="projectProgressTable">
            <tr>
                <th>Project Name</th>
                <th>Total Tasks</th>
                <th>Completed Tasks</th>
                <th>Progress (%)</th>
            </tr>
            <?php foreach ($projectProgressData as $project) : ?>
                <tr>
                    <td><?= htmlspecialchars($project['PROJECT_NAME']); ?></td>
                    <td><?= $project['total_tasks']; ?></td>
                    <td><?= $project['completed_tasks']; ?></td>
                    <td><?= number_format($project['progress_percentage'], 2); ?>%</td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Employee Workload Report -->
    <div class="report-section">
        <h3>Employee Workload Report</h3>
        <canvas id="employeeWorkloadChart"></canvas>
        <table id="employeeWorkloadTable">
            <tr>
                <th>Employee Name</th>
                <th>Total Tasks</th>
                <th>Completed Tasks</th>
            </tr>
            <?php foreach ($employeeWorkloadData as $employee) : ?>
                <tr>
                    <td><?= htmlspecialchars($employee['employee_name']); ?></td>
                    <td><?= $employee['total_tasks']; ?></td>
                    <td><?= $employee['completed_tasks']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script>
        function applyFilters() {
            console.log("Filters applied");
        }

        function renderCharts() {
            // Project Progress Chart
            const ctx1 = document.getElementById('projectProgressChart').getContext('2d');
            const projectNames = <?= json_encode(array_column($projectProgressData, 'PROJECT_NAME')); ?>;
            const progressData = <?= json_encode(array_column($projectProgressData, 'progress_percentage')); ?>;

            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: projectNames,
                    datasets: [{
                        label: 'Project Progress (%)',
                        data: progressData,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });

            // Employee Workload Chart
            const ctx2 = document.getElementById('employeeWorkloadChart').getContext('2d');
            const employeeNames = <?= json_encode(array_column($employeeWorkloadData, 'employee_name')); ?>;
            const taskCounts = <?= json_encode(array_column($employeeWorkloadData, 'total_tasks')); ?>;

            new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: employeeNames,
                    datasets: [{
                        label: 'Employee Workload',
                        data: taskCounts,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderWidth: 1
                    }]
                }
            });
        }

        document.addEventListener("DOMContentLoaded", renderCharts);
    </script>
</body>
</html>
