<?php
// Inclure le fichier de connexion
require_once 'connect.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['categories']) && !empty($_POST['categories']) && isset($_POST['id_suggestion'])) {
        // Récupérer les catégories sélectionnées et l'ID de la suggestion
        $categories = $_POST['categories'];
        $id_suggestion = intval($_POST['id_suggestion']);

        // Récupérer les détails de la suggestion
        $stmt_suggestion = $pdo->prepare("SELECT * FROM suggestion WHERE id_suggestion = ?");
        $stmt_suggestion->execute([$id_suggestion]);
        $suggestion = $stmt_suggestion->fetch(PDO::FETCH_ASSOC);

        if ($suggestion) {
            // Ajouter le projet dans la table projets
            $stmt_insert_projet = $pdo->prepare("INSERT INTO projets (titre, description, annee, date_creation, image_url) 
                                                 VALUES (?, ?, ?, ?, ?)");
            $stmt_insert_projet->execute([
                $suggestion['titre'],
                $suggestion['description'],
                $suggestion['annee'],
                $suggestion['date_creation'],
                $suggestion['image_url']
            ]);
            $projet_id = $pdo->lastInsertId();  // Récupérer l'ID du projet nouvellement inséré

            // Ajouter la relation dans la table projets_categories pour chaque catégorie sélectionnée
            $stmt_insert_relation = $pdo->prepare("INSERT INTO projets_categories (projet_id, categorie_id) VALUES (?, ?)");
            foreach ($categories as $categorie_id) {
                $stmt_insert_relation->execute([$projet_id, intval($categorie_id)]);
            }

            // Redirection vers index.php après avoir accepté
            header("Location: index.php");
            exit();  // Terminer le script pour éviter d'autres sorties
        } else {
            echo "Suggestion non trouvée.";
        }
    } else {
        echo "Aucune catégorie sélectionnée ou erreur dans les données.";
    }
}
?> f