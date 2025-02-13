<h2>Pages existantes</h2>
<ul>
    <?php foreach ($pages as $page): ?>
        <li>
            <strong><?= htmlspecialchars($page->getTitle()) ?></strong>
            <a href=<?= "/page/" . $page->getSlug() ?>>Voir la page</a>
        </li>
    <?php endforeach; ?>
</ul>

