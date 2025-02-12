<?php
$title = "Créer une Page";
ob_start();
?>

<h2>Créer une nouvelle page</h2>
<form method="POST" action="/create">
    <label for="title">Titre :</label>
    <input type="text" id="title" name="title" required>

    <label for="content">Contenu :</label>
    <textarea id="content" name="content" required></textarea>

    <button type="submit">Enregistrer</button>
</form>

<?php
$content = ob_get_clean();
require __DIR__ . "/layout.php";
?>
