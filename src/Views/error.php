<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <main class="container error-page">
        <h1>Une erreur est survenue</h1>
        <div class="error-content">
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <a href="/" class="btn yes">Retour à l'accueil</a>
        </div>
    </main>
</body>
</html>