<h2>Pages existantes</h2>
<ul>
    <?php foreach ($pages as $page): ?>
        <li>
            <hr>
            <strong><?= $page->getName() ?></strong>
            <p>crée le : <?= $page->getCreatedAt() ?></p>
            <p>dernière modification le : <?= $page->getUpdatedAt()?></p>
            <a href=<?= "/page/" . $page->getSlug() ?> class="btn yes">Voir la page</a>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $page->getUserId()): ?>
                <a href="/page/edit/<?= $page->getSlug() ?>" class="btn yes">Modifier la page</a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>

