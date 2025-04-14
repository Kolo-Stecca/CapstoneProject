<?php
require('database.php');

function get_tasks_by_employee($employee_id) {
    global $db;
    $query = 'SELECT t.TASK_ID, t.TASK_NAME, t.TASK_STATUS, t.ESTIMATED_TASKS_DUE_DATE, p.ESTIMATED_PROJECT_DUE_DATE
              FROM tasks t
              JOIN project p ON t.PROJECT_ID = p.PROJECT_ID
              WHERE t.EMPLOYEE_ID = :employee_id';
    $statement = $db->prepare($query);
    $statement->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
    $statement->execute();
    $tasks = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $tasks;
}

function get_all_tasks_with_employees() {
    global $db;
    $query = "SELECT t.TASK_ID, t.TASK_NAME, p.PROJECT_NAME,
                     CONCAT(e.first_name, ' ', e.last_name) AS EMPLOYEE_NAME,
                     t.IS_BILLABLE, t.TASK_STATUS
              FROM tasks t
              JOIN project p ON t.PROJECT_ID = p.PROJECT_ID
              LEFT JOIN employees e ON t.EMPLOYEE_ID = e.employee_id
              ORDER BY t.TASK_ID ASC";

    $statement = $db->prepare($query);
    $statement->execute();
    $tasks = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    return $tasks;
}

function add_task($task_name, $project_id, $is_billable, $employee_id = null) {
    global $db;

    $query = "INSERT INTO tasks (TASK_NAME, PROJECT_ID, IS_BILLABLE, EMPLOYEE_ID)
              VALUES (:task_name, :project_id, :is_billable, :employee_id)";

    $statement = $db->prepare($query);
    $statement->bindValue(':task_name', $task_name);
    $statement->bindValue(':project_id', $project_id, PDO::PARAM_INT);
    $statement->bindValue(':is_billable', $is_billable, PDO::PARAM_INT);
    $statement->bindValue(':employee_id', $employee_id, PDO::PARAM_INT);

    // Handle case where employee_id may be null
    if ($employee_id === null) {
        $statement->bindValue(':employee_id', null, PDO::PARAM_NULL);
    } else {
        $statement->bindValue(':employee_id', $employee_id, PDO::PARAM_INT);
    }

    $statement->execute();
    $statement->closeCursor();
}


?>
