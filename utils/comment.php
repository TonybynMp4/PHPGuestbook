<?php
    require_once 'db.php';

    session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_SESSION['username'];
        $comment = $_POST['comment'];

        $query = "SELECT id FROM users WHERE username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        $query = "INSERT INTO comments (user_id, content) VALUES (:user_id, :content)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['user_id' => $user['id'], 'content' => $comment]);

        header('Location: index.php');
    }
?>