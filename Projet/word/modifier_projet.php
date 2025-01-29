<?php
// Démarrer la session
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

// Connexion à la base de données
require_once 'connect.php';
// Traitement du formulaire de modification
$message = '';
$success = false;

// Récupérer tous les projets pour les afficher dans une liste
$stmt = $pdo->query('SELECT * FROM projets');
$projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id'])) {
    // Récupérer le projet à modifier
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM projets WHERE id = ?');
    $stmt->execute([$id]);
    $projet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$projet) {
        $message = "Projet non trouvé.";
    }

    // Modifier le projet
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = htmlspecialchars($_POST['titre']);
        $description = htmlspecialchars($_POST['description']);
        $annee = (int) $_POST['annee'];
        $image_url = $projet['image_url']; // Garder l'image actuelle

        

        // Vérifier si une nouvelle image a été téléchargée
        if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $image_name = basename($_FILES['image_url']['name']);
            $image_url = $upload_dir . time() . '_' . $image_name;

            // Déplacer l'image téléchargée
            if (!move_uploaded_file($_FILES['image_url']['tmp_name'], $image_url)) {
                $message = "Erreur lors du téléchargement de l'image.";
            }
        }

        // Validation des champs
        if (!empty($titre) && !empty($description) && !empty($annee)) {
            try {
                // Mise à jour dans la base de données
                $stmt = $pdo->prepare(
                    'UPDATE projets SET titre = ?, description = ?, annee = ?, image_url = ? WHERE id = ?'
                );
                $stmt->execute([$titre, $description, $annee, $image_url, $id]);
                $message = "Le projet a été modifié avec succès!";
                $success = true;
            } catch (PDOException $e) {
                $message = "Erreur lors de la modification du projet : " . $e->getMessage();
            }
        } else {
            $message = "Veuillez remplir tous les champs.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Projet</title>
    <link rel="stylesheet" href="assets/css/mdj.css">
</head>
<body>
    <div class="container">
        <h1>Modifier un Projet</h1>

        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <!-- Liste des projets -->
        <h2>Liste des projets</h2>
        <ul>
            <?php foreach ($projets as $projet_item): ?>
                <li>
                    <a href="modifier_projet.php?id=<?= $projet_item['id'] ?>"><?= htmlspecialchars($projet_item['titre']) ?></a>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if (isset($projet)): ?>
            <!-- Formulaire de modification -->
            <h2>Modifier le projet</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <label for="titre">Titre du Projet :</label>
                <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($projet['titre']) ?>" required>

                <label for="description">Description :</label>
                <textarea id="description" name="description" required><?= htmlspecialchars($projet['description']) ?></textarea>

                <label for="annee">Année :</label>
                <input type="number" id="annee" name="annee" value="<?= htmlspecialchars($projet['annee']) ?>" required>

                <label for="image_url">Image (URL) :</label>
                <input type="file" id="image_url" name="image_url" accept="image/*">

                <button type="submit" class="btn-submit">Modifier</button>
                <a href="index.php" class="btn-return">Retour au menu</a>
            </form>
        <?php endif; ?>

        
    </div>
</body>
</html>