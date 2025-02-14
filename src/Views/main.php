
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">
    <title> Projet CMS</title>
</head>
<body>
    <header>
        <h1>Projet CMS</h1>
        <nav class="navbar">
    <a href="/">Accueil</a>
    
    <?php if (isset($_SESSION['user'])): ?>
        <a href="/create">Créer une page</a>
        <a href="/logout">Se déconnecter</a>
        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
            <a href="/admin/dashboard">Dashboard</a>
        <?php endif; ?>
    <?php else: ?>
        <a href="/login">Se connecter</a>
    <?php endif; ?>
</nav>

    </header>

    <main class="container">
        <?= $content ?>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> Projet CMS</p>
    </footer>
</body>
</html>
