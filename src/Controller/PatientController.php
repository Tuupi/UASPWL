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

    }
    private function processCollectionRequest(string $method):void{
        switch($method){
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;

            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $id = $this->gateway->create($data);

                echo json_encode([
                    "message" => "Patient Created",
                    "id" => $id
                ]);
                break;
        }
    }
}