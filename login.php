<?php
$error = null;

if (http_response_code() === 401) {
    $error = "Vous avez été déconnecté.";
}

session_start();
if (isset($_SESSION['username'])) {
    header('Location: index.php');
}

require_once 'utils/db.php';

if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'create') {
        $login_username = $_POST['username'];
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Vérifier si l'utilisateur existe
        $query = "SELECT * FROM users WHERE username = :login_username";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['login_username' => $login_username]);
        $user = $stmt->fetch();

        if ($user) {
            $error = "Nom d'utilisateur déjà utilisé.";
        } else {
            // Insérer l'utilisateur dans la base de données
            $query = "INSERT INTO users (username, password) VALUES (:login_username, :hashed_password)";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['login_username' => $login_username, 'hashed_password' => $hashed_password]);
            echo "Utilisateur créé avec succès.";

            header('Location: index.php');
        }
    } else if ($_POST['submit'] == 'connect') {
        $login_username = $_POST['username'];
        $login_password = $_POST['password'];

        if (empty($login_username) || empty($login_password)) {
            $error =  "Veuillez remplir tous les champs.";
            exit();
        }

        // Récupérer l'utilisateur
        $query = "SELECT * FROM users WHERE username = :login_username";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['login_username' => $login_username]);
        $user = $stmt->fetch();

        // Check si l'utilisateur existe et si le mot de passe est correct
        if ($user && password_verify($login_password, $user['password'])) {
            $_SESSION['username'] = $login_username;
            header('Location: index.php');
        } else {
            $error = "Mot de passe invalide.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte</title>
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/login.css">
</head>
<body>
    <?php require_once 'utils/header.php'; ?>
    <div class="error"><?php if (isset($error)) echo $error; ?></div>
    <main>
        <section>
            <form method="post">
                <fieldset>
                    <legend>
                        Créer un compte
                    </legend>
                    <label for=" username">
                        Nom d'utilisateur:
                    </label>
                    <input type="text" name="username" id="username">
                    <label for="password">
                        Mot de passe:
                    </label>
                    <input type="password" name="password" id="password">

                    <button type="submit" name="submit" value="create">
                        Créer un compte
                    </button>
                </fieldset>
            </form>
        </section>
        <section id="or">
            <p>
                Ou
            </p>
        </section>
        <section>
            <form method="post">
                <fieldset>
                    <legend>
                        Se connecter
                    </legend>
                    <label for=" username">
                        Nom d'utilisateur:
                    </label>
                    <input type="text" name="username" id="username">
                    <label for="password">
                        Mot de passe:
                    </label>
                    <input type="password" name="password" id="password">

                    <button type="submit" name="submit" value="connect">
                        Se connecter
                    </button>
                </fieldset>
            </form>
        </section>
    </main>
</body>

</html>