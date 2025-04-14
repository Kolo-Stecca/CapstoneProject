<?php
require('../../model/client.php');
include('../../view/header.php');

// Fetch all clients
$clients = get_all_clients();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>

<h2>All Clients</h2>

<table>
    <thead>
        <tr>
            <th>Client Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($clients)): ?>
            <tr>
                <td colspan="5">No clients found.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?php echo htmlspecialchars($client['CLIENT_NAME']); ?></td>
                    <td><?php echo htmlspecialchars($client['CONTACT_EMAIL']); ?></td>
                    <td><?php echo htmlspecialchars($client['PHONE_NUMBER']); ?></td>
                    <td><?php echo htmlspecialchars($client['ADDRESS']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
