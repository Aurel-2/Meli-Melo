<?php
use controllers\ProductController;
use controllers\LoginController;
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/LoginController.php';

$productController = new ProductController();
$loginController = new LoginController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['form_type']) && $_POST['form_type'] === 'login') {
        $loginController->login($_POST['username'], $_POST['password']);
        header('Location: ../public/index.php');
        exit;
    } else {
        $productController->create();
        header('Location: ../public/index.php?action=index');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue de Produits</title>
    <link href="../public/shop.css" rel="stylesheet">
</head>
<body onload="init()">
<h1>Gestion des Produits</h1>
<div class="container">
    <div id="entry">
        <?php if (!$loginController->isConnected()): ?>
            <!-- Formulaire de connexion -->
            <form method="POST" class="login-form">
                <input type="hidden" name="form_type" value="login">
                <h2>Connexion</h2>
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" placeholder="Nom d'utilisateur" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Mot de passe" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="login-btn">Se connecter</button>
                </div>
            </form>
        <?php else: ?>

            <form id="productForm" method="POST" enctype="multipart/form-data">
                <div class="logout-container">
                    <p>Connecté en tant que: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                    <a href="?action=logout">Déconnexion</a>
                </div>
                <div class="form-group">
                    <label for="name">Nom du produit : </label>
                    <input type="text" id="name" name="name" value="" required>
                </div>
                <div class="form-group">
                    <label for="category">Catégorie :</label>
                    <input type="text" id="category" name="category" value="" required>
                </div>
                <div class="form-group">
                    <label for="image">Sélectionner une image : </label>
                    <input type="file" id="image" name="Image" accept=".jpg, .jpeg, .png">
                </div>
                <div class="form-group">
                    <label for="price">Prix : </label>
                    <input type="number" id="price" name="price" step="0.01" min="0" required>
                </div>
                <div class="form-group checkbox-inline">
                    <label for="stock">Stock : </label>
                    <input type="checkbox" id="stock" name="stock">
                </div>
                <div class="form-group">
                    <button type="submit">Ajouter le produit</button>
                </div>
            </form>
            <div>
                <img id="img-src" src="../images/default.jpg" alt="Image produit">
            </div>
    </div>
</div>
<div class="container">
    <div class="form-group" id="total">
        <button type="button" onclick="totalPrice()">Calculer le prix total</button>
    </div>
    <div id="search">
        <label for="search-products">Rechercher un produit</label>
        <input type="text" id="search-products" onkeyup="searchProducts()" placeholder="Rechercher par nom...">
    </div>
    <div id="filter">
        <label for="cat-filter">Filtrer par catégorie</label>
        <select id="cat-filter" onchange="filterProducts()">
            <option value="all">Toutes les catégories</option>
        </select>
    </div>
    <div class="btn-grid">
        <button type="button" onclick="applyDiscount()">Afficher la réduction de 10%</button>
        <button type="button" onclick="resetDisplay()">Réinitialisation</button>
    </div>
</div>
<div class="second-container">
    <div class="product-grid" id="product-grid"></div>
</div>
<?php endif; ?>

<script src="../public/shop.js"></script>
</body>
</html>