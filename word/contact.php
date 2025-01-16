<?php
// Inclusion du fichier de connexion
require 'connect.php';

// Vérification si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

    if ($email && $content) {
        // Insertion des données dans la table `contacts`
        $sql = "INSERT INTO contacts (email, message) VALUES (:email, :message)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute(['email' => $email, 'message' => $content]);

            // Redirection vers index.php après succès
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            // Redirection avec un message d'erreur en cas d'échec
            header("Location: index.php?error=" . urlencode($e->getMessage()));
            exit;
        }
    } else {
        // Redirection avec un message d'erreur si les champs sont mal remplis
        header("Location: index.php?error=" . urlencode("Veuillez remplir tous les champs correctement."));
        exit;
    }
}
?>
