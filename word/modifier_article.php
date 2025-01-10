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

// Traitement du formulaire de modification
$message = '';
$success = false;

if (isset($_GET['id_article'])) {
    // Récupérer l'article à modifier
    $id_article = (int) $_GET['id_article'];
    $stmt = $pdo->prepare('SELECT * FROM articles WHERE id_article = ?');
    $stmt->execute([$id_article]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        $message = "Article non trouvé.";
    }

    // Modifier l'article
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = htmlspecialchars($_POST['titre']);
        $contenu = htmlspecialchars($_POST['contenu']);
        $image_path = $article['image']; // Garder l'image actuelle

        // Vérifier si une nouvelle image a été téléchargée
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $image_name = basename($_FILES['image']['name']);
            $image_path = $upload_dir . time() . '_' . $image_name;

            // Déplacer l'image téléchargée
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                $message = "Erreur lors du téléchargement de l'image.";
            }
        }

        // Validation des champs
        if (!empty($titre) && !empty($contenu)) {
            try {
                // Mise à jour dans la base de données
                $stmt = $pdo->prepare(
                    'UPDATE articles SET titre = ?, contenu = ?, image = ? WHERE id_article = ?'
                );
                $stmt->execute([$titre, $contenu, $image_path, $id_article]);
                $message = "L'article a été modifié avec succès!";
                $success = true;
            } catch (PDOException $e) {
                $message = "Erreur lors de la modification de l'article : " . $e->getMessage();
            }
        } else {
            $message = "Veuillez remplir tous les champs.";
        }
    }
}

// Affichage des articles existants
$stmt = $pdo->query('SELECT * FROM articles');
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Article</title>
    <link rel="stylesheet" href="style6.css">
</head>
<body>
    <div class="container">
        <h1>Modifier un Article</h1>

        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <!-- Liste des anciens articles -->
        <h2>Liste des articles</h2>
        <ul>
            <?php foreach ($articles as $article): ?>
                <li>
                    <a href="modifier_article.php?id_article=<?= $article['id_article'] ?>"><?= $article['titre'] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if (isset($article)): ?>
            <!-- Formulaire de modification -->
            <form method="POST" action="" enctype="multipart/form-data">
                <label for="titre">Titre de l'Article :</label>
                <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required>

                <label for="contenu">Contenu :</label>
                <textarea id="contenu" name="contenu" required><?= htmlspecialchars($article['contenu']) ?></textarea>

                <label for="image">Image :</label>
                <input type="file" id="image" name="image" accept="image/*">

                <button type="submit" class="btn-submit">Modifier</button>
            </form>
        <?php endif; ?>

        <a href="index.php" class="btn-return">Retour au menu</a>
    </div>
</body>
</html>
