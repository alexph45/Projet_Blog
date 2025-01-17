<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header('Location: connexion.php');
    exit();
}

// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=blog;charset=utf8mb4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomAuteur = htmlspecialchars($_POST['nomAuteur']);
    $entrepriseAuteur = htmlspecialchars($_POST['entrepriseAuteur']) ?? null;
    $texteTemoignage = htmlspecialchars($_POST['texteTemoignage']);
    $note = intval($_POST['note']);
    $date_creation = date('Y-m-d');
    $cheminImage = null;

    // Gestion de l'image
    if (isset($_FILES['photoAuteur']) && $_FILES['photoAuteur']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Créer le dossier si nécessaire
        }
        $cheminImage = $uploadDir . time() . '_' . basename($_FILES['photoAuteur']['name']);
        move_uploaded_file($_FILES['photoAuteur']['tmp_name'], $cheminImage);
    }

    // Insertion des données dans la base de données
    try {
        $stmt = $pdo->prepare("INSERT INTO temoignages (nom_auteur, entreprise_auteur, texte, note, chemin_image, date_creation, statut) 
                               VALUES (?, ?, ?, ?, ?, ?, 'en_attente')");
        $stmt->execute([$nomAuteur, $entrepriseAuteur, $texteTemoignage, $note, $cheminImage, $date_creation]);
        $message = "Témoignage soumis avec succès et en attente de validation.";
    } catch (PDOException $e) {
        $message = "Erreur lors de la soumission : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/temoignage.css">
  <title>Soumettre un Témoignage</title>
  <style>
    .btn-return {
      display: inline-block;
      margin-bottom: 20px;
      padding: 10px 15px;
      font-size: 16px;
      color: #fff;
      background-color: #007bff;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }
    .btn-return:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

<a href="index.php" class="btn-return">Retour au menu</a>

<h1>Soumettre un Témoignage</h1>
<p>Merci de partager votre expérience. Votre témoignage sera examiné avant d'être publié.</p>

<?php if (isset($message)): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form action="" method="POST" enctype="multipart/form-data">
  <label for="nomAuteur">Nom :</label>
  <input type="text" id="nomAuteur" name="nomAuteur" placeholder="Votre nom" required>

  <label for="entrepriseAuteur">Entreprise (optionnel) :</label>
  <input type="text" id="entrepriseAuteur" name="entrepriseAuteur" placeholder="Nom de votre entreprise">

  <label for="texteTemoignage">Témoignage :</label>
  <textarea id="texteTemoignage" name="texteTemoignage" rows="5" placeholder="Écrivez votre témoignage ici..." required></textarea>

  <label for="note">Note (de 1 à 5) :</label>
  <select id="note" name="note" required>
    <option value="">Choisir une note</option>
    <option value="1">1 étoile</option>
    <option value="2">2 étoiles</option>
    <option value="3">3 étoiles</option>
    <option value="4">4 étoiles</option>
    <option value="5">5 étoiles</option>
  </select>

  <label for="photoAuteur">Photo de profil (optionnel) :</label>
  <input type="file" id="photoAuteur" name="photoAuteur" accept="image/*">

  <button type="submit">Envoyer le témoignage</button>
</form>

</body>
</html>
