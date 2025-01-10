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
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $date_creation = date('Y-m-d');
    $url = htmlspecialchars($_POST['url']);
    $annee = htmlspecialchars($_POST['annee']);
    $id_categorie = (int)$_POST['id_categorie'];
    $image_path = '';

    // Vérifier si une image a été envoyée
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $image_name = basename($_FILES['image']['name']);
        $image_path = $upload_dir . time() . '_' . $image_name;

        // Redimensionnement de l'image
        list($width, $height, $type) = getimagesize($_FILES['image']['tmp_name']);

        // Définir les dimensions de l'image
        $new_width = 370;
        $new_height = 300;

        // Créer une nouvelle image en fonction du type de l'image
        if ($type == IMAGETYPE_JPEG) {
            $src = imagecreatefromjpeg($_FILES['image']['tmp_name']);
        } elseif ($type == IMAGETYPE_PNG) {
            $src = imagecreatefrompng($_FILES['image']['tmp_name']);
        } elseif ($type == IMAGETYPE_GIF) {
            $src = imagecreatefromgif($_FILES['image']['tmp_name']);
        } else {
            $message = "Format d'image non supporté.";
            exit;
        }

        // Créer une nouvelle image vide avec les nouvelles dimensions
        $dst = imagecreatetruecolor($new_width, $new_height);

        // Redimensionner l'image
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        // Sauvegarder l'image redimensionnée
        if ($type == IMAGETYPE_JPEG) {
            imagejpeg($dst, $image_path);
        } elseif ($type == IMAGETYPE_PNG) {
            imagepng($dst, $image_path);
        } elseif ($type == IMAGETYPE_GIF) {
            imagegif($dst, $image_path);
        }

        // Libérer la mémoire
        imagedestroy($src);
        imagedestroy($dst);

    } else {
        $message = "L'image est obligatoire.";
    }

    // Validation des champs
    if (!empty($titre) && !empty($description) && !empty($url) && !empty($annee) && !empty($id_categorie) && !empty($image_path)) {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO projets (titre, description, image, date_creation, url, année, id_categorie) 
                VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([$titre, $description, $image_path, $date_creation, $url, $annee, $id_categorie]);
            $message = "Le projet a été ajouté avec succès!";
            $success = true;
        } catch (PDOException $e) {
            $message = "Erreur lors de l'ajout du projet : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Projet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #ffffff;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            padding: 20px;
            border-radius: 10px;
            animation: fadeIn 0.5s ease-in-out;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        textarea,
        input[type="file"],
        select {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #ff7e67;
            outline: none;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #ff758c 0%, #ff7eb3 100%);
            color: #fff;
            border: none;
            padding: 10px 15px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background 0.3s;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #ff6071 0%, #ff6584 100%);
        }

        .btn-return {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-return:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 20px;
            font-size: 16px;
            text-align: center;
            color: green;
            font-weight: bold;
        }

        .error {
            color: red;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajouter un Projet</h1>
        <?php if ($success): ?>
            <p class="message"><?= $message ?></p>
            <a href="index.php" class="btn-return">Retour au menu</a>
        <?php else: ?>
            <form method="POST" action="" enctype="multipart/form-data" id="projectForm">
                <label for="titre">Titre du Projet :</label>
                <input type="text" id="titre" name="titre" required>

                <label for="description">Description :</label>
                <textarea id="description" name="description" required></textarea>

                <label for="url">URL :</label>
                <input type="text" id="url" name="url" required>

                <label for="annee">Année :</label>
                <input type="text" id="annee" name="annee" required>

                <label for="id_categorie">Catégorie :</label>
                <select id="id_categorie" name="id_categorie" required>
                    <option value="" disabled selected>Choisissez une catégorie</option>
                    <?php
                    // Récupération des catégories depuis la base
                    $categories = $pdo->query('SELECT id_categorie, nom FROM categories')->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($categories as $categorie) {
                        echo "<option value='{$categorie['id_categorie']}'>{$categorie['nom']}</option>";
                    }
                    ?>
                </select>

                <label for="image">Image (obligatoire) :</label>
                <input type="file" id="image" name="image" accept="image/*" required>

                <button type="submit" class="btn-submit">Ajouter</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        const form = document.getElementById('projectForm');
        
        form.addEventListener('submit', function(event) {
            const imageInput = document.getElementById('image');
            if (!imageInput.files.length) {
                alert("L'image est obligatoire.");
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
