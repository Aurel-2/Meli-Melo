<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Produit</title>
    <link href="../shop.css" rel="stylesheet">
</head>
<body>
<h1>Modifier un Produit</h1>
<div class="container">
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($this->product->name); ?>" required>
        </div>
        <div class="form-group">
            <label for="category">Catégorie :</label>
            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($this->product->category); ?>" required>
        </div>
        <div class="form-group">
            <label for="image">Image actuelle :</label>
            <img id="img-src" src="../../public/<?php echo $this->product->image; ?>" alt="Image produit" style="max-width: 100px;">
            <input type="file" id="image" name="Image" accept=".jpg, .jpeg, .png">
        </div>
        <div class="form-group">
            <label for="price">Prix :</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo $this->product->price; ?>" required>
        </div>
        <div class="form-group checkbox-inline">
            <label for="stock">Stock :</label>
            <input type="checkbox" id="stock" name="stock" <?php echo $this->product->stock ? 'checked' : ''; ?>>
        </div>
        <div class="form-group">
            <button type="submit">Mettre à jour</button>
            <a href="../../index.php">Annuler</a>
        </div>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
    </form>
</div>
</body>
</html>