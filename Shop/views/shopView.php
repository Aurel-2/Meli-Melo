<?php
// shopView.php
use controllers\ProductController;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/ProductController.php';

session_start();
$productController = new ProductController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productController->create();
    header('Location: ../public/index.php?action=index');
    exit;
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
        <form id="productForm" method="POST" enctype="multipart/form-data">
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
    <div class="product-grid" id="product-grid"></div>
    <div class="btn-grid">
        <button type="button" onclick="applyDiscount()">Afficher la réduction de 10%</button>
        <button type="button" onclick="resetDisplay()">Réinitialisation</button>
    </div>
</div>
<script src="../public/shop.js"></script>
</body>
</html>