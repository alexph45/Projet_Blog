<?php
$host = 'localhost'; // Serveur de base de données
$dbname = 'blog'; // Nom de la base de données
$username = 'root'; // Nom d'utilisateur (par défaut : root)
$password = ''; // Mot de passe (par défaut vide pour XAMPP)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
