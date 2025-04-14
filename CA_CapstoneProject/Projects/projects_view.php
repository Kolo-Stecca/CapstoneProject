<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Projects</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <style>
        .filter-container {
            margin-bottom: 20px; /* Space below the input */
            text-align: left; /* Align filter container to the left */
        }
        #filterInput {
            width: 100%; /* Full width of the input */
            padding: 10px; /* Padding for input */
            border: 1px solid #ccc; /* Border for input */
            border-radius: 5px; /* Rounded corners */
            box-sizing: border-box; /* Include padding and border in element's total width and height */
        }
        table {
            width: 100%;
            border-collapse: collapse; /* Collapse borders for better styling */
            margin-top: 20px; /* Space above the table */
        }
        th, td {
            border: 1px solid #ddd; /* Border for table cells */
            padding: 8px; /* Padding inside cells */
            text-align: left; /* Align text to the left */
        }
        th {
            background-color: #f4f4f4; /* Background color for table headers */
        }
        .small-button {
            padding: 10px 15px; /* Padding for buttons */
            background-color: #007bff; /* Button background color */
            color: white; /* Button text color */
            text-decoration: none; /* No underline */
            border-radius: 5px; /* Rounded corners */
            transition: background-color 0.3s; /* Smooth transition for hover effect */
        }
        .small-button:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
    </style>
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
</head>
<body>

<h1>Your Projects</h1>

<!-- Input Filter -->
<div class="filter-container">
    <input type="text" id="filterInput" placeholder="Filter by Project Name, Client, or Status" onkeyup="filterTable()">
</div>

<!-- Add Project Button -->
<?php
if (isset($_SESSION['job_title_name']) &&
    (stripos($_SESSION['job_title_name'], 'Project Manager') !== false ||
     stripos($_SESSION['job_title_name'], 'Executive') !== false)) {
    echo '<a href="add_projects.php" class="small-button">Add Project</a>';
}
?>

<!-- Container for the Projects Table -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Project ID</th>
                <th>Project Name</th>
                <th>Client Name</th>
                <th>Category</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Actions</th> <!-- Added Actions column for buttons -->
            </tr>
        </thead>
        <tbody>
            <?php if (empty($projects)): ?>
                <tr>
                    <td colspan="8">No projects found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?= htmlspecialchars($project['PROJECT_ID']) ?></td>
                        <td><?= htmlspecialchars($project['PROJECT_NAME']) ?></td>
                        <td><?= htmlspecialchars($project['CLIENT_NAME']) ?></td>
                        <td><?= htmlspecialchars($project['CATEGORY']) ?></td>
                        <td><?= htmlspecialchars($project['START_DATE']) ?></td>
                        <td><?= htmlspecialchars($project['END_DATE']) ?></td>
                        <td><?= htmlspecialchars($project['PROJECT_STATUS']) ?></td>
                        <td>
                            <button class="button editBtn" onclick="window.location.href='edit_project.php?project_id=<?= htmlspecialchars($project['PROJECT_ID']) ?>'">Edit</button>
                            <form action="delete_project.php" method="POST" style="display:inline;">
                                <input type="hidden" name="project_id" value="<?= htmlspecialchars($project['PROJECT_ID']) ?>">
                                <button type="submit" class="button delete" onclick="return confirm('Are you sure you want to delete this project?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
