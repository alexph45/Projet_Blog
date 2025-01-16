<?php

// Start session
session_start();

require_once 'connect.php';

// Traitement du formulaire
$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $date_creation = date('Y-m-d');
    $annee = htmlspecialchars($_POST['annee']);
    $image_path = '';

    // Vérification et validation de l'année
    if (!empty($annee)) {
        if (!preg_match('/^\d{4}$/', $annee) || $annee < 1900 || $annee > date('Y')) {
            $message = "Veuillez entrer une année valide (format : AAAA, entre 1900 et l'année actuelle).";
            $success = false;
        }
    } else {
        $message = "L'année est obligatoire.";
        $success = false;
    }

    // Vérifier si une image a été envoyée
    if (empty($message) && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
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

    } else if (empty($message)) {
        $message = "L'image est obligatoire.";
    }

    if (empty($message) && !empty($titre) && !empty($description) && !empty($image_path)) {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO projets (titre, description, image_url, date_creation, annee) 
                VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$titre, $description, $image_path, $date_creation, $annee]);
            $message = "Le projet a été ajouté avec succès!";
            $success = true;
        } catch (PDOException $e) {
            $message = "Erreur lors de l'ajout du projet : " . $e->getMessage();
        }
    } else if (empty($message)) {
        $message = "Veuillez remplir tous les champs.";
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
        <?php if ($success): ?>
            <p class="message"><?= $message ?></p>
            <a href="index.php" class="btn-return">Retour au menu</a>
        <?php else: ?>
            <form method="POST" action="" enctype="multipart/form-data" id="projectForm">
                <label for="titre">Titre du Projet :</label>
                <input type="text" id="titre" name="titre" required>

                <label for="description">Description :</label>
                <textarea id="description" name="description" required></textarea>

                <label for="annee">Année :</label>
                <input type="text" id="annee" name="annee" required>

                <label for="image">Image (obligatoire) :</label>
                <input type="file" id="image" name="image" accept="image/*" required>

                <label>Choisir des catégories :</label><br>
                        <?php
                        $stmt_categories = $pdo->query("SELECT id, nom FROM categories");
                        $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($categories as $categorie) {
                            ?>
                            <input type="checkbox" name="categories[]" value="<?= htmlspecialchars($categorie['id']) ?>"> <?= htmlspecialchars($categorie['nom']) ?><br>
                            <?php
                        }
                        ?>

                <button type="submit" class="btn-submit">Ajouter</button>
                <button onclick="window.location.href='index.php'" class="btn-return">Retour vers la page d'accueil</button>
            </form>
            <?php if (!empty($message)): ?>
                <p class="error-message"><?= $message ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <script src="assets/js/validation.js"></script>
</body>
</html>
