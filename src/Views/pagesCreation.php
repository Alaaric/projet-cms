<?php
$tinymceApiKey = $_ENV["TINYMCE_API_KEY"];
?>

<script src="https://cdn.tiny.cloud/1/<?= $tinymceApiKey ?>/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '.editor',
        menubar: false
    });
</script>

<form method="POST">
    <label>nom de votre page :</label>
    <input type="text" name="title" value="<?= isset($page) ? htmlspecialchars($page->getTitle()) : '' ?>" required>

    <label>Slug :</label>
    <input type="text" name="slug" value="<?= isset($page) ? htmlspecialchars($page->getSlug()) : '' ?>" required>

    <div id="dynamic-fields">
        <?php foreach ($placeholders as $placeholder): ?>
            <label><?= htmlspecialchars($placeholder) ?> :</label>
            <textarea class="editor" name="<?= htmlspecialchars($placeholder) ?>"><?= htmlspecialchars(isset($page) ? $page->getContent()[$placeholder] ?? '' : '') ?></textarea>
        <?php endforeach; ?>
    </div>

    <button type="submit">Enregistrer</button>
</form>