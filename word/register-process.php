<?php
// Inclure le fichier de connexion à la base de données
require_once 'db.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Validation des données
    if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($confirmPassword)) {
        die("Veuillez remplir tous les champs.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Adresse e-mail invalide.");
    }

    if ($password !== $confirmPassword) {
        die("Les mots de passe ne correspondent pas.");
    }

    if (strlen($password) < 8) {
        die("Le mot de passe doit contenir au moins 8 caractères.");
    }

    // Hacher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Préparer une requête pour insérer l'utilisateur dans la base de données
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES (:nom, :prenom, :email, :mot_de_passe)");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $hashedPassword);

        // Exécuter la requête
        $stmt->execute();

        // Rediriger l'utilisateur vers une page de connexion avec un message de succès
        header("Location: connexion.php?success=1");
        exit;
    } catch (PDOException $e) {
        // Gérer les erreurs, notamment les e-mails dupliqués
        if ($e->getCode() === '23000') { // Code d'erreur SQL pour une violation de clé unique
            die("Cet e-mail est déjà utilisé. Veuillez en choisir un autre.");
        } else {
            die("Erreur lors de l'inscription : " . $e->getMessage());
        }
    }
} else {
    die("Méthode de requête non autorisée.");
}
?>
