<?php
$title = "Liste des Pages";
ob_start();
?>

<h2>Pages existantes</h2>
<ul>
    <?php foreach ($pages as $page): ?>
        <li>
            <strong><?= htmlspecialchars($page->getTitle()) ?></strong>
            <p><?= nl2br(htmlspecialchars($page->getContent())) ?></p>
        </li>
    <?php endforeach; ?>
</ul>

<?php
$content = ob_get_clean();
require __DIR__ . "/layout.php";
?>
