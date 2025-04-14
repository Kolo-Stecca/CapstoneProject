<?php
// Check if the session is not started, then start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require('../model/database.php');

// Get employee ID from session
$employee_id = $_SESSION['employee_id'];

// Fetch the time logs for the employee using the model function
$time_logs = get_time_logs($employee_id); // Get time logs for the logged-in employee
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Time Logs</title>
    <link rel="stylesheet" type="text/css" href="/CA_CapstoneProject/styles.css">
</head>
<body>
<header>
    <?php include('../view/header.php'); ?>
</header>
<main>



    <!-- Shows all the current user's time -->
    <h1>Your Time Logs</h1>
    <?php if (empty($time_logs)): ?>
        <p>No time logs found.</p>
    <?php else: ?>
        <a href="add_time_log.php" class="small-button" style="margin-top: 20px; margin-bottom: 20px">Add Entry</a>
        <table>
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>Date Logged</th>
                    <th>Hours Worked</th>
                    <th>Starting Times</th>
                    <th>Ending Times</th>
                    <th>Is Billable</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($time_logs as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['TIME_LOG_ID']) ?></td>
                        <td><?= !empty($log['DATE_LOGGED']) ? htmlspecialchars(date('n/j/Y', strtotime($log['DATE_LOGGED']))) : 'N/A' ?></td>
                        <td><?= !empty($log['HOURS_WORKED']) ? htmlspecialchars($log['HOURS_WORKED']) . ' Hours' : '0 Hours' ?></td>
                        <td><?= !empty($log['START_TIME']) ? htmlspecialchars(date('H:i', strtotime($log['START_TIME']))) : 'N/A' ?></td>
                        <td><?= !empty($log['END_TIME']) ? htmlspecialchars(date('H:i', strtotime($log['END_TIME']))) : 'N/A' ?></td>
                        <td><?= htmlspecialchars($log['IS_BILLABLE'] ? 'Yes' : 'No') ?></td>
                        <td><?= htmlspecialchars($log['NOTES']) ?></td>
                        <td>
                            <button class="button editBtn"
                                data-logid="<?= htmlspecialchars($log['TIME_LOG_ID']) ?>"
                                data-start="<?= htmlspecialchars($log['START_TIME']) ?>"
                                data-end="<?= htmlspecialchars($log['END_TIME']) ?>"
                                data-date="<?= htmlspecialchars($log['DATE_LOGGED']) ?>"
                                data-billable="<?= htmlspecialchars($log['IS_BILLABLE']) ?>"
                                data-notes="<?= htmlspecialchars($log['NOTES']) ?>">
                                Edit
                            </button>
                            <button class="button delete" onclick="deleteTimeLog(<?= htmlspecialchars($log['TIME_LOG_ID']) ?>)">
                                Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Time Log</h2>
            <form action="edit_time.php" method="post">
                <input type="hidden" name="time_log_id" id="time_log_id">

                <label>Start Time:</label>
                <input type="datetime-local" name="start_time" id="start_time" required>

                <label>End Time:</label>
                <input type="datetime-local" name="end_time" id="end_time" required>

                <label>Date Logged:</label>
                <input type="date" name="date_logged" id="date_logged" required>

                <div class="is-billable-container">
                    <label for="is_billable">Is Billable:</label><br>
                    <input type="checkbox" name="is_billable" id="is_billable" <?= $log['IS_BILLABLE'] ? 'checked' : '' ?>>
                </div>

                <label>Notes:</label><br>
                <textarea name="notes" id="notes" required><?= htmlspecialchars($log['NOTES']) ?></textarea>

                <input type="submit" value="Save Changes">
                <input type="button" class="cancel" value="Cancel" onclick="window.location.href='time_logs_view.php'">
            </form>
        </div>
    </div>

</main>
<footer>
    <?php include('../view/footer.php'); ?>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('editModal');
    const closeModal = document.querySelector('.close');

    // Hide the modal on page load
    modal.style.display = 'none';

    document.querySelectorAll('.editBtn').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('time_log_id').value = this.dataset.logid;
            document.getElementById('start_time').value = this.dataset.start.replace(" ", "T");
            document.getElementById('end_time').value = this.dataset.end.replace(" ", "T");
            document.getElementById('date_logged').value = this.dataset.date;
            document.getElementById('is_billable').checked = this.dataset.billable == "1";
            document.getElementById('notes').value = this.dataset.notes;
            modal.style.display = 'block'; // Show modal when edit button is clicked
        });
    });

    closeModal.addEventListener('click', function () {
        modal.style.display = 'none'; // Hide modal on close
    });

    window.addEventListener('click', function (event) {
        if (event.target == modal) {
            modal.style.display = 'none'; // Hide modal if clicking outside
        }
    });
});

function deleteTimeLog(timeLogId) {
    if (confirm('Are you sure you want to delete this time log?')) {
        fetch('delete_time.php?id=' + timeLogId, {
            method: 'GET'
        })
        .then(response => {
            if (response.ok) {
                alert('Time log deleted successfully!');
                location.reload(); // Reload the page to see the changes
            } else {
                alert('Error deleting time log. Please try again.');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>
</body>
</html>
