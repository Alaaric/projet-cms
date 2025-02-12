<h2>Créer une nouvelle page</h2>
<form method="POST" action="/create">
    <label for="title">Titre :</label>
    <input type="text" id="title" name="title" required>

    <label for="content">Contenu :</label>
    <textarea  id="content" name="content" required></textarea>

    <label for="slug">slug :</label>
    <input type="text" id="slug" name="slug" required>

    <button type="submit">Enregistrer</button>
</form>
