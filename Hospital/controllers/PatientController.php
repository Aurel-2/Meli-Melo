<?php

namespace controllers;

use config\Database;
use models\Patient;

class PatientController
{
    private Database $database;
    private Patient $patient;

    public function __construct()
    {
        $this->database = new Database();
        $this->database->connect();
        $this->patient = new Patient($this->database);
    }

    public function create(): void
    {
        $this->patient->id = $_POST['id'];
        $this->patient->name = $_POST['name'];
        $this->patient->age = $_POST['age'];
        $this->patient->createPatient();
        header('Location: ../public/index.php');
    }

    public function readSingle($id)
    {
        return $this->patient->readSingle($id);
    }

    public function delete($id): void
    {
        $controller = new DiagnosticController();
        $controller->delete($id);
        $this->patient->deletePatient($id);
        header('Location: ../public/index.php');
    }

    public function api_get_patient()
    {
        header('Content-Type: application/json');
        $stmt = $this->patient->read();
        echo json_encode($stmt);
        exit;
    }

    public function read(): array
    {
        return $this->patient->read();
    }
}