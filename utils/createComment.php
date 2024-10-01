<?php
    require_once 'db.php';

    session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_SESSION['username'])) {
            header('Location: ../login.php', true, 401);
            exit();
        } else if (empty($_POST['content'])) {
            echo "Veuillez remplir le champ commentaire.";
            exit();
        }

        $username = $_SESSION['username'];
        $comment = htmlspecialchars($_POST['content']);

        $query = "SELECT id FROM users WHERE username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        $query = "INSERT INTO comments (user_id, content) VALUES (:user_id, :content)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['user_id' => $user['id'], 'content' => $comment]);

        header('Location: ../index.php');
    }
?>