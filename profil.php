<?php
include_once 'bdd.php';
include('header.php');

if (!isset($_SESSION['login'])) {
    header("Location: connexion.php");
    exit();
}

$erreur = '';
$succes = '';

$userId = $_SESSION['id'];

$stmt = $bdd->prepare("SELECT login, prenom, nom FROM utilisateurs WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: connexion.php");
    exit();
}

if (isset($_POST['submit'])) {
    $login = htmlspecialchars(trim($_POST['login']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $nom = htmlspecialchars(trim($_POST['nom']));
    $password = $_POST['password'];
    $conf_password = $_POST['conf_password'];

    if (!empty($login) && !empty($prenom) && !empty($nom)) {
        if ($login !== $user['login']) {
            $checkLogin = $bdd->prepare("SELECT id FROM utilisateurs WHERE login = ? AND id != ?");
            $checkLogin->execute([$login, $userId]);
            if ($checkLogin->rowCount() > 0) {
                $erreur = "Ce login est déjà utilisé.";
            }
        }

        if (!$erreur) {
            if ($password !== '') {
                if ($password === $conf_password) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                } else {
                    $erreur = "Les mots de passe ne correspondent pas.";
                }
            }

            if (!$erreur) {
                if (isset($hashedPassword)) {
                    $update = $bdd->prepare("UPDATE utilisateurs SET login = ?, prenom = ?, nom = ?, password = ? WHERE id = ?");
                    $update->execute([$login, $prenom, $nom, $hashedPassword, $userId]);
                } else {
                    $update = $bdd->prepare("UPDATE utilisateurs SET login = ?, prenom = ?, nom = ? WHERE id = ?");
                    $update->execute([$login, $prenom, $nom, $userId]);
                }

                $_SESSION['login'] = $login;
                $_SESSION['prenom'] = $prenom;
                $_SESSION['nom'] = $nom;

                $succes = "Profil mis à jour avec succès.";
                $user['login'] = $login;
                $user['prenom'] = $prenom;
                $user['nom'] = $nom;
            }
        }
    } else {
        $erreur = "Les champs login, prénom et nom sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Profil - Module Connexion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css" />
</head>
<body>

<main class="form-container">
    <div class="form-profil">
        <h2>Modifier mon profil</h2>

        <form method="POST" action="">
            <label for="login">Login</label>
            <input type="text" id="login" name="login" required value="<?= htmlspecialchars($user['login']) ?>">

            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" required value="<?= htmlspecialchars($user['prenom']) ?>">

            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" required value="<?= htmlspecialchars($user['nom']) ?>">

            <label for="password">Nouveau mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Nouveau mot de passe">

            <label for="conf_password">Confirmer nouveau mot de passe</label>
            <input type="password" id="conf_password" name="conf_password" placeholder="Confirmer mot de passe">

            <input type="submit" name="submit" value="Mettre à jour" class="btn-submit" />
        </form>

        <?php if ($erreur): ?>
            <p class="error"><?= $erreur ?></p>
        <?php endif; ?>

        <?php if ($succes): ?>
            <p class="success"><?= $succes ?></p>
        <?php endif; ?>

       
    </div>
</main>

</body>
</html>