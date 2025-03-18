<?php
use config\Database;
use controllers\ProductController;

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/controllers/ProductController.php';
require_once __DIR__ . '/models/Product.php';

try {
    $productController = new ProductController();
    $message = "";
    $products = $productController->read() ?? [];
    $productToEdit = null;

    $action = $_GET['action'] ?? '';
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($action === 'update' && $id > 0) {
            $productController->update($id);
            $message = "Produit mis à jour avec succès";
        } elseif ($action === 'create' || empty($action)) {
            if ($productController->create()) {
                $message = "Produit ajouté avec succès";
            } else {
                $message = "Erreur lors de l'ajout";
            }
        }
        $products = $productController->read();
    }

    if ($action === 'edit' && $id > 0) {
        $productToEdit = $productController->readSingle($id);
    } elseif ($action === 'delete' && $id > 0) {
        $productController->delete($id);
        $message = "Produit supprimé avec succès";
        $products = $productController->read();
    }

    require_once __DIR__ . '/views/shopView.php';
} catch (Exception $e) {
    $message = "Erreur: " . $e->getMessage();
    require_once __DIR__ . '/views/shopView.php';
}

