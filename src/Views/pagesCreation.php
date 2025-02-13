<?php
$tinymceApiKey = $_ENV["TINYMCE_API_KEY"];
$auth = new App\Controllers\AuthController();
?>

<script src="https://cdn.tiny.cloud/1/<?= $tinymceApiKey ?>/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '.editor',
        menubar: false
    });
</script>

<form method="POST">
    <label>Titre :</label>
    <input type="text" name="title" value="<?= isset($page) ? htmlspecialchars($page->getTitle()) : '' ?>" required>

    <label>Slug :</label>
    <input type="text" name="slug" value="<?= isset($page) ? htmlspecialchars($page->getSlug()) : '' ?>" required>

    <h3>Contenu (Éditable par tous)</h3>
    <label>Header :</label>
    <textarea name="header" class="editor"><?= isset($page) ? htmlspecialchars($page->getHeader()->getContent()) : '' ?></textarea>

    <label>Contenu principal :</label>
    <textarea name="body" class="editor"><?= isset($page) ? htmlspecialchars($page->getBody()->getContent()) : '' ?></textarea>

    <label>Footer :</label>
    <textarea name="footer" class="editor"><?= isset($page) ? htmlspecialchars($page->getFooter()->getContent()) : '' ?></textarea>

    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <h3>Structure (Éditable uniquement par les admins)</h3>
        <label>Structure du Header :</label>
        <textarea name="header_structure"><?= isset($page) ? htmlspecialchars($page->getHeader()->getStructure() ?: '') : '' ?></textarea>

        <label>Structure du Contenu :</label>
        <textarea name="body_structure"><?= isset($page) ? htmlspecialchars($page->getBody()->getStructure() ?: '') : '' ?></textarea>

        <label>Structure du Footer :</label>
        <textarea name="footer_structure"><?= isset($page) ? htmlspecialchars($page->getFooter()->getStructure() ?: '') : '' ?></textarea>
    <?php endif; ?>

    <button type="submit">Enregistrer</button>
</form>
