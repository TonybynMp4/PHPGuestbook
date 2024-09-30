<header>
        <h1>Livre d'Or</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <?php if (isset($_SESSION['username'])) : ?>
                <a href="logout.php">Déconnexion</a>
            <?php else : ?>
                <a href="login.php">Connexion</a>
            <?php endif; ?>
            </ul>
        </nav>
</header>
