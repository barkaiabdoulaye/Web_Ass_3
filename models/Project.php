<?php
// models/Project.php
class Project {
    private $conn;
    private $table = 'projects';
    private $milestones = ['ui_design', 'frontend', 'backend', 'testing', 'delivered'];
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function findBySme($sme_id) {
        $sql = "SELECT p.*, pr.title, u.full_name as student_name
                FROM {$this->table} p
                JOIN project_requests pr ON p.request_id = pr.id
                JOIN users u ON p.student_id = u.id
                WHERE p.sme_id = ? AND p.status = 'active'
                ORDER BY p.started_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $sme_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $projects = [];
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
        
        return $projects;
    }
    
    public function findByStudent($student_id) {
        $sql = "SELECT p.*, pr.title, u.full_name as sme_name, u.company_name
                FROM {$this->table} p
                JOIN project_requests pr ON p.request_id = pr.id
                JOIN users u ON p.sme_id = u.id
                WHERE p.student_id = ? AND p.status = 'active'
                ORDER BY p.started_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $projects = [];
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
        
        return $projects;
    }
    
    public function findById($id) {
        $sql = "SELECT p.*, pr.title, u.full_name as sme_name, u.company_name,
                stu.full_name as student_name, stu.id as student_id, u.id as sme_id
                FROM {$this->table} p
                JOIN project_requests pr ON p.request_id = pr.id
                JOIN users u ON p.sme_id = u.id
                JOIN users stu ON p.student_id = stu.id
                WHERE p.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    public function getAll($status = 'all') {
        $sql = "SELECT p.*, pr.title as project_title, 
                sme.full_name as sme_name, sme.company_name,
                stu.full_name as student_name
                FROM {$this->table} p
                JOIN project_requests pr ON p.request_id = pr.id
                JOIN users sme ON p.sme_id = sme.id
                JOIN users stu ON p.student_id = stu.id";
        
        if ($status != 'all') {
            $sql .= " WHERE p.status = ? ORDER BY p.started_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $status);
        } else {
            $sql .= " ORDER BY p.started_at DESC";
            $stmt = $this->conn->prepare($sql);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $projects = [];
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
        
        return $projects;
    }
    
    public function advanceMilestone($project_id) {
        $project = $this->findById($project_id);
        
        if (!$project) {
            return false;
        }
        
        $current_index = array_search($project['current_milestone'], $this->milestones);
        
        if ($current_index !== false && $current_index < count($this->milestones) - 1) {
            $next_milestone = $this->milestones[$current_index + 1];
            
            $this->conn->begin_transaction();
            
            try {
                // Update project milestone
                $sql = "UPDATE {$this->table} SET current_milestone = ? WHERE id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("si", $next_milestone, $project_id);
                $stmt->execute();
                
                // Record milestone approval
                $approval_sql = "INSERT INTO milestone_approvals (project_id, milestone, approved_by_sme, approved_at) 
                                VALUES (?, ?, TRUE, NOW())";
                $approval_stmt = $this->conn->prepare($approval_sql);
                $approval_stmt->bind_param("is", $project_id, $project['current_milestone']);
                $approval_stmt->execute();
                
                // Check if project completed
                if ($next_milestone == 'delivered') {
                    $complete_sql = "UPDATE {$this->table} SET status = 'completed', completed_at = NOW() WHERE id = ?";
                    $complete_stmt = $this->conn->prepare($complete_sql);
                    $complete_stmt->bind_param("i", $project_id);
                    $complete_stmt->execute();
                }
                
                $this->conn->commit();
                return true;
                
            } catch (Exception $e) {
                $this->conn->rollback();
                error_log("Error in advanceMilestone: " . $e->getMessage());
                return false;
            }
        }
        
        return false;
    }
    
    public function getStats() {
        $sql = "SELECT 
                    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_projects,
                    COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_projects,
                    COUNT(*) as total_projects
                FROM {$this->table}";
        
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
}
?>