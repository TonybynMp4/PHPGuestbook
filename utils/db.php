<?php
    $host = 'localhost';
    $db   = 'GuestBook';
    $user = 'pma';
    $pass = null;


    try {
        $pdo = new PDO("mysql:host=$host;port=3328;dbname=$db", $user, $pass);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit();
    }
?>