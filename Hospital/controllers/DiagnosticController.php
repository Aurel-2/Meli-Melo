<?php

namespace controllers;

use config\Database;
use models\Diagnostic;

class DiagnosticController
{
    private Database $database;
    private Diagnostic $diagnostic;

    public function __construct()
    {
        $this->database = new Database();
        $this->database->connect();
        $this->diagnostic = new Diagnostic($this->database);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->diagnostic->id_patient = $_POST['id'];
            $this->diagnostic->date = $_POST['date'];
            $this->diagnostic->description = $_POST['diag'];
            $this->diagnostic->create();
            header("Location: ../public/index.php?action=index");
        }
    }

    public function delete($id)
    {
        $this->diagnostic->delete($id);
        header("Location: ../public/index.php?action=index");
    }

    public function api_get_diagnostic($id)
    {
        header('Content-Type: application/json');
        $diagnostics = $this->diagnostic->readSingle($id);
        echo json_encode($diagnostics);
        exit;
    }
}