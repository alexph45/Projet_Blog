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

// Traitement du formulaire
$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_article = $_POST['id_article'];  // Récupère l'ID de l'article à supprimer

    // Validation de l'ID
    if (!empty($id_article) && filter_var($id_article, FILTER_VALIDATE_INT) !== false) {
        try {
            // Vérification si l'article existe
            $stmt = $pdo->prepare('SELECT id_article FROM articles WHERE id_article = ?');
            $stmt->execute([$id_article]);
            $article = $stmt->fetch();

            if ($article) {
                // Supprimer l'article par ID
                $stmt = $pdo->prepare('DELETE FROM articles WHERE id_article = ?');
                $stmt->execute([$id_article]);
                $message = "L'article a été supprimé avec succès!";
                $success = true;
            } else {
                $message = "Aucun article trouvé avec cet ID.";
            }
        } catch (PDOException $e) {
            $message = "Erreur lors de la suppression de l'article : " . $e->getMessage();
        }
    } else {
        $message = "L'ID de l'article doit être un nombre valide.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Article</title>
    <link rel="stylesheet" href="style4.css"> <!-- Lien vers votre fichier CSS -->
</head>
<body>
    <div class="container">
        <h1>Supprimer un Article</h1>
        <?php if ($success): ?>
            <p class="message"><?= $message ?></p>
            <!-- Bouton de retour au menu -->
            <a href="index.php" class="btn-return">Retour au menu</a>
        <?php else: ?>
            <form method="POST" action="">
                <label for="id_article">ID de l'Article à supprimer :</label>
                <input type="number" id="id_article" name="id_article" required>

                <button type="submit" class="btn-submit">Supprimer</button>
                <a href="index.php" class="btn-return">Retour au menu</a>
            </form>
            <?php if ($message): ?>
                <p class="error-message"><?= $message ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
