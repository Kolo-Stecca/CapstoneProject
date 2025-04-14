<?php
require('database.php');

function get_time_logs($employee_id) {
    global $db;
    // Update the query to select time logs for the given employee ID, ordered by DATE_LOGGED DESC
    $query = 'SELECT TIME_LOG_ID, EMPLOYEE_ID, TASK_ID, START_TIME, DATE_LOGGED, IS_BILLABLE, NOTES, END_TIME,
                     TIMESTAMPDIFF(HOUR, START_TIME, END_TIME) AS HOURS_WORKED
              FROM time_logs
              WHERE EMPLOYEE_ID = :employee_id
              ORDER BY DATE_LOGGED DESC'; // Order by most recent date

    $statement = $db->prepare($query);
    $statement->bindParam(':employee_id', $employee_id);
    $statement->execute();
    $time_logs = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $time_logs;
}

// Query for time logs of the current week
function get_weekly_logs($employee_id) {
    global $db;
    $query_week = 'SELECT TIME_LOG_ID, START_TIME, END_TIME, DATE_LOGGED, IS_BILLABLE, NOTES,
                      TIMESTAMPDIFF(HOUR, START_TIME, END_TIME) AS HOURS_WORKED
               FROM time_logs
               WHERE EMPLOYEE_ID = :employee_id
               AND DATE_LOGGED BETWEEN DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
                                  AND DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 6 DAY)
               ORDER BY DATE_LOGGED DESC';

    $statement = $db->prepare($query_week);
    $statement->bindParam(':employee_id', $employee_id);
    $statement->execute();
    $weekly_logs = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $weekly_logs;
}
