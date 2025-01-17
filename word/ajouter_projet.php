<?php
// Inclure le fichier de connexion
require_once 'connect.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['categories']) && !empty($_POST['categories'])) {
        // Récupérer les catégories sélectionnées
        $categories = $_POST['categories'];

        // Récupérer les informations du projet
        $titre = htmlspecialchars($_POST['titre']);
        $description = htmlspecialchars($_POST['description']);
        $annee = intval($_POST['annee']);
        $image_path = '';

        // Vérifier si une image a été envoyée
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $image_name = basename($_FILES['image']['name']);
            $image_path = $upload_dir . time() . '_' . $image_name;

            // Redimensionnement de l'image
            list($width, $height, $type) = getimagesize($_FILES['image']['tmp_name']);
            $new_width = 370;
            $new_height = 300;

            if ($type == IMAGETYPE_JPEG) {
                $src = imagecreatefromjpeg($_FILES['image']['tmp_name']);
            } elseif ($type == IMAGETYPE_PNG) {
                $src = imagecreatefrompng($_FILES['image']['tmp_name']);
            } elseif ($type == IMAGETYPE_GIF) {
                $src = imagecreatefromgif($_FILES['image']['tmp_name']);
            } else {
                echo "Format d'image non supporté.";
                exit;
            }

            $dst = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

            if ($type == IMAGETYPE_JPEG) {
                imagejpeg($dst, $image_path);
            } elseif ($type == IMAGETYPE_PNG) {
                imagepng($dst, $image_path);
            } elseif ($type == IMAGETYPE_GIF) {
                imagegif($dst, $image_path);
            }

            imagedestroy($src);
            imagedestroy($dst);
        } else {
            echo "L'image est obligatoire.";
            exit;
        }

        // Ajouter le projet dans la table projets
        $stmt_insert_projet = $pdo->prepare("INSERT INTO projets (titre, description, annee, date_creation, image_url) 
                                             VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?)");
        $stmt_insert_projet->execute([$titre, $description, $annee, $image_path]);
        $projet_id = $pdo->lastInsertId();  // Récupérer l'ID du projet nouvellement inséré

        // Ajouter la relation dans la table projets_categories pour chaque catégorie sélectionnée
        $stmt_insert_relation = $pdo->prepare("INSERT INTO projets_categories (projet_id, categorie_id) VALUES (?, ?)");
        foreach ($categories as $categorie_id) {
            $stmt_insert_relation->execute([$projet_id, intval($categorie_id)]);
        }

        // Redirection vers index.php après avoir ajouté le projet
        header("Location: index.php");
        exit();  // Terminer le script pour éviter d'autres sorties
    } else {
        echo "Aucune catégorie sélectionnée.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Projet</title>
    <link rel="stylesheet" href="assets/css/add.css">
</head>
<body>
    <div class="container">
        <h1>Ajouter un Projet</h1>
        <form method="POST" action="ajouter_projet.php" enctype="multipart/form-data">
            <label for="titre">Titre du Projet :</label>
            <input type="text" id="titre" name="titre" required>

            <label for="description">Description :</label>
            <textarea id="description" name="description" required></textarea>

            <label for="annee">Année :</label>
            <input type="text" id="annee" name="annee" required>

            <label for="image">Image (obligatoire) :</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <label for="categories">Choisir des catégories :</label><br>
            <select id="categories-select" name="categories[]" multiple size="3" style="width: 100%;">
                <?php
                    // Récupérer toutes les catégories
                    $stmt_categories = $pdo->query("SELECT id, nom FROM categories");
                    $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

                    // Créer une liste déroulante
                    foreach ($categories as $categorie) {
                        echo "<option value='" . htmlspecialchars($categorie['id']) . "'>" . htmlspecialchars($categorie['nom']) . "</option>";
                    }
                ?>
            </select>

            <!-- Ajouter le script pour la gestion de la sélection multiple -->
            <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const select = document.getElementById('categories-select');
                        select.addEventListener('mousedown', function(e) {
                            e.preventDefault(); // Empêche le comportement par défaut du clic
                            const option = e.target;
                            if (option.tagName === 'OPTION') {
                                // Inverse l'état de sélection de l'option
                                option.selected = !option.selected;
                            }
                        });
                    });
            </script>


                
            <button type="submit" class="btn-submit">Ajouter</button>
            <button onclick="window.location.href='index.php'" class="btn-return">Retour vers la page d'accueil</button>
        </form>
    </div>
</body>
</html>
