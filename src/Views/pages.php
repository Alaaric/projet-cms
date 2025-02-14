<h2>Pages existantes</h2>
<ul>
    <?php foreach ($pages as $page): ?>
        <li>
            <strong><?= $page->getTitle() ?></strong>
            <a href=<?= "/page/" . $page->getSlug() ?> class="btn yes">Voir la page</a>
        </li>
    <?php endforeach; ?>
</ul>

