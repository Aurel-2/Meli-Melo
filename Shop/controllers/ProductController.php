<?php
namespace controllers;

use config\Database;
use models\Product;

require_once __DIR__ . '/../models/Product.php';

class ProductController {
    private Product $product;
    private Database $database;

    public function __construct() {
        $this->database = new Database();
        $this->database->connect();
        $this->product = new Product($this->database);
    }

    public function create()
    {
        $this->product->name = $_POST['name'] ?? '';
        $this->product->price = $_POST['price'] ?? 0;
        $this->product->category = $_POST['category'] ?? '';
        $this->product->stock = isset($_POST['stock']) ? 1 : 0;

        $imagePath = "../images/default.jpg";
        if (isset($_FILES["Image"]) && $_FILES["Image"]["error"] == 0) {
            $target_dir = "../images/";
            $target_file = $target_dir . basename($_FILES["Image"]["name"]);
            if (move_uploaded_file($_FILES["Image"]["tmp_name"], $target_file)) {
                $imagePath = $target_file;
            }
        }
        $this->product->image = $imagePath;
        $this->product->create();
        header("Location: ../public/index.php?action=index");
    }

    public function read(): array
    {
        return $this->product->read();
    }

    public function readSingle(int $id) {
        return $this->product->readSingle($id);
    }

    public function update($id): void {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $imagePath = "../images/default.jpg";
            if (isset($_FILES["Image"]) && $_FILES["Image"]["error"] == 0) {
                $target_dir = "../images/";
                $target_file = $target_dir . basename($_FILES["Image"]["name"]);
                if (move_uploaded_file($_FILES["Image"]["tmp_name"], $target_file)) {
                    $imagePath = $target_file;
                }
            }
            $product = [
                "id" => $id,
                "name" => $_POST['name'],
                "price" => $_POST['price'],
                "category" => $_POST['category'],
                "stock" => isset($_POST['stock']) ? 1 : 0,
                "image" => $imagePath
            ];

            $this->product->update($product);
            header("Location: ../public/index.php?action=index");
            exit();
        }
        $data = $this->product->readSingle($id);
        require(__DIR__ . "/../views/updateView.php");
    }

    public function delete($id): void
    {
        $this->product->delete($id);
        header("Location: ../public/index.php?action=index");
    }

    public function api_get_products(): void
    {
        header('Content-Type: application/json');
        $stmt = $this->product->read();
        echo json_encode($stmt);
        exit;
    }
}
