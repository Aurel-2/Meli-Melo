<?php

namespace models;

use Cassandra\Date;
use config\Database;
use PDO;

class Diagnostic
{
    public int $id_patient;
    public Date $date;
    public string $description;
    private Database $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function create()
    {
        $sql = "INSERT INTO diagnostics(id_patient, date, description) VALUES (:id_patient, :date, :description)";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':id_patient', $this->id_patient);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':description', $this->description);
        $stmt->execute();
    }

    public function read()
    {
        $sql = "SELECT * FROM diagnostics WHERE id_patient = :id_patient";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':id_patient', $this->id_patient);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fixed readSingle method
    public function readSingle($id)
    {
        $sql = "SELECT * FROM diagnostics WHERE id_patient = :id_patient";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':id_patient', $id, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM diagnostics WHERE id_patient = :id";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}