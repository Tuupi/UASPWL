<?php
namespace PMS\Model;
use PDO;

class LoginGateway{
    private PDO $conn;
    public function __construct(Database $database)
    {
     $this->conn = $database->getConnection();   
    }
    public function get(array $login) : array{
        $data = [];
        $sql = "SELECT username, role FROM login_credentials WHERE username = :username AND password = :password";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":username", $login["username"], PDO::PARAM_INT);
        $stmt->bindValue(":password", $login["password"], PDO::PARAM_INT);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $data = (array)$stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $data;
    }
}