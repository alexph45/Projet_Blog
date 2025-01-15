<?php
session_start();
// Détruire toutes les données de session
session_unset();
session_destroy();

// Rediriger l'utilisateur vers la page d'accueil ou de connexion
header('Location: index.php');
exit;
?>
