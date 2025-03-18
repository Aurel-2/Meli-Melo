<?php

use controllers\ProductController;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../models/Product.php';

$controller = new ProductController();
$products = $controller->read() ?? [];

$action = $_GET['action'] ?? 'index';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;


$allowedActions = ['index', 'api-get', 'create', 'update', 'delete'];

// Si l'action n'est pas valide, revenir à index
if (!in_array($action, $allowedActions)) {
    $action = 'index';
}

switch ($action) {
    case 'index':
        $products = $controller->read();
        include __DIR__ . '/../views/shopView.php';
        break;
    case 'api-get':
        $controller->api_get_products();
        break;
    case 'create':
        $controller->create();
        break;
    case 'update':
        $controller->update($id);
        break;
    case 'delete':
        $controller->delete($id);
        break;
}