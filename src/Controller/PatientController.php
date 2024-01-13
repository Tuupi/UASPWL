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

            default:
                http_response_code(405);
                header("Allow: GET, POST");
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
                if(!empty($errors)){
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
        if(!array_key_exists("full_name", $data)){
            $errors[] = "Input Name fields";
        }else if(!array_key_exists("gender", $data)){
            $errors[] = "Input Gender fields";
        }else if(!array_key_exists("address", $data)){
            $errors[] = "Input Address fields";
        }else if(!array_key_exists("date_of_birth", $data)){
            $errors[] = "Input Date of Birth fields";
        }else if(!array_key_exists("phone", $data)){
            $errors[] = "Input Phone number fields";
        }else if(!array_key_exists("email", $data)){
            $errors[] = "Input Email fields";
        }else if(!array_key_exists("occupation", $data)){
            $errors[] = "Input Occupation fields";
        }else if(!array_key_exists("parent_name", $data)){
            $errors[] = "Input Parent Name fields";
        }else if(!array_key_exists("parent_phone", $data)){
            $errors[] = "Input Parent Phone Number fields";
        }else if(!array_key_exists("guardian_name", $data)){
            $errors[] = "Input Guardian Name fields";
        }

        return $errors;
    }
}