<?php
$title = "Connexion";
ob_start();
?>

<h2>Connexion</h2>
<form method="POST" action="/login">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Se connecter</button>
</form>

<?php
$content = ob_get_clean();
require __DIR__ . "/layout.php";
?>
