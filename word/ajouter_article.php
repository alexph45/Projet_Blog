<?php
// Start session
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=blog1;charset=utf8mb4';
$username = 'root'; // Remplacez par votre utilisateur MySQL
$password = ''; // Remplacez par votre mot de passe MySQL

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
    $contenu = htmlspecialchars($_POST['contenu']);
    $date_publication = date('Y-m-d');
    $image_path = '';

    // Vérifier si une image a été envoyée
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/'; // Dossier où stocker les images téléchargées
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $image_name = basename($_FILES['image']['name']);
        $image_path = $upload_dir . time() . '_' . $image_name;

        // Déplacer l'image vers le dossier de téléchargement
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            // Validation des champs
            if (!empty($titre) && !empty($contenu) && !empty($image_path)) {
                try {
                    // Insertion dans la base de données
                    $stmt = $pdo->prepare(
                        'INSERT INTO articles (titre, contenu, date_publication, image) 
                        VALUES (?, ?, ?, ?)'
                    );
                    $stmt->execute([$titre, $contenu, $date_publication, $image_path]);
                    $message = "L'article a été ajouté avec succès!";
                    $success = true;
                } catch (PDOException $e) {
                    $message = "Erreur lors de l'ajout de l'article : " . $e->getMessage();
                }
            } else {
                $message = "Veuillez remplir tous les champs.";
            }
        } else {
            $message = "Erreur lors du téléchargement de l'image.";
        }
    } else {
        $message = "L'image est obligatoire.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Article</title>
    <link rel="stylesheet" href="style5.css"> <!-- Lien vers votre fichier CSS -->
</head>
<body>
    <div class="container">
        <h1>Ajouter un Article</h1>
        <?php if ($success): ?>
            <p class="message"><?= $message ?></p>
            <!-- Bouton de retour au menu -->
            <a href="index.php" class="btn-return">Retour au menu</a>
        <?php else: ?>
            <form method="POST" action="" enctype="multipart/form-data">
                <label for="titre">Titre de l'Article :</label>
                <input type="text" id="titre" name="titre" required>

                <label for="contenu">Contenu :</label>
                <textarea id="contenu" name="contenu" required></textarea>

                <label for="image">Image (obligatoire) :</label>
                <input type="file" id="image" name="image" accept="image/*" required>

                <button type="submit" class="btn-submit">Ajouter</button>
                <a href="index.php" class="btn-return">Retour au menu</a>
            </form>
        <?php endif; ?>
    </div>

    <script>
        // Vérifier si l'image est présente dans le formulaire
        const form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            const imageInput = document.getElementById('image');
            if (!imageInput.files.length) {
                alert("L'image est obligatoire.");
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
