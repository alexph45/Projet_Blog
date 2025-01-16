<?php
// Inclure le fichier de connexion à la base de données
require_once 'connect.php';

// Démarrer une session pour gérer les messages flash
session_start();

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validation des champs
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
        header('Location: connexion.php');
        exit;
    }

    // Préparer une requête pour récupérer l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Vérifier si le mot de passe correspond
        if (password_verify($password, $user['mot_de_passe'])) {
            // Connexion réussie, rediriger vers l'index
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['prenom'];  // Optionnel, ajouter un nom d'utilisateur pour l'afficher ailleurs
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['success'] = "Connexion réussie. Bienvenue, " . htmlspecialchars($user['prenom']) . "!";
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['error'] = "Mot de passe incorrect.";
            header('Location: connexion.php');
            exit;
        }
    } else {
        $_SESSION['error'] = "Aucun utilisateur trouvé avec cet e-mail.";
        header('Location: connexion.php');
        exit;
    }
} else {
    $_SESSION['error'] = "Méthode de requête non autorisée.";
    header('Location: connexion.php');
    exit;
}
?>
