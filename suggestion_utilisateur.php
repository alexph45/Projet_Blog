<?php
// Start session
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=blog;charset=utf8mb4';
$username = 'root'; 
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement du formulaire
$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $date_creation = date('Y-m-d');
    $annee = htmlspecialchars($_POST['annee']);
    $image_path = '';

    // Validation de l'année côté serveur
    $current_year = date('Y');
    if (!filter_var($annee, FILTER_VALIDATE_INT) || $annee < 1900 || $annee > $current_year) {
        $message = "L'année doit être un nombre compris entre 1900 et $current_year.";
    } else {
        // Vérification si une image a été envoyée
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

            // Validation des autres champs
            if (!empty($titre) && !empty($description) && !empty($image_path)) {
                try {
                    // Insertion dans la base de données
                    $stmt = $pdo->prepare(
                        'INSERT INTO suggestion (titre, description, image_url, date_creation, annee) 
                        VALUES (?, ?, ?, ?, ?)'
                    );
                    $stmt->execute([$titre, $description, $image_path, $date_creation, $annee]);
                    $message = "Le projet a été ajouté avec succès!";
                    $success = true;
                } catch (PDOException $e) {
                    $message = "Erreur lors de l'ajout du projet : " . $e->getMessage();
                }
            } else {
                $message = "Veuillez remplir tous les champs.";
            }
        } else {
            $message = "L'image est obligatoire.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Projet</title>
    <link rel="stylesheet" href="style4.css">
</head>
<body>
    <div class="container">
        <h1>Ajouter une Suggestion</h1>
        <?php if ($success): ?>
            <p class="message"><?= $message ?></p>
            <a href="index.php" class="btn-return">Retour au menu</a>
        <?php else: ?>
            <form method="POST" action="" enctype="multipart/form-data">
                <label for="titre">Titre du Projet :</label>
                <input type="text" id="titre" name="titre" required>

                <label for="description">Description :</label>
                <textarea id="description" name="description" required></textarea>

                <label for="annee">Année :</label>
                <input type="text" id="annee" name="annee" required>

                <label for="image">Image (obligatoire) :</label>
                <input type="file" id="image" name="image" accept="image/*" required>

                <button type="submit" class="btn-submit">Ajouter</button>
            </form>
            <?php if ($message): ?>
                <p class="error-message"><?= $message ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
