<?php
namespace PMS\Controller;
use Firebase\JWT\JWT;
use Exception;
use stdClass;
class SessionController{
    private $secret_key = "togetherwearestronger";
    public function sessionCreate(array $payload): string{
        $jwt = JWT::encode($payload, $this->secret_key, 'HS256');
        setcookie("PWL-SESSION", $jwt);
        return $jwt;
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