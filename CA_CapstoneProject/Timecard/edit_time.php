<?php
require('../model/database.php');

// edit_time.php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $time_log_id = $_GET['id'];
    $query = 'SELECT * FROM time_logs WHERE TIME_LOG_ID = :time_log_id';
    $statement = $db->prepare($query);
    $statement->bindParam(':time_log_id', $time_log_id);
    $statement->execute();
    $log = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $time_log_id = $_POST['time_log_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $date_logged = $_POST['date_logged'];
    $is_billable = isset($_POST['is_billable']) ? 1 : 0;
    $notes = $_POST['notes'];

    // Form validation
    if (empty($start_time) || empty($end_time) || empty($date_logged) || empty($notes)) {
        die("Error: Please fill in all required fields.");
    }

    $query = 'UPDATE time_logs SET START_TIME = :start_time, END_TIME = :end_time, 
                DATE_LOGGED = :date_logged, IS_BILLABLE = :is_billable, NOTES = :notes 
              WHERE TIME_LOG_ID = :time_log_id';
    $statement = $db->prepare($query);
    $statement->execute([ 
        ':start_time' => $start_time,
        ':end_time' => $end_time,
        ':date_logged' => $date_logged,
        ':is_billable' => $is_billable,
        ':notes' => $notes,
        ':time_log_id' => $time_log_id
    ]);
    header('Location: time_logs_view.php');
    exit;
}
?>

<style>
    .edit-time-log-form {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        width: 450px;
        margin: 40px auto;
        text-align: left;
    }

    .edit-time-log-form label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .edit-time-log-form input[type="text"],
    .edit-time-log-form input[type="datetime-local"],
    .edit-time-log-form input[type="date"],
    .edit-time-log-form textarea {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    .edit-time-log-form .is-billable-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px 0;
    }

    .edit-time-log-form input[type="submit"] {
        width: 100%;
        background: #000000;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        margin-top: 10px;
    }

    .edit-time-log-form input[type="submit"]:hover {
        background: #0056b3;
    }
</style>

<div class="edit-time-log-form">
    <h2>Edit Time Log</h2>
    <form action="edit_time.php" method="post">
        <input type="hidden" name="time_log_id" value="<?= htmlspecialchars($log['TIME_LOG_ID']) ?>">

        <label>Start Time:</label>
        <input type="datetime-local" name="start_time" value="<?= date('Y-m-d\TH:i', strtotime($log['START_TIME'])) ?>" required>

        <label>End Time:</label>
        <input type="datetime-local" name="end_time" value="<?= date('Y-m-d\TH:i', strtotime($log['END_TIME'])) ?>" required>

        <label>Date Logged:</label>
        <input type="date" name="date_logged" value="<?= htmlspecialchars($log['DATE_LOGGED']) ?>" required>

        <div class="is-billable-container">
            <label>
                <input type="checkbox" name="is_billable" <?= $log['IS_BILLABLE'] ? 'checked' : '' ?>> Is Billable
            </label>
        </div>

        <label>Notes:</label>
        <textarea name="notes" required><?= htmlspecialchars($log['NOTES']) ?></textarea>

        <input type="submit" value="Save Changes">
        <input type="button" class="cancel" value="Cancel" onclick="window.location.href='time_logs_view.php'">
    </form>
</div>
