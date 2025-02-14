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
        if ($placeholder === 'createdAt') {
            $structure = str_replace('{{createdAt}}', $page->getCreatedAt(), $structure);
        } elseif ($placeholder === 'updatedAt') {
            $structure = str_replace('{{updatedAt}}', $page->getUpdatedAt(), $structure);
        } else {
            $structure = str_replace('{{' . $placeholder . '}}', $value, $structure);
        }
    }
    echo $structure;
    ?>
</body>
</html>