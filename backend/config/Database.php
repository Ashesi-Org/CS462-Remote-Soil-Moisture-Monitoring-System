<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn = null;

    public function __construct() {
        $this->host = getenv('POSTGRES_HOST') ?: 'postgres';
        $this->db_name = getenv('POSTGRES_DB') ?: 'ecogo';
        $this->username = getenv('POSTGRES_USER') ?: 'postgres';
        $this->password = getenv('POSTGRES_PASSWORD') ?: 'ecogo';
    }

    public function getConnection() {
        try {
            if ($this->conn === null) {
                $dsn = "pgsql:host=" . $this->host . ";dbname=" . $this->db_name;
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $this->conn;
        } catch(PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            return null;
        }
    }
} 