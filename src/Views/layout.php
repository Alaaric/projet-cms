
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "Projet CMS" ?></title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <header>
        <h1>Projet CMS</h1>
        <nav>
            <a href="/">Accueil</a>
            <a href="/create">Créer une page</a>
        </nav>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> Projet CMS</p>
    </footer>
</body>
</html>
