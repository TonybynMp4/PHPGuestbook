<?php
    $host = 'localhost';
    $db   = 'test';
    $user = 'pma';
    $pass = 'test';


    try {
        $pdo = new PDO("mysql:host=$host;port=3328;dbname=$db", $user, $pass);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit();
    }
?>