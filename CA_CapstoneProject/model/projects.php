<?php
require_once('database.php');

// Function to get projects by employee ID
if (!function_exists('get_projects_by_employee')) {
    function get_projects_by_employee($employee_id) {
        global $db;

        // Check if the employee is a Project Manager or Executive
        $queryCheckRole = 'SELECT job_title_name FROM employees WHERE employee_id = :employee_id';
        $statement = $db->prepare($queryCheckRole);
        $statement->bindParam(':employee_id', $employee_id);
        $statement->execute();
        $role = $statement->fetchColumn();
        $statement->closeCursor();

        // If the user is a Project Manager or Executive, get all projects
        if (stripos($role, 'Project Manager') !== false || stripos($role, 'Executive') !== false) {
            $query = 'SELECT project.PROJECT_ID, project.PROJECT_NAME, client.CLIENT_NAME,
                             project.CATEGORY, project.START_DATE, project.END_DATE, project.PROJECT_STATUS
                      FROM project
                      JOIN client ON project.CLIENT_ID = client.CLIENT_ID
                      ORDER BY project.PROJECT_ID'; // Get all projects
        } else {
            // Else, get only the projects assigned to this employee
            $query = 'SELECT project.PROJECT_ID, project.PROJECT_NAME, client.CLIENT_NAME,
                             project.CATEGORY, project.START_DATE, project.END_DATE, project.PROJECT_STATUS
                      FROM project_assignments
                      JOIN project ON project_assignments.PROJECT_ID = project.PROJECT_ID
                      JOIN client ON project.CLIENT_ID = client.CLIENT_ID
                      WHERE project_assignments.EMPLOYEE_ID = :employee_id';
        }

        $statement = $db->prepare($query);
        if (stripos($role, 'Project Manager') === false && stripos($role, 'Executive') === false) {
            $statement->bindParam(':employee_id', $employee_id); // Bind only if not PM or Executive
        }
        $statement->execute();
        $projects = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $projects;
    }
}


// Function to get all categories
if (!function_exists('get_all_categories')) {
    function get_all_categories() {
        global $db;
        $query = 'SELECT DISTINCT CATEGORY FROM project ORDER BY CATEGORY';
        $statement = $db->prepare($query);
        $statement->execute();
        $categories = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $categories;
    }
}


// Function to get projects by client ID
if (!function_exists('get_projects_by_client')) {
    function get_projects_by_client($client_id) {
        global $db;

        $query = 'SELECT PROJECT_ID, PROJECT_NAME FROM project WHERE CLIENT_ID = :client_id ORDER BY PROJECT_NAME';
        $statement = $db->prepare($query);
        $statement->bindValue(':client_id', $client_id, PDO::PARAM_INT);
        $statement->execute();
        $projects = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return $projects;
    }
}
