<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page->getTitle()) ?></title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <?php
    $structure = $template->getStructure();
    foreach ($page->getContent() as $placeholder => $value) {
        $structure = str_replace('{{' . $placeholder . '}}', htmlspecialchars($value), $structure);
    }
    echo $structure;
    ?>
</body>
</html>