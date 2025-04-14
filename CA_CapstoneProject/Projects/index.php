<?php
session_start();
require('../model/projects.php');
include('../view/header.php');

$employee_id = $_SESSION['employee_id'];
$projects = get_projects_by_employee($employee_id);

include('projects_view.php');
?>
