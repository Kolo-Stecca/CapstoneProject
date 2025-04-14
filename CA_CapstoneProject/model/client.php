<?php
require('database.php'); // Ensure this path is correct

function get_all_clients() {
    global $db;

    // SQL query to get all client information
    $query = 'SELECT CLIENT_ID, CLIENT_NAME, CONTACT_EMAIL, PHONE_NUMBER, ADDRESS 
              FROM client 
              ORDER BY CLIENT_NAME';

    $statement = $db->prepare($query);
    $statement->execute();
    $clients = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    return $clients;
}
?>
