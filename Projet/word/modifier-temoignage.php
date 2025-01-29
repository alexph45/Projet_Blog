<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est administrateur
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

    if (isset($_GET['action'], $_GET['id'])) {
        $action = $_GET['action'];
        $id = (int) $_GET['id'];

        if ($action === 'approve') {
            // Valider le témoignage (changer son statut à 'valide')
            $stmt = $pdo->prepare("UPDATE temoignages SET statut = :statut WHERE id = :id");
            $stmt->execute([':statut' => 'valide', ':id' => $id]);
        } elseif ($action === 'reject') {
            // Rejeter le témoignage (supprimer de la base de données)
            $stmt = $pdo->prepare("DELETE FROM temoignages WHERE id = :id");
            $stmt->execute([':id' => $id]);
        } else {
            die("Action non valide.");
        }

        // Rediriger vers la page admin après l'action
        header('Location: admin-temoignages.php');
        exit();
    } else {
        die("Requête invalide.");
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
