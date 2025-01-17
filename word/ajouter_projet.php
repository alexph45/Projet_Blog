<?php
// Inclure le fichier de connexion
require_once 'connect.php';

$success = true;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si des catégories sont sélectionnées
    if (!isset($_POST['categories']) || empty($_POST['categories'])) {
        $message = "Aucune catégorie sélectionnée.";
        $success = false;
    }

    // Récupérer les informations du projet
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $annee = intval($_POST['annee']);
    $image_path = '';

    // Vérification et validation de l'année
    if (empty($annee)) {
        $message = "L'année est obligatoire.";
        $success = false;
    } elseif (!preg_match('/^\d{4}$/', $annee) || $annee < 1950 || $annee > date('Y')) {
        $message = "Veuillez entrer une année valide (format : AAAA, entre 1950 et l'année actuelle).";
        $success = false;
    }

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
            $message = "Format d'image non supporté.";
            $success = false;
        }

        if ($success) {
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
        }
    } else {
        $message = "L'image est obligatoire.";
        $success = false;
    }

    // Si toutes les validations sont passées, insérer dans la base de données
    if ($success) {
        // Ajouter le projet dans la table projets
        $stmt_insert_projet = $pdo->prepare("INSERT INTO projets (titre, description, annee, date_creation, image_url) 
                                             VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?)");
        $stmt_insert_projet->execute([$titre, $description, $annee, $image_path]);
        $projet_id = $pdo->lastInsertId();  // Récupérer l'ID du projet nouvellement inséré

        // Ajouter la relation dans la table projets_categories pour chaque catégorie sélectionnée
        $stmt_insert_relation = $pdo->prepare("INSERT INTO projets_categories (projet_id, categorie_id) VALUES (?, ?)");
        foreach ($_POST['categories'] as $categorie_id) {
            $stmt_insert_relation->execute([$projet_id, intval($categorie_id)]);
        }

        // Redirection vers index.php après avoir ajouté le projet
        header("Location: index.php");
        exit();
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

        <!-- Affichage du message d'erreur -->
        <?php if (!empty($message)) : ?>
            <div class="error-message" style="color: red;">
                <strong>Erreur:</strong> <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="ajouter_projet.php" enctype="multipart/form-data">
            <label for="titre">Titre du Projet :</label>
            <input type="text" id="titre" name="titre" value="<?php echo isset($_POST['titre']) ? htmlspecialchars($_POST['titre']) : ''; ?>" required>

            <label for="description">Description :</label>
            <textarea id="description" name="description" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>

            <label for="annee">Année :</label>
            <input type="text" id="annee" name="annee" value="<?php echo isset($_POST['annee']) ? htmlspecialchars($_POST['annee']) : ''; ?>" required>

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
                        // Vérifier si la catégorie est déjà sélectionnée
                        $selected = (isset($_POST['categories']) && in_array($categorie['id'], $_POST['categories'])) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($categorie['id']) . "' $selected>" . htmlspecialchars($categorie['nom']) . "</option>";
                    }
                ?>
            </select>

            <button type="submit" class="btn-submit">Ajouter</button>
            <button onclick="window.location.href='index.php'" class="btn-return">Retour vers la page d'accueil</button>
        </form>
    </div>
    <script src="assets/js/validation.js"></script>
</body>
</html>
