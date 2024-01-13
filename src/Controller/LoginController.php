<?php
namespace PMS\Controller;

use Exception;
use PMS\Model\LoginGateway;
use Firebase\JWT\JWT as JWT;
use Session;
use stdClass;

class LoginController{
    private $secret_key = "togetherwearestronger";
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
    public function sessionCreate(array $payload):void{
        $jwt = JWT::encode($payload, $this->secret_key, 'HS256');
        setcookie("PWL-SESSION", $jwt);
    }
    public function getCurrentSession(): stdClass{
        if($_COOKIE["PWL-SESSION"]){
            try{
            $jwt = $_COOKIE["PWL-SESSION"];
            $payload = JWT::decode($jwt, $this->secret_key);
            return $payload;
            } catch(Exception $e){
                http_response_code(404);
                echo json_encode(["Errors" => "You aren't login yet"]);
            }
        }
        else {
            http_response_code(404);
            echo json_encode(["Errors" => "You aren't login yet"]);
        }
    }
}