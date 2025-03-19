<?php

namespace models;

use config\Database;
use PDO;

class Patient
{
    public int $id;
    public string $name;
    public int $age;
    private Database $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function createPatient(): void
    {
        $sql = "INSERT INTO patients (id_patient, name, age) VALUES (:id, :name, :age)";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':age', $this->age);
        $stmt->execute();
    }

    public function readSingle($id)
    {
        $sql = "SELECT * FROM patients WHERE id_patient = :id";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function read()
    {
        $sql = "SELECT * FROM patients";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updatePatient($data): void
    {
        $sql = "UPDATE patients SET name = :name, age = :age WHERE id_patient = :id";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':age', $this->age);
        $stmt->execute();
    }

    public function deletePatient($id): void
    {
        $sql = "DELETE FROM patients WHERE id_patient = :id";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

}