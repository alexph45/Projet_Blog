<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>

    <div class="connexion-container">
        <h1>Connexion</h1>

        <!-- Affichage des messages d'erreur -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']); // Effacer le message après affichage
                ?>
            </div>
        <?php endif; ?>

        <!-- Affichage des messages de succès -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-message">
                <?php
                    echo htmlspecialchars($_SESSION['success']);
                    unset($_SESSION['success']); // Effacer le message après affichage
                ?>
            </div>
        <?php endif; ?>

        <!-- Si l'utilisateur est connecté, afficher le bouton de déconnexion -->
        <?php if (isset($_SESSION['user'])): ?>
            <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['user']); ?> !</p>
            <form action="logout.php" method="POST">
                <button type="submit" class="connexion-btn">Se déconnecter</button>
            </form>
        <?php else: ?>
            <!-- Sinon afficher le formulaire de connexion -->
            <form action="login-process.php" method="POST">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="connexion-btn">Se connecter</button>
            </form>
            <p>Pas encore membre ? <a href="inscription.html">S'inscrire</a></p>
        <?php endif; ?>

        <button onclick="window.location.href='index.html'" class="retour-btn">Retour vers la page d'accueil</button>
    </div>

</body>
</html>
