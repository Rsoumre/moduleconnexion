<!-- header.php -->
<nav>
    <div>
        <a href="index.php">Accueil</a>
    </div>
    <div>
        <a href="inscription.php">Inscription</a>
        <a href="connexion.php">Connexion</a>

        <?php if (isset($_SESSION['login'])): ?>
            <a href="profil.php">Profil</a>
            <?php if ($_SESSION['login'] === 'admin'): ?>
                <a href="admin.php">Admin</a>
            <?php endif; ?>
            <a href="deconnexion.php">Déconnexion</a>
        <?php endif; ?>
    </div>
</nav>
