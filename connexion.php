<?php
session_start();
include_once 'bdd.php';

// Message d'erreur à afficher si besoin
$message = "";

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = htmlspecialchars(trim($_POST['login']));
    $password = $_POST['password'];

    // Vérifie si le login existe
    $stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Connexion réussie
        $_SESSION['id'] = $user['id'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['nom'] = $user['nom'];

        // Redirection selon si admin ou pas
        if ($_SESSION['login'] === 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: profil.php');
        }
        exit;
    } else {
        $message = "Login ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion</title>
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

<div class="login-container">
  <h2 class="h2">Connexion</h2>
  <div class="login-card">
    <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>
              <div class="avatar"></div>
    <form action="connexion.php" method="POST">
      <div class="input-group">
        <input type="text" name="login" placeholder="Login" required>
      </div>
      <div class="input-group">
        <input type="password" name="password" placeholder="Mot de passe" required>
      </div>
      <div class="options">
        <label><input type="checkbox" name="remember"> Se souvenir de moi</label>
      </div>
      <button type="submit" class="btn-login">Se connecter</button>
    </form>
  </div>
</div>
</body>
</html>
