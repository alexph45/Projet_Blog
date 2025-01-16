<?php
// Inclure le fichier de connexion
require_once 'connect.php';

// Vérifier si une suggestion est sélectionnée
if (isset($_GET['id_suggestion'])) {
    $id_suggestion = intval($_GET['id_suggestion']);

    // Récupérer les détails de la suggestion
    $stmt = $pdo->prepare("SELECT * FROM suggestion WHERE id_suggestion = ?");
    $stmt->execute([$id_suggestion]);
    $suggestion = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($suggestion) {
        // Afficher les détails de la suggestion
        echo "<h1>Suggestion #" . htmlspecialchars($suggestion['id_suggestion']) . "</h1>";
        echo "<p><strong>Titre :</strong> " . htmlspecialchars($suggestion['titre']) . "</p>";
        echo "<p><strong>Description :</strong> " . htmlspecialchars($suggestion['description']) . "</p>";
        echo "<p><strong>Année :</strong> " . htmlspecialchars($suggestion['annee']) . "</p>";
        echo "<p><strong>Date de création :</strong> " . htmlspecialchars($suggestion['date_creation']) . "</p>";
        echo "<p><strong>Image :</strong><br><img src='" . htmlspecialchars($suggestion['image_url']) . "' alt='Image' style='max-width:300px;'></p>";
        
        // Formulaire pour accepter la suggestion avec des checkboxes
        echo "<h2>Accepter cette suggestion</h2>";
        echo "<form action='accepter_suggestion.php' method='POST'>";
        echo "<label>Choisir des catégories :</label><br>";

        // Récupérer toutes les catégories
        $stmt_categories = $pdo->query("SELECT id, nom FROM categories");
        $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);
        
        // Afficher chaque catégorie comme une checkbox
        foreach ($categories as $categorie) {
            echo "<input type='checkbox' name='categories[]' value='" . htmlspecialchars($categorie['id']) . "'> " . htmlspecialchars($categorie['nom']) . "<br>";
        }

        // Ajouter un champ caché pour l'ID de la suggestion
        echo "<input type='hidden' name='id_suggestion' value='" . htmlspecialchars($suggestion['id_suggestion']) . "'>";
        echo "<button type='submit'>Accepter et ajouter aux catégories sélectionnées</button>";
        echo "</form>";

        // Bouton pour refuser la suggestion
        echo "<h2>Refuser cette suggestion</h2>";
        echo "<form action='refuser_suggestion.php' method='POST'>";
        echo "<input type='hidden' name='id_suggestion' value='" . htmlspecialchars($suggestion['id_suggestion']) . "'>";
        echo "<button type='submit'>Refuser</button>";
        echo "</form>";

    } else {
        echo "<p>Suggestion non trouvée.</p>";
        echo "<a href='suggestions.php'>Retour à la liste</a>";
    }
} else {
    // Afficher la liste des suggestions
    $stmt = $pdo->query("SELECT id_suggestion, titre FROM suggestion ORDER BY id_suggestion ASC");
    $suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h1>Liste des Suggestions</h1>";
    echo "<ul>";
    foreach ($suggestions as $suggestion) {
        echo "<li><a href='suggestion.php?id_suggestion=" . htmlspecialchars($suggestion['id_suggestion']) . "'>Suggestion #" . htmlspecialchars($suggestion['id_suggestion']) . " - " . htmlspecialchars($suggestion['titre']) . "</a></li>";
    }
    echo "</ul>";
}
?>