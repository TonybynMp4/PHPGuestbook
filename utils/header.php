<header>
        <h1>Livre d'Or</h1>
        <nav>
            <a class='button' href="index.php">Accueil</a>
            <?php if (isset($_SESSION['username'])) : ?>
                <a class='button' href="utils/logout.php">Déconnexion</a>
            <?php else : ?>
                <a class='button' href="login.php">Connexion</a>
            <?php endif; ?>
            </ul>
        </nav>
</header>
