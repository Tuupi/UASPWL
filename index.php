<?php
declare(strict_types=1);
use PMS\Controller\PatientController;
use PMS\Model\PatientGateway;

require __DIR__."/vendor/autoload.php";

spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});
set_error_handler("PMS\ErrorHandler::handleError");
set_exception_handler("PMS\ErrorHandler::handleException");
header("Content-type: application/json; charset=UTF-8");

$route = explode("/", $_SERVER["REQUEST_URI"]);

if($route[1] != "patients") {
    http_response_code(404);
    exit;
}

$id = $route[2] ?? null;

$database = new \PMS\Model\Database("localhost", "db_clinic", "root", "");

$gateway = new PatientGateway($database);

$controller = new PatientController($gateway);
$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);