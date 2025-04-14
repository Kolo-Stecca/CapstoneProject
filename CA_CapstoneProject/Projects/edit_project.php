<?php
require('../model/database.php');
require('../model/projects.php');

// Function to get all projects (Add this function to your projects.php file if it doesn't exist)
if (!function_exists('get_all_projects')) {
    function get_all_projects() {
        global $db;
        $query = 'SELECT PROJECT_ID, PROJECT_NAME FROM project ORDER BY PROJECT_NAME';
        $statement = $db->prepare($query);
        $statement->execute();
        $projects = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $projects;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];
    // Fetch the project details by ID
    $query = 'SELECT * FROM project WHERE PROJECT_ID = :project_id';
    $statement = $db->prepare($query);
    $statement->bindParam(':project_id', $project_id);
    $statement->execute();
    $project = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    if (!$project) {
        echo "Project not found.";
        exit;
    }
}

// Fetch all projects for the dropdown
$all_projects = get_all_projects();

// Fetch categories for the dropdown
$categories = get_all_categories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <style>
        .edit-project-form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            width: 450px;
            margin: 40px auto;
            text-align: left;
        }

        .edit-project-form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .edit-project-form input[type="text"],
        .edit-project-form select,
        .edit-project-form textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .edit-project-form input[type="submit"],
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

        .edit-project-form input[type="submit"]:hover,
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
<h2 style="text-align: center;">Edit Project</h2>

<div class="edit-project-form">
    <form action="edit_project.php" method="POST">
        <input type="hidden" name="project_id" value="<?= htmlspecialchars($project['PROJECT_ID']) ?>">

        <label for="project_name">Project Name:</label>
        <select name="project_name" id="project_name" required>
            <option value="" disabled>Select a project</option>
            <?php foreach ($all_projects as $p): ?>
                <option value="<?= htmlspecialchars($p['PROJECT_ID']) ?>" <?= $p['PROJECT_ID'] == $project['PROJECT_ID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['PROJECT_NAME']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="">Select a category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= htmlspecialchars($category['CATEGORY']) ?>" <?= $category['CATEGORY'] === $project['CATEGORY'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['CATEGORY']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="start_date">Start Date:</label>
        <input type="datetime-local" name="start_date" value="<?= date('Y-m-d\TH:i', strtotime($project['START_DATE'])) ?>" required>

        <label for="estimated_finish_date">Estimated Finish Date:</label>
        <input type="datetime-local" name="estimated_finish_date" value="<?= date('Y-m-d\TH:i', strtotime($project['ESTIMATED_PROJECT_DUE_DATE'])) ?>">

        <label for="notes">Notes:</label>
        <textarea name="notes" required><?= htmlspecialchars($project['NOTES']) ?></textarea>

        <input type="submit" value="Update Project">
    </form>
    <!-- Cancel Button -->
    <button class="cancel-button" onclick="window.location.href='index.php'">Cancel</button>
</div>
</body>
</html>
