<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page->getName()) ?></title>
    <link rel="stylesheet" href="/css/page-styles.css">
</head>
<body>
    <?php
    $structure = $template->getStructure();
    foreach ($page->getContent() as $placeholder => $value) {
        $replacement = match ($placeholder) {
            'createdAt' => $page->getCreatedAt(),
            'updatedAt' => $page->getUpdatedAt(),
            'currentYear' => date("Y"),
            default => $value,
        };
        $structure = str_replace('{{' . $placeholder . '}}', $replacement, $structure);
    }
    echo $structure;
    ?>
</body>
</html>