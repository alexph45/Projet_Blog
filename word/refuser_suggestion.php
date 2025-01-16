<?php
// Inclure le fichier de connexion
require_once 'connect.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_suggestion'])) {
        // Récupérer l'ID de la suggestion
        $id_suggestion = intval($_POST['id_suggestion']);

        // Supprimer la suggestion de la table suggestion
        $stmt_delete_suggestion = $pdo->prepare("DELETE FROM suggestion WHERE id_suggestion = ?");
        $stmt_delete_suggestion->execute([$id_suggestion]);

        // Redirection vers index.php après avoir refusé
        header("Location: suggestion.php");
        exit();  // Terminer le script pour éviter d'autres sorties
    } else {
        echo "Erreur dans les données.";
    }
}
?>