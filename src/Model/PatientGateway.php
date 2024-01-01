<?php
namespace PMS\Model;
use PDO;
    class PatientGateway{
        private PDO $conn;
        public function __construct(Database $database){
            $this->conn = $database->getConnection();
        }
        public function getAll():array{
            $sql = "SELECT * FROM patient_details";

            $stmt = $this->conn->query($sql);

            $data = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $data[] = $row;
            }

            return $data;
        }

        public function create(array $data):string{
            $sql = "INSERT INTO patient_details (first_name, last_name, gender, address, date_of_birth, phone, age)
                    VALUES (:first_name, :last_name, :gender, :address, :date_of_birth, :phone, :age)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":first_name", $data["first_name"], PDO::PARAM_STR);
            $stmt->bindValue(":last_name", $data["last_name"], PDO::PARAM_STR);
            $stmt->bindValue(":gender", $data["gender"], PDO::PARAM_STR);
            $stmt->bindValue(":address", $data["address"], PDO::PARAM_STR);
            $stmt->bindValue(":date_of_birth", $data["date_of_birth"],PDO::PARAM_STR);
            $stmt->bindValue(":phone", $data["phone"], PDO::PARAM_STR);
            $stmt->bindValue(":age", $data["age"], PDO::PARAM_STR);

            $stmt->execute();

            return $this->conn->lastInsertId();
        }
    }