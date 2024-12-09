<?php
class User {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                 (full_name, email, password_hash) 
                 VALUES (:full_name, :email, :password_hash)
                 RETURNING id";

        try {
            $stmt = $this->conn->prepare($query);
            
            $fullName = htmlspecialchars(strip_tags($data['fullName']));
            $email = htmlspecialchars(strip_tags($data['email']));
            $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

            $stmt->bindParam(":full_name", $fullName);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password_hash", $passwordHash);

            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
} 