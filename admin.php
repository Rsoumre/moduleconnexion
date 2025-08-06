<?php
session_start();
include_once 'bdd.php';
include('header.php');

// Vérifie si l'utilisateur est admin
if (!isset($_SESSION['login']) || $_SESSION['login'] !== 'admin') {
    header('Location: connexion.php');
    exit();
}

// Récupère tous les utilisateurs avec PDO
$stmt = $bdd->query("SELECT id, login, prenom, nom FROM utilisateurs");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Module Connexion</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2 class="paragraph">Espace Admin</h2>

<div class="container-profil">
    <h3>Liste des utilisateurs :</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Login</th>
                <th>Prénom</th>
                <th>Nom</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['login']) ?></td>
                    <td><?= htmlspecialchars($user['prenom']) ?></td>
                    <td><?= htmlspecialchars($user['nom']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html> 