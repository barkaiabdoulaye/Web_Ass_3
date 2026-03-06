<?php
// models/ProjectRequest.php
class ProjectRequest {
    private $conn;
    private $table = 'project_requests';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (sme_id, title, description, required_skills, budget_range, status) 
                VALUES (?, ?, ?, ?, ?, 'open')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issss", 
            $data['sme_id'],
            $data['title'],
            $data['description'],
            $data['required_skills'],
            $data['budget_range']
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }
    
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET title = ?, description = ?, required_skills = ?, budget_range = ?, status = ? 
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssi", 
            $data['title'],
            $data['description'],
            $data['required_skills'],
            $data['budget_range'],
            $data['status'],
            $id
        );
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
    
    public function findBySme($sme_id) {
        $sql = "SELECT * FROM {$this->table} WHERE sme_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $sme_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $requests = [];
        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
        
        return $requests;
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    public function getAllOpen($search = '', $budget = '') {
        $sql = "SELECT pr.*, u.full_name as sme_name, u.company_name
                FROM {$this->table} pr
                JOIN users u ON pr.sme_id = u.id
                WHERE pr.status = 'open'";
        
        $params = [];
        $types = "";
        
        if (!empty($search)) {
            $sql .= " AND (pr.title LIKE ? OR pr.description LIKE ? OR pr.required_skills LIKE ?)";
            $search_term = "%$search%";
            $params[] = $search_term;
            $params[] = $search_term;
            $params[] = $search_term;
            $types .= "sss";
        }
        
        if (!empty($budget)) {
            $sql .= " AND pr.budget_range = ?";
            $params[] = $budget;
            $types .= "s";
        }
        
        $sql .= " ORDER BY pr.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        
        $requests = [];
        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
        
        return $requests;
    }
    
    public function getStats($sme_id) {
        $sql = "SELECT 
                    COUNT(CASE WHEN status = 'open' THEN 1 END) as open_requests,
                    COUNT(CASE WHEN status = 'in_progress' THEN 1 END) as active_projects,
                    COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_projects
                FROM {$this->table} 
                WHERE sme_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $sme_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
}
?>