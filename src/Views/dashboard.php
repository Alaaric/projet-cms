<h2>🛠️ Gestion des pages</h2>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Slug</th>
            <th>Auteur</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pages as $page): ?>
            <tr>
                <td><?= $page->getId() ?></td>
                <td><?= htmlspecialchars($page->getTitle()) ?></td>
                <td><?= htmlspecialchars($page->getSlug()) ?></td>
                <td>Utilisateur #<?= $page->getUserId() ?></td>
                <td>
                    <a href="/page/<?= $page->getSlug() ?>">👁️ Voir</a>
                    <a href="/page/edit/<?= $page->getSlug() ?>">✏️ Modifier</a>
                    <a href="/admin/delete-page/<?= $page->getSlug() ?>" onclick="return confirm('Supprimer cette page ?')">❌ Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>👥 Gestion des utilisateurs</h2>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Nom d'utilisateur</th>
            <th>Rôle</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user->getId() ?></td>
                <td><?= htmlspecialchars($user->getEmail()) ?></td>
                <td><?= htmlspecialchars($user->getUsername()) ?></td>
                <td><?= htmlspecialchars($user->getRole()) ?></td>
                <td>
                    <?php if ($user->getId() !== $_SESSION['user']['id']): ?>
                        <a href="/admin/user/delete/<?= $user->getId() ?>" onclick="return confirm('Supprimer cet utilisateur ?')">❌ Supprimer</a>
                    <?php else: ?>
                        🔒 (Vous)
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>