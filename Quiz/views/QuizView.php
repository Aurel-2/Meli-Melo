<?php
session_start();
global $database;

use controllers\ProductController;
use controllers\LoginController;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/LoginController.php';

$productController = new ProductController();
$loginController = new LoginController();
$message = "";

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $loginController->logout();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['form_type'])) {
        switch ($_POST['form_type']) {
            case 'login':
                $username = $_POST['username'];
                $password = $_POST['password'];
                if ($loginController->login($username, $password)) {
                    $message = "<h4 style='color: green'>Connexion réussie</h4>";
                } else {
                    $message = "<h4 style='color: red'>Identifiants incorrects</h4>";
                }
                break;

            case 'add_product':
                $productController->create();
                $message = "<h4 style='color: green'>Produit ajouté avec succès</h4>";
                header('Location: ../public/index.php?action=index');
                exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits</title>
    <link href="../public/shop.css" rel="stylesheet">
</head>
<body onload="init()">
<?php if (!$loginController->isConnected()): ?>
    <!-- Formulaire de connexion -->
    <form method="POST">
        <input type="hidden" name="form_type" value="login">
        <h1>Connexion</h1>
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
        <button type="submit">Se connecter</button>
        <?php echo $message; ?>
    </form>
<?php else: ?>
    <!-- Formulaire d'ajout de produit -->
    <form method="POST">
        <div class="logout-container">
            <h1>Ajouter un produit</h1>
            <a href="?action=logout">Déconnexion</a>
        </div>
        <input type="hidden" name="form_type" value="add_product">
        <input type="text" name="name" placeholder="Nom du produit" required>
        <input type="text" name="category" placeholder="Catégorie" required>
        <input type="file" name="Image" accept=".jpg, .jpeg, .png">
        <input type="number" name="price" step="0.01" min="0" placeholder="Prix (€)" required><br>
        <input type="checkbox" name="stock" id="stock">
        <label for="stock">En stock</label><br>
        <button type="submit">Ajouter le produit</button>
        <?php echo $message; ?>
    </form>
<?php endif; ?>

<!-- Affichage des produits (toujours visible) -->
<form method="POST">
    <h1>Catalogue des produits</h1>
    <div id="search">
        <input type="text" id="search-products" onkeyup="searchProducts()" placeholder="Rechercher par nom...">
    </div>
    <div id="filter">
        <select id="cat-filter" onchange="filterProducts()">
            <option value="all">Toutes les catégories</option>
        </select>
    </div>
    <div class="product-grid" id="product-grid"></div>
    <?php if ($loginController->isConnected()): ?>
        <button type="button" onclick="totalPrice()">Calculer le prix total</button>
        <button type="button" onclick="applyDiscount()">Réduction de 10%</button>
        <button type="button" onclick="resetDisplay()">Réinitialiser</button>
    <?php endif; ?>
</form>

<script src="../public/shop.js"></script>
</body>
</html>