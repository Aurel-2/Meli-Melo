<?php
namespace models;
use config\Database;
use PDO;

class Product {
    private Database $database;
    public string $name;
    public float $price;
    public string $category;
    public int $stock;
    public string $image;

    public function __construct($database) {
        $this->database = $database;
    }

    // CREATE
    public function create()
    {
        $sql = "INSERT INTO products (name, price, category, stock, image) VALUES (:name, :price, :category, :stock, :image)";
        $stmt = $this->database->connect()->prepare($sql);

        // Plus lisible que de mettre tout dans le execute
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':image', $this->image);
        return $stmt->execute();
    }

    // READ (tous les produits)
    public function read(): array
    {
        $sql = "SELECT * FROM products";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ (un produit)
    public function readSingle(int $id)
    {
        $sql = "SELECT * FROM products WHERE id_product = :id";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // UPDATE
    public function update($data): void
    {
        $sql = "UPDATE products SET name = :name, price = :price, category = :category, stock = :stock WHERE id_product = :id";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute($data);
    }

    // DELETE
    public function delete($id): bool
    {
        $sql = "DELETE FROM products WHERE id_product = :id";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
