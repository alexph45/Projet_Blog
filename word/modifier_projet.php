<?php

require_once 'connect.php';

session_start(); // Démarre la session

// Requête pour récupérer les projets et leurs catégories
$sql = "
    SELECT 
        p.id AS projet_id, 
        p.titre, 
        p.description, 
        p.annee, 
        p.image_url, 
        GROUP_CONCAT(c.nom SEPARATOR ' / ') AS categories
    FROM projets p
    LEFT JOIN projets_categories pc ON p.id = pc.projet_id
    LEFT JOIN categories c ON pc.categorie_id = c.id
    GROUP BY p.id
";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Projets</title>
</head>
<body>
    <h1>Liste des Projets</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Année</th>
                <th>Image</th>
                <th>Catégories</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['projet_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['titre']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['annee']); ?></td>
                        <td>
                            <?php if (!empty($row['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Image du projet" style="width: 100px;">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['categories']); ?></td>
                        <td>
                            <a href="edit_project.php?id=<?php echo $row['projet_id']; ?>">Modifier</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Aucun projet trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>

<?php
// Fermer la connexion
$conn->close();
?>
