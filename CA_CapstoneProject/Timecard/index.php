<?php
session_start();
require('../model/time_logs.php');

if (!isset($_SESSION['employee_id'])) {
    echo "No employee Id found in session";
    header('Location: /CA_CapstoneProject/loginform.php');
    exit();
}

$employee_id = $_SESSION['employee_id'];
$time_logs = get_time_logs($employee_id);
include('time_logs_view.php');
?>