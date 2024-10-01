<?php
$error = http_response_code() !== 200 ? http_response_code() : null;
require_once 'utils/db.php';

session_start();

$query = "SELECT c.id, c.content, c.created_at, c.updated_at, u.username FROM comments AS c JOIN users AS u ON c.user_id = u.id ORDER BY c.created_at DESC";
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
} else if (isset($_POST['action'])) {
    if ($_POST['action'] === 'modify') {
        if (!isset($_POST['id']) || !isset($_POST['content'])) {
            header('Location: index.php', true, 400);
            exit();
        }

        $comment = htmlspecialchars($_POST['content']);
        // update comment & updated_at
        $query = "UPDATE comments SET content = :content, updated_at = NOW() WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['content' => $comment, 'id' => $_POST['id']]);

        header('Location: index.php');
    } else if ($_POST['action'] === 'delete') {
        if (!isset($_POST['id'])) {
            header('Location: ../index.php', true, 400);
            exit();
        }

        $query = "DELETE FROM comments WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $_POST['id']]);
        header('Location: index.php');
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
        <div class="error"><?php if (isset($error)) echo $error; ?></div>
        <fieldset>
            <legend>Commentaires</legend>
            <?php
            if (empty($comments)) {
                echo "<p>Aucun commentaire pour le moment.</p>";
            } else {
                foreach ($comments as $comment) {
                    ?>
                    <div class="comment">
                        <div class="top">
                            <div class="info">
                                <p class="author">
                                    <?php echo $comment['username']; ?>
                                </p>
                                <p class="muted">Posté le <?php echo $comment['created_at']; ?></p>
                                <?php if ($comment['updated_at']) {
                                    echo "<p class=\"muted\" style=\"font-style: italic;\">Modifié</p>";
                                } ?>
                            </div>
                            <?php if (isset($_SESSION['username']) && $_SESSION['username'] === $comment['username']) : ?>
                                <div class="actions">
                                    <form method="post" action="index.php">
                                        <input type="hidden" name="id" value="<?php echo $comment['id']; ?>">
                                        <button type="submit" name="action" value="modifying">Modifier</button>
                                        <button class="destructive" type="submit" name="action" value="delete">Supprimer</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="content">
                            <?php if (isset($_POST['action']) && $_POST['action'] === 'modifying' && isset($_POST['id']) && $_POST['id'] == $comment['id']) : ?>
                                <form method="post">
                                    <input type="hidden" name="id" value="<?php echo $comment['id']; ?>">
                                    <textarea name="content" cols="75" rows="5"><?php echo $comment['content']; ?></textarea>
                                    <button type="submit" name="action" value="modify">Valider</button>
                                </form>
                            <?php else : ?>
                                <p><?php echo $comment['content']; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
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