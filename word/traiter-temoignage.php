<?php
// Configuration de la base de données
$host = 'localhost';
$dbname = 'blog';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $message = ''; // Pour afficher le message de retour
    $success = false; // Indique si la soumission a réussi

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $nomAuteur = htmlspecialchars($_POST['nomAuteur']);
        $entrepriseAuteur = htmlspecialchars($_POST['entrepriseAuteur']) ?? null;
        $texteTemoignage = htmlspecialchars($_POST['texteTemoignage']);

        // Gestion de l'image
        $cheminImage = null;
        if (isset($_FILES['photoAuteur']) && $_FILES['photoAuteur']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Créer le dossier si nécessaire
            }

            $cheminImage = $uploadDir . time() . '_' . basename($_FILES['photoAuteur']['name']);
            if (!move_uploaded_file($_FILES['photoAuteur']['tmp_name'], $cheminImage)) {
                $message = "Erreur lors de l'upload de l'image.";
            }
        }

        // Insérer les données dans la base de données si tout est valide
        if (!empty($nomAuteur) && !empty($texteTemoignage) && $cheminImage) {
            $sql = "INSERT INTO temoignages (nom_auteur, entreprise_auteur, texte, chemin_image, statut) 
                    VALUES (:nom_auteur, :entreprise_auteur, :texte, :chemin_image, 'en_attente')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nom_auteur' => $nomAuteur,
                ':entreprise_auteur' => $entrepriseAuteur,
                ':texte' => $texteTemoignage,
                ':chemin_image' => $cheminImage,
            ]);

            $message = "Votre témoignage a été soumis avec succès et est en attente de validation.";
            $success = true;
        } else {
            $message = "Veuillez remplir tous les champs et ajouter une image.";
        }
    }
} catch (PDOException $e) {
    $message = "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soumission de Témoignage</title>
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            font-family: Arial, sans-serif;
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #d4d4d4;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .message.success {
            border-color: #28a745;
            background-color: #d4edda;
            color: #155724;
        }
        .message.error {
            border-color: #dc3545;
            background-color: #f8d7da;
            color: #721c24;
        }
        .btn-return {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-return:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Soumission de Témoignage</h1>
        <?php if ($message): ?>
            <div class="message <?= $success ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <a href="index.php" class="btn-return">Retour à l'Accueil</a>
    </div>
</body>
</html>
