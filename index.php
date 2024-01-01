<?php

declare(strict_types=1);

require __DIR__."/vendor/autoload.php";

spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});
set_exception_handler("PMS\ErrorHandler::handleException()");
header("Content-type: application/json; charset=UTF-8");

$route = explode("/", $_SERVER["REQUEST_URI"]);

if($route[1] != "patients") {
    http_response_code(404);
    exit;
}

$id = $route[2] ?? null;

$database = new \PMS\Model\Database("localhost", "db_clinic", "root", "");

$database->getConnection();

$controller = new \PMS\Controller\PatientController();
$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);