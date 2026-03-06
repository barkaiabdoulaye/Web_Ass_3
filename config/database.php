<?php
// config/database.php
class Database {
    private $host = "localhost";
    private $db_name = "sme_stack";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
            
            $this->conn->set_charset("utf8mb4");
            
        } catch(Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            die("Database connection failed. Please try again later.");
        }
        
        return $this->conn;
    }
    
    public function prepareAndExecute($sql, $types = "", $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }
            
            if ($types && $params) {
                $stmt->bind_param($types, ...$params);
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            return $stmt;
            
        } catch(Exception $e) {
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }
    
    public function beginTransaction() {
        $this->conn->begin_transaction();
    }
    
    public function commit() {
        $this->conn->commit();
    }
    
    public function rollback() {
        $this->conn->rollback();
    }
    
    public function lastInsertId() {
        return $this->conn->insert_id;
    }
}
?>