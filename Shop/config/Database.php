<?php
namespace config;
use PDO;
use PDOException;

class Database {
    private string $host = "localhost";
    private string $db_name = "db_shop";
    private string $username = "root";
    private string $password = "";

    public PDO $database;

    public function connect(): ?PDO
    {
        try {
            $this->database = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        } catch(PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
        return $this->database;
    }
}