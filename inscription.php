<?php
session_start();
include_once 'bdd.php';
// include ('header.php'); // Décommente si tu as un header.php

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupération des données du formulaire
    $login = htmlspecialchars(trim($_POST['login']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $nom = htmlspecialchars(trim($_POST['nom']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérification si les mots de passe correspondent
    if ($password !== $confirm_password) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si le login existe déjà
        $stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = ?");
        $stmt->execute([$login]);

        if ($stmt->rowCount() > 0) {
            $message = "Ce login est déjà utilisé.";
        } else {
            // Hashage du mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insertion dans la base de données
            $insert = $bdd->prepare("INSERT INTO utilisateurs (login, prenom, nom, password) VALUES (?, ?, ?, ?)");
            $insert->execute([$login, $prenom, $nom, $hashed_password]);

            // Rediriger vers la page de connexion
            header('Location: connexion.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<nav>
    <div>
        <a href="index.php">Acceuil</a>
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

<h2 class="paragraph">Créer un compte</h2>

<?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>

<form method="post">
    <input type="text" name="login" placeholder="Login" required>
    <input type="text" name="prenom" placeholder="Prénom" required>
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
    <button type="submit">S'inscrire</button>
</form>

</body>
</html>
