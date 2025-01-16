<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Suggestion</title>
    <link rel="stylesheet" href="assets/css/sug.css">
</head>
<body>
    <div class="container">
        <?php
        // Inclure la connexion à la base de données
        require_once 'connect.php';

        // Vérifier si une suggestion spécifique est demandée
        if (isset($_GET['id_suggestion'])) {
            $id_suggestion = intval($_GET['id_suggestion']);

            // Récupérer les détails de la suggestion
            $stmt = $pdo->prepare("SELECT * FROM suggestion WHERE id_suggestion = ?");
            $stmt->execute([$id_suggestion]);
            $suggestion = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($suggestion) {
                ?>
                <section class="suggestion-details">
                    <h1>Suggestion #<?= htmlspecialchars($suggestion['id_suggestion']) ?></h1>
                    <p><strong>Titre :</strong> <?= htmlspecialchars($suggestion['titre']) ?></p>
                    <p><strong>Description :</strong> <?= htmlspecialchars($suggestion['description']) ?></p>
                    <p><strong>Année :</strong> <?= htmlspecialchars($suggestion['annee']) ?></p>
                    <p><strong>Date de création :</strong> <?= htmlspecialchars($suggestion['date_creation']) ?></p>
                    <p><strong>Image :</strong></p>
                    <img src="<?= htmlspecialchars($suggestion['image_url']) ?>" alt="Image" class="suggestion-image">
                </section>

                <section class="actions">
                    <h2>Accepter cette suggestion</h2>
                    <form action="accepter_suggestion.php" method="POST">
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
                        <input type="hidden" name="id_suggestion" value="<?= htmlspecialchars($suggestion['id_suggestion']) ?>">
                        <button type="submit" class="btn btn-accept">Accepter</button>
                    </form>

                    <h2>Refuser cette suggestion</h2>
                    <form action="refuser_suggestion.php" method="POST">
                        <input type="hidden" name="id_suggestion" value="<?= htmlspecialchars($suggestion['id_suggestion']) ?>">
                        <button type="submit" class="btn btn-refuse">Refuser</button>
                    </form>
                </section>
                <?php
            } else {
                echo "<p>Suggestion non trouvée.</p>";
                echo "<a href='suggestions.php' class='btn'>Retour à la liste</a>";
            }
        } else {
            // Afficher la liste des suggestions
            $stmt = $pdo->query("SELECT id_suggestion, titre FROM suggestion ORDER BY id_suggestion ASC");
            $suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <section class="suggestion-list">
                <h1>Liste des Suggestions</h1>
                <?php if (empty($suggestions)) : ?>
                    <p>Aucune suggestion n'a été trouvée dans la base de données.</p>
                <?php else : ?>
                    <ul>
                        <?php foreach ($suggestions as $suggestion) : ?>
                            <li>
                                <a href="suggestion.php?id_suggestion=<?= htmlspecialchars($suggestion['id_suggestion']) ?>">
                                    Suggestion #<?= htmlspecialchars($suggestion['id_suggestion']) ?> - <?= htmlspecialchars($suggestion['titre']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>
            <?php
        }
        ?>
    </div>
</body>
</html>
