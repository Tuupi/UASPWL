<?php
namespace PMS\Model;

use PDO;

class DoctorGateway{
    private PDO $conn;
    public function __construct(Database $database)
    {
     $this->conn = $database->getConnection();   
    }
    public function getAll() : array{
        $sql = "SELECT * FROM doctor_details";

            $stmt = $this->conn->query($sql);

            $data = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $data[] = $row;
            }

            return $data;
    }
    public function create(array $data):string{
        $sql = "INSERT INTO doctor_details (first_name, last_name, poli)
                VALUES (:first_name, :last_name, :poli)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":first_name", $data["first_name"], PDO::PARAM_STR);
        $stmt->bindValue(":last_name", $data["last_name"], PDO::PARAM_STR);
        $stmt->bindValue(":poli", $data["poli"], PDO::PARAM_STR);
        

        $stmt->execute();

        return $this->conn->lastInsertId();
    }
    public function get(string $id):array | false{
        $sql = "SELECT * FROM doctor_details WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;
    }
    public function update(array $current, array $new):int{
        $sql = "UPDATE doctor_details
                SET first_name = :first_name, last_name = :last_name, poli = :poli WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":first_name", $new["first_name"] ?? $current["first_name"], PDO::PARAM_STR);
        $stmt->bindValue(":last_name", $new["last_name"] ?? $current["last_name"], PDO::PARAM_STR);
        $stmt->bindValue(":poli", $new["poli"] ?? $current["poli"] , PDO::PARAM_STR);
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}