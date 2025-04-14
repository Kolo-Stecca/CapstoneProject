<?php
include('../view/header.php');
require('../model/tasks.php');

$employee_id = $_SESSION['employee_id'];
$tasks = get_tasks_by_employee($employee_id);

include('tasks_view.php');
?>
