<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $bdd = new PDO('mysql:host=localhost;dbname=harouna-soumare_moduleconnexion;charset=utf8', 
    'harouna-soumare', 'harounA.06@');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erreur BDD : " . $e->getMessage());
}
?>
