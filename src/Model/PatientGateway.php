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
            $sql = "INSERT INTO patient_details (full_name, gender, address, date_of_birth, phone, email, occupation, parent_name, parent_phone, guardian_name)
                    VALUES (:full_name, :gender, :address, :date_of_birth, :phone, :email, :occupation, :parent_name, :parent_phone, :guardian_name)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":full_name", $data["full_name"], PDO::PARAM_STR);
            $stmt->bindValue(":gender", $data["gender"], PDO::PARAM_STR);
            $stmt->bindValue(":address", $data["address"], PDO::PARAM_STR);
            $stmt->bindValue(":date_of_birth", $data["date_of_birth"],PDO::PARAM_STR);
            $stmt->bindValue(":phone", $data["phone"], PDO::PARAM_STR);
            $stmt->bindValue(":occupation", $data["occupation"], PDO::PARAM_STR);
            $stmt->bindValue(":email", $data["email"], PDO::PARAM_STR);
            $stmt->bindValue(":parent_name", $data["parent_name"], PDO::PARAM_STR);
            $stmt->bindValue(":parent_phone", $data["parent_phone"], PDO::PARAM_STR);
            $stmt->bindValue(":guardian_name", $data["guardian_name"], PDO::PARAM_STR);

            $stmt->execute();

            return $this->conn->lastInsertId();
        }

        public function get(string $id):array | false{
            $sql = "SELECT * FROM patient_details WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            return $data;
        }
        public function update(array $current, array $new):int{
            $sql = "UPDATE patient_details
                    SET full_name = :full_name, gender = :gender, address = :address, date_of_birth = :date_of_birth, phone = :phone, age = :age, email = :email, occupation = :occupation, parent_name = :parent_name, parent_phone = :parent_phone, guardian_name = :guardian_name WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":full_name", $new["full_name"] ?? $current["full_name"], PDO::PARAM_STR);
            $stmt->bindValue(":gender", $new["gender"] ?? $current["gender"] , PDO::PARAM_STR);
            $stmt->bindValue(":address", $new["address"] ?? $current["address"], PDO::PARAM_STR);
            $stmt->bindValue(":date_of_birth", $new["date_of_birth"] ?? $current["date_of_birth"],PDO::PARAM_STR);
            $stmt->bindValue(":phone", $new["phone"] ?? $current["phone"], PDO::PARAM_STR);
            $stmt->bindValue(":age", $new["age"] ?? $current["age"], PDO::PARAM_STR);
            $stmt->bindValue(":occupation", $new["occupation"] ?? $current["occupation"], PDO::PARAM_STR);
            $stmt->bindValue(":email", $new["email"] ?? $current["email"], PDO::PARAM_STR);
            $stmt->bindValue(":parent_name", $new["parent_name"] ?? $current["parent_name"], PDO::PARAM_STR);
            $stmt->bindValue(":parent_phone", $new["parent_phone"] ?? $current["parent_phone"], PDO::PARAM_STR);
            $stmt->bindValue(":guardian_name", $new["guardian_name"] ?? $current["guardian_name"], PDO::PARAM_STR);
            $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->rowCount();
        }
    }