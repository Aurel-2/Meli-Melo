<?php

use controllers\DiagnosticController;
use controllers\LoginController;
use controllers\PatientController;

session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/PatientController.php';
require_once __DIR__ . '/../controllers/DiagnosticController.php';
require_once __DIR__ . '/../controllers/LoginController.php';
require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../models/Diagnostic.php';

$patientController = new PatientController();
$diagnosticController = new DiagnosticController();
$loginController = new LoginController();
$patients = $patientController->read() ?? [];

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $loginController->logout();
}
$action = $_GET['action'] ?? 'index';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$allowedActions = ['index', 'api-get-patient', 'api-get-diagnostic', 'add-diagnostic', 'create', 'update', 'delete', 'login','logout'];

if (!in_array($action, $allowedActions)) {
    $action = 'index';
}

switch ($action) {
    case 'index':
        $patients = $patientController->read();
        include __DIR__ . '/../views/patientsView.php';
        break;
    case 'api-get-patient':
        $patientController->api_get_patient();
        break;
    case 'api-get-diagnostic':
        $diagnosticController->api_get_diagnostic($id);
        break;
    case 'create':
        $patientController->create();
        $diagnosticController->create();
        break;
    case 'delete':
        $patientController->delete($id);
        break;
    case 'add-diagnostic':
        $diagnosticController->create();
        break;
    case 'login':
        $loginController->login($_POST['username'], $_POST['password']);
        include __DIR__ . '/../views/patientsView.php';
        break;
}