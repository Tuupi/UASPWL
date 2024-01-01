<?php
namespace PMS\Controller;
use PMS\Model\PatientGateway; 
class PatientController
{
    public function __construct(private PatientGateway $gateway){

    }
    public function processRequest(string $method, ?string $id):void
    {
        if ($id){
            $this->processResourceRequest($method, $id);
        } else {
            $this->processCollectionRequest($method);
        }
    }
    private function processResourceRequest(string $method, string $id):void{
        $patient = $this->gateway->get($id);
        if(!$patient){
            http_response_code(404);
            echo json_encode(["message" => "Patient not found"]);
            return;
        }
        switch($method){
            case "GET":
            echo json_encode($patient);
            break;

            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $errors = $this->getValidationErrors($data);
                if(! empty($errors)){
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                $rows = $this->gateway->update($patient, $data);
                
                echo json_encode([
                    "message" => "Patient $id Updated",
                    "rows" => $rows
                ]);
                break;
        }
        
    }
    private function processCollectionRequest(string $method):void{
        switch($method){
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;

            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $errors = $this->getValidationErrors($data);
                if(! empty($errors)){
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                $id = $this->gateway->create($data);
                http_response_code(201);
                echo json_encode([
                    "message" => "Patient Created",
                    "id" => $id
                ]);
                break;

            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }

    private function getValidationErrors(array $data):array{
        $errors = [];

        if(!array_key_exists("first_name",$data) && !array_key_exists("last_name",$data) && !array_key_exists("gender",$data) && !array_key_exists("address",$data) && !array_key_exists("date_of_birth",$data) && !array_key_exists("phone",$data) && !array_key_exists("age",$data)){
            $errors[] = "Input all the fields";
        }
        return $errors;
    }
}