<?php
// models/User.php
class User {
    private $conn;
    private $table = 'users';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (email, password, full_name, role, company_name, phone, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, 1)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss", 
            $data['email'], 
            $data['password'], 
            $data['full_name'], 
            $data['role'], 
            $data['company_name'], 
            $data['phone']
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }
    
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET full_name = ?, phone = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $data['full_name'], $data['phone'], $id);
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
    
    public function getAll($role = null) {
        $sql = "SELECT * FROM {$this->table}";
        if ($role) {
            $sql .= " WHERE role = ? ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $role);
        } else {
            $sql .= " ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($sql);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        
        return $users;
    }
    
    public function logActivity($user_id, $action, $details) {
        $sql = "INSERT INTO activity_log (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $ip = $_SERVER['REMOTE_ADDR'];
        $stmt->bind_param("isss", $user_id, $action, $details, $ip);
        
        return $stmt->execute();
    }
    
    public function getStats() {
        $sql = "SELECT 
                    COUNT(CASE WHEN role = 'sme' THEN 1 END) as total_smes,
                    COUNT(CASE WHEN role = 'student' THEN 1 END) as total_students,
                    COUNT(CASE WHEN role = 'admin' THEN 1 END) as total_admins,
                    COUNT(*) as total_users
                FROM {$this->table}";
        
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
}
?>