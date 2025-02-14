<?php
$tinymceApiKey = $_ENV["TINYMCE_API_KEY"];
?>

<script src="https://cdn.tiny.cloud/1/<?= $tinymceApiKey ?>/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '.editor-structure',
        plugins: 'code',
        toolbar: 'undo redo | code',
        menubar: false
    });
</script>

<h2> Gestion des pages</h2>

<table >
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
                <td><?= $page->getTitle() ?></td>
                <td><?= $page->getSlug() ?></td>
                <td>Utilisateur #<?= $page->getUserId() ?></td>
                <td>
                    <a href="/page/<?= $page->getSlug() ?>">Voir</a>
                    <a href="/page/edit/<?= $page->getSlug() ?>"> Modifier</a>
                    <form method="POST" action="/admin/delete-page" style="display:inline;">
                        <input type="hidden" name="slug" value="<?= $page->getSlug() ?>">
                        <button type="submit" onclick="return confirm('Supprimer cette page ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2> Gestion des utilisateurs</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Nom</th>
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
                        <form method="POST" action="/admin/delete-user" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $user->getId() ?>">
                            <button type="submit" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</button>
                        </form>
                    <?php else: ?>
                        Vous ne pouvez pas vous supprimer
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2> Édition de la structure du template</h2>

<form method="POST" action="/admin/edit-template-structure">
    <label>Structure complète du template :</label>
    <textarea class="editor-structure" name="template_structure"><?= $template->getStructure() ?></textarea>

    <button type="submit">Enregistrer</button>
</form>