<?php
require_once 'utils/db.php';

session_start();

$query = "SELECT c.content, u.username FROM comments AS c JOIN users AS u ON c.user_id = u.id";
$stmt = $pdo->query($query);
$comments = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    $login_username = $_POST['username'];
    $login_password = $_POST['password'];

    // Récupérer l'utilisateur
    $query = "SELECT * FROM users WHERE username = :login_username";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['login_username' => $login_username]);
    $user = $stmt->fetch();

    // Check si l'utilisateur existe et si le mot de passe est correct
    if ($user && password_verify($login_password, $user['password'])) {
        $_SESSION['username'] = $login_username;
        exit();
    } else {
        echo "Mot de passe invalide.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livre d'Or</title>
    <link rel="stylesheet" href="style/common.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/header.css">
</head>

<body>
    <?php require_once 'utils/header.php'; ?>
    <main>
        <fieldset>
            <legend>Commentaires</legend>
            <?php
            if (empty($comments)) {
                echo "<p>Aucun commentaire pour le moment.</p>";
            } else {
                foreach ($comments as $comment) {
                    echo "<div>";
                    echo "<h3>{$comment['username']}</h3>";
                    echo "<p>{$comment['content']}</p>";
                    echo "</div>";
                }
            }
            ?>
        </fieldset>
        <?php if (isset($_SESSION['username'])) : ?>
            <form action="utils/createComment.php" method="post" id="commentForm">
                <label for="content">Ajouter un commentaire:</label>
                <textarea name="content" id="content" cols="75" rows="5" placeholder="Votre commentaire..." ></textarea>
                <button type="submit">Envoyer</button>
            </form>
        <?php else : ?>
            <p><a href="./login.php">Connectez-vous</a> pour laisser un commentaire.</p>
        <?php endif; ?>
    </main>
</body>

</html>