<?php
require('../../model/database.php'); // Correct path to your database connection
require('../../model/report_db.php'); // Correct path to your reports database functions
include('../../view/header.php'); // Include header

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
        <table id="projectProgressTable"></table>
    </div>

    <!-- Employee Workload Report -->
    <div class="report-section">
        <h3>Employee Workload Report</h3>
        <canvas id="employeeWorkloadChart"></canvas>
        <table id="employeeWorkloadTable"></table>
    </div>

    <!-- Time Tracking Report -->
    <div class="report-section">
        <h3>Time Tracking Report</h3>
        <canvas id="timeTrackingChart"></canvas>
        <table id="timeTrackingTable"></table>
    </div>

    <!-- Task Completion Report -->
    <div class="report-section">
        <h3>Task Completion Report</h3>
        <canvas id="taskCompletionChart"></canvas>
        <table id="taskCompletionTable"></table>
    </div>

    <!-- Client Project Overview Report -->
    <div class="report-section">
        <h3>Client Project Overview Report</h3>
        <canvas id="clientProjectChart"></canvas>
        <table id="clientProjectTable"></table>
    </div>

    <script>
        function applyFilters() {
            // Fetch and update data based on filters
            console.log("Filters applied");
        }

        function renderCharts() {
            new Chart(document.getElementById('projectProgressChart'), { type: 'bar', data: {} });
            new Chart(document.getElementById('employeeWorkloadChart'), { type: 'pie', data: {} });
            new Chart(document.getElementById('timeTrackingChart'), { type: 'line', data: {} });
            new Chart(document.getElementById('taskCompletionChart'), { type: 'bar', data: {} });
            new Chart(document.getElementById('clientProjectChart'), { type: 'doughnut', data: {} });
        }

        document.addEventListener("DOMContentLoaded", renderCharts);
    </script>
</body>
</html>
