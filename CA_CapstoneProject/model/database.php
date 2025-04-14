<?php
    //dsn = Data Source Name
    $dsn = 'mysql:host=localhost;dbname=ca_engineering';
    $username = 'causer';
    $password = 'pa55word';

    try {
        $db = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo 'Error Message ' . $error_message;
    }
?>