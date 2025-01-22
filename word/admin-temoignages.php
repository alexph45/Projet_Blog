<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

// Connexion à la base de données
$host = 'localhost';
$dbname = 'blog';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer tous les témoignages
    $sql = "SELECT * FROM temoignages ORDER BY date_creation DESC";
    $stmt = $pdo->query($sql);
    $temoignages = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Témoignages</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
            text-decoration: none;
        }
        .btn-approve {
            background-color: #28a745;
        }
        .btn-reject {
            background-color: #dc3545;
        }
        .btn-approve:hover {
            background-color: #218838;
        }
        .btn-reject:hover {
            background-color: #c82333;
        }
        .btn-back {
            margin: 20px 0;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
        .rating {
            color: #f39c12;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des Témoignages</h1>
        <a href="index.php" class="btn-back">Retour à l'Accueil</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Entreprise</th>
                    <th>Témoignage</th>
                    <th>Note</th>
                    <th>Image</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($temoignages) > 0): ?>
                    <?php foreach ($temoignages as $temoignage): ?>
                        <tr>
                            <td><?= htmlspecialchars($temoignage['id']) ?></td>
                            <td><?= htmlspecialchars($temoignage['nom_auteur']) ?></td>
                            <td><?= htmlspecialchars($temoignage['entreprise_auteur'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($temoignage['texte']) ?></td>
                            <td class="rating">
                                <?= htmlspecialchars($temoignage['note'] ?? '-') ?> / 5
                            </td>
                            <td>
                                <?php if ($temoignage['chemin_image']): ?>
                                    <img src="<?= htmlspecialchars($temoignage['chemin_image']) ?>" alt="Image" width="50">
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($temoignage['statut']) ?></td>
                            <td><?= htmlspecialchars($temoignage['date_creation']) ?></td>
                            <td class="actions">
                                <a href="modifier-temoignage.php?action=approve&id=<?= $temoignage['id'] ?>" class="btn btn-approve">Valider</a>
                                <a href="modifier-temoignage.php?action=reject&id=<?= $temoignage['id'] ?>" class="btn btn-reject">Rejeter</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">Aucun témoignage trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
