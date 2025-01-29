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
$username = 'root'; // Remplacez par votre utilisateur MySQL
$password = ''; // Remplacez par votre mot de passe MySQL

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Message de confirmation ou d'erreur
$message = '';
$success = false;

// Récupération des articles pour la liste déroulante
try {
    $stmt = $pdo->query('SELECT id_article, titre FROM articles ORDER BY titre ASC');
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des articles : " . $e->getMessage());
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_article']) && !empty($_POST['id_article'])) {
        $id_article = $_POST['id_article']; // Récupérer l'ID de l'article sélectionné

        try {
            // Vérification si l'article existe
            $stmt = $pdo->prepare('SELECT id_article FROM articles WHERE id_article = ?');
            $stmt->execute([$id_article]);
            $article = $stmt->fetch();

            if ($article) {
                // Supprimer l'article
                $stmt = $pdo->prepare('DELETE FROM articles WHERE id_article = ?');
                $stmt->execute([$id_article]);
                $message = "L'article a été supprimé avec succès!";
                $success = true;

                // Mettre à jour la liste après suppression
                $stmt = $pdo->query('SELECT id_article, titre FROM articles ORDER BY titre ASC');
                $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $message = "Aucun article trouvé avec cet ID.";
            }
        } catch (PDOException $e) {
            $message = "Erreur lors de la suppression de l'article : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez sélectionner un article à supprimer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Article</title>
    <link rel="stylesheet" href="assets/css/add.css"> <!-- Lien vers votre fichier CSS -->
</head>
<body>
    <div class="container">
        <h1>Supprimer un Article</h1>

        <?php if ($success): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
            <a href="index.php" class="btn-return">Retour au menu</a>
        <?php else: ?>
            <!-- Formulaire pour sélectionner un article -->
            <form method="POST" action="">
                <label for="id_article">Sélectionnez un article :</label>
                <select id="id_article" name="id_article" required>
                    <option value="">-- Choisissez un article --</option>
                    <?php foreach ($articles as $article): ?>
                        <option value="<?= htmlspecialchars($article['id_article']) ?>">
                            <?= htmlspecialchars($article['titre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn-submit">Supprimer</button>
                <a href="index.php" class="btn-return">Annuler</a>
            </form>

            <?php if ($message): ?>
                <p class="error-message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>