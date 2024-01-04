<?php
declare(strict_types=1);

use PMS\Controller\DoctorController;
use PMS\Controller\PatientController;
use PMS\Model\DoctorGateway;
use PMS\Model\PatientGateway;

require __DIR__."/vendor/autoload.php";

// spl_autoload_register(function ($class) {
//     require __DIR__ . "/src/$class.php";
// });
set_error_handler("PMS\ErrorHandler::handleError");
set_exception_handler("PMS\ErrorHandler::handleException");
header("Content-type: application/json; charset=UTF-8");

$route = explode("/", $_SERVER["REQUEST_URI"]);
$database = new \PMS\Model\Database("localhost", "db_clinic", "root", "");

if($route[1] == "patients") {
    $id = $route[2] ?? null;
    $gateway = new PatientGateway($database);
    $controller = new PatientController($gateway);
    $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
    
} else if($route[1] == "doctors"){
    $id = $route[2] ?? null;
    $gateway = new DoctorGateway($database);
    $controller = new DoctorController($gateway);
    $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
}else{
    http_response_code(404);
    echo json_encode([
        "Error" => "Route isn't available"
    ]);
    exit;
}

