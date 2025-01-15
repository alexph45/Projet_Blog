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

// Récupérer tous les projets pour la liste déroulante
try {
    $stmt = $pdo->query('SELECT id, titre FROM projets ORDER BY titre ASC');
    $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des projets : " . $e->getMessage());
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id']; // Récupérer l'ID du projet sélectionné

        try {
            // Supprimer le projet sélectionné
            $stmt = $pdo->prepare('DELETE FROM projets WHERE id = ?');
            $stmt->execute([$id]);
            $message = "Le projet a été supprimé avec succès!";
            $success = true;

            // Mettre à jour la liste après suppression
            $stmt = $pdo->query('SELECT id, titre FROM projets ORDER BY titre ASC');
            $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $message = "Erreur lors de la suppression du projet : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez sélectionner un projet à supprimer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Projet</title>
    <link rel="stylesheet" href="style4.css"> <!-- Lien vers votre fichier CSS -->
</head>
<body>
    <div class="container">
        <h1>Supprimer un Projet</h1>

        <?php if ($success): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
            <a href="index.php" class="btn-return">Retour au menu</a>
        <?php else: ?>
            <!-- Formulaire pour sélectionner un projet -->
            <form method="POST" action="">
                <label for="id">Sélectionnez un projet :</label>
                <select id="id" name="id" required>
                    <option value="">-- Choisissez un projet --</option>
                    <?php foreach ($projets as $projet): ?>
                        <option value="<?= htmlspecialchars($projet['id']) ?>">
                            <?= htmlspecialchars($projet['titre']) ?>
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
