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

        <!-- Message de succès -->
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="success-message">
                Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.
            </div>
        <?php endif; ?>

        <form action="login-process.php" method="POST">
            <label for="email">Adresse e-mail</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="connexion-btn">Se connecter</button>
        </form>

        <p>Pas encore membre ? <a href="inscription.html">S'inscrire</a></p>
        <button onclick="window.location.href='index.html'" class="retour-btn">Retour vers la page d'accueil</button>
    </div>

</body>
</html>
