<?php

session_start();
include_once 'bdd.php';
include ('header.php');


// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header("Location: connexion.php");
    exit();
}

// Récupération des données de session
$login = $_SESSION['login'];
$prenom = $_SESSION['prenom'] ?? '';
$nom = $_SESSION['nom'] ?? '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2 class="paragraph">Bienvenue sur votre profil</h2>

<div class="container-profil">
    <p><strong>Login :</strong> <?= htmlspecialchars($login) ?></p>
    <p><strong>Prénom :</strong> <?= htmlspecialchars($prenom) ?></p>
    <p><strong>Nom :</strong> <?= htmlspecialchars($nom) ?></p>
</div>

</body>
</html>
