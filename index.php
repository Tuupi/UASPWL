<?php
declare(strict_types=1);

use PMS\Controller\DoctorController;
use PMS\Controller\LoginController;
use PMS\Controller\PatientController;
use PMS\Model\DoctorGateway;
use PMS\Model\LoginGateway;
use PMS\Model\PatientGateway;

require __DIR__."/vendor/autoload.php";

// spl_autoload_register(function ($class) {
//     require __DIR__ . "/src/$class.php";
// });
set_error_handler("PMS\ErrorHandler::handleError");
set_exception_handler("PMS\ErrorHandler::handleException");


$route = explode("/", $_SERVER["REQUEST_URI"]);
$database = new \PMS\Model\Database("localhost", "db_clinic", "root", "");
if($route[1] == "api"){
    if($route[2] == "patients") {
        header("Content-type: application/json; charset=UTF-8");
        $id = $route[3] ?? null;
        $gateway = new PatientGateway($database);
        $controller = new PatientController($gateway);
        $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
        
    } else if($route[2] == "doctors"){
        header("Content-type: application/json; charset=UTF-8");
        $id = $route[3] ?? null;
        $gateway = new DoctorGateway($database);
        $controller = new DoctorController($gateway);
        $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
    } else if($route[2] == "login"){
        if($route[3] ?? null){
            header("Content-type: application/json; charset=UTF-8");
            http_response_code(403);
            echo json_encode([
            "Error" => "Forbidden Method"
            ]);
            exit;
        } else {
            $gateway = new LoginGateway($database);
            $controller = new LoginController($gateway);
            $controller->processRequest($_SERVER["REQUEST_METHOD"]);
        }
    }
    else {
        header("Content-type: application/json; charset=UTF-8");
        http_response_code(404);
        echo json_encode([
        "Error" => "Route isn't available"
        ]);
        exit;
    }
} else
 if($route[1] == "register"){
    header("Content-type: text/html; charset=UTF-8");
    require 'src/View/Regist.html';
}
else if ($route[1] == ""){
    header("Content-type: text/html; charset=UTF-8");
    require 'src/view/index.html';
}else if($route[1] == "login"){
    header("Content-type: text/html; charset=UTF-8");
    require 'src/view/login.html';
}else{
    header("Content-type: application/json; charset=UTF-8");
    http_response_code(404);
    echo json_encode([
        "Error" => "Route isn't available"
    ]);
    exit;
}