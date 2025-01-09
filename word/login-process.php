<?php
// Inclure le fichier de connexion à la base de données
require_once 'db.php';

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validation des champs
    if (empty($email) || empty($password)) {
        echo "Veuillez remplir tous les champs.";
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
            echo "Connexion réussie. Bienvenue, " . htmlspecialchars($user['prenom']) . "!";
            // Démarrez une session ou redirigez l'utilisateur vers une autre page
            session_start();
            $_SESSION['user_id'] = $user['id'];
            header('Location: index.html');
            exit;
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet e-mail.";
    }
} else {
    echo "Méthode de requête non autorisée.";
}
?>
