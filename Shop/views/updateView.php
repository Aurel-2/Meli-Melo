<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Produit</title>
    <link href="../public/shop.css" rel="stylesheet">
</head>
<body>
<h1>Modifier le Produit</h1>
<?php if (isset($data)) : ?>
    <div class="container">
        <div id="entry">
            <form method="POST" action="?action=update&id=<?php echo $data['id_product']; ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Nom du produit :</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($data['name']); ?>"
                           required>
                </div>
                <div class="form-group">
                    <label for="category">Catégorie :</label>
                    <input type="text" id="category" name="category"
                           value="<?php echo htmlspecialchars($data['category']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="image">Modifier l'image :</label>
                    <input type="file" id="image" name="Image" accept=".jpg, .jpeg, .png">
                </div>
                <div class="form-group">
                    <label for="price">Prix :</label>
                    <input type="number" id="price" name="price" step="0.01" min="0"
                           value="<?php echo $data['price']; ?>" required>
                </div>
                <div class="form-group checkbox-inline">
                    <label for="stock">Stock :</label>
                    <input type="checkbox" id="stock" name="stock" <?php echo $data['stock'] ? 'checked' : ''; ?>>
                </div>

                <div class="form-group">
                    <button type="submit">Mettre à jour</button>
                </div>
            </form>
            <div>
                <img id="img-src" src="<?php echo $data['image'] ?: '../images/default.jpg'; ?>" alt="Image actuelle">
            </div>
        </div>
    </div>
<?php else : ?>
    <p>Aucune donnée disponible.</p>
<?php endif; ?>
</body>
<script>
    let imgInput = document.getElementById("image");
    let imgElement = document.getElementById("img-src");
    if (imgInput && imgElement) {
        imgInput.addEventListener('change', (event) => {
            let file = event.target.files[0];
            if (file) {
                imgElement.src = URL.createObjectURL(file);
            }
        });
    }
</script>
</html>
