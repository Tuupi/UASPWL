<?php
namespace PMS\Controller;

use PMS\Model\LoginGateway;
use PMS\Controller\Session;


class LoginController{
    
    public function __construct(private LoginGateway $gateway)
    {
        
    }
    public function processRequest(string $method):void
    {
        $this->processResourceRequest($method);
    }
    public function processResourceRequest(string $method):void{
        switch($method){
            case "POST":
                http_response_code(200);
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $errors = $this->getValidationErrors($data);
                if(!empty($errors)){
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                $login = $this->gateway->get($data);
                if(empty($login)){
                    http_response_code(404);
                    echo json_encode(["errors" => "Username & password tidak valid"]);
                    break;
                }
                
                $session = new SessionController();
                $session = $session->sessionCreate($login);
                echo json_encode(["Message" => "Login Confirmed", "Token" => $session]);
                http_response_code(202);
                break;
            default : 
                http_response_code(405);
                header("Allow: POST");
        }
    }
    public function getValidationErrors(array $data) : array{
        $errors = [];
        if(!array_key_exists("username", $data)){
            $errors[] = "Input username fields";
        }else if(!array_key_exists("password", $data)){
            $errors[] = "Input password fields";
        }
        return $errors;
    }
}