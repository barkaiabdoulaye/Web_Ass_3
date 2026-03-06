<?php
// models/Application.php
class Application {
    private $conn;
    private $table = 'applications';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (request_id, student_id, message, status) 
                VALUES (?, ?, ?, 'pending')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iis", 
            $data['request_id'],
            $data['student_id'],
            $data['message']
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }
    
    public function updateStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET status = ?, reviewed_at = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        
        return $stmt->execute();
    }
    
    public function findByRequest($request_id) {
        $sql = "SELECT a.*, u.full_name, u.email, u.phone 
                FROM {$this->table} a
                JOIN users u ON a.student_id = u.id
                WHERE a.request_id = ?
                ORDER BY a.applied_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $applications = [];
        while ($row = $result->fetch_assoc()) {
            $applications[] = $row;
        }
        
        return $applications;
    }
    
    public function findByStudent($student_id) {
        $sql = "SELECT a.*, pr.title, pr.budget_range, u.full_name as sme_name
                FROM {$this->table} a
                JOIN project_requests pr ON a.request_id = pr.id
                JOIN users u ON pr.sme_id = u.id
                WHERE a.student_id = ?
                ORDER BY a.applied_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $applications = [];
        while ($row = $result->fetch_assoc()) {
            $applications[] = $row;
        }
        
        return $applications;
    }
    
    public function findById($id) {
        $sql = "SELECT a.*, u.full_name as student_name, pr.title as project_title,
                sme.full_name as sme_name, pr.id as request_id, sme.id as sme_id, u.id as student_id
                FROM {$this->table} a
                JOIN users u ON a.student_id = u.id
                JOIN project_requests pr ON a.request_id = pr.id
                JOIN users sme ON pr.sme_id = sme.id
                WHERE a.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    public function getPendingWithDetails() {
        $sql = "SELECT a.*, u.full_name as student_name, pr.title as project_title,
                sme.full_name as sme_name
                FROM {$this->table} a
                JOIN users u ON a.student_id = u.id
                JOIN project_requests pr ON a.request_id = pr.id
                JOIN users sme ON pr.sme_id = sme.id
                WHERE a.status = 'accepted' 
                AND NOT EXISTS (SELECT 1 FROM projects WHERE request_id = a.request_id)
                ORDER BY a.applied_at DESC";
        
        $result = $this->conn->query($sql);
        
        $applications = [];
        while ($row = $result->fetch_assoc()) {
            $applications[] = $row;
        }
        
        return $applications;
    }
    
    public function acceptAndCreateProject($application_id, $admin_id) {
        $this->conn->begin_transaction();
        
        try {
            // Get application details
            $app = $this->findById($application_id);
            
            if (!$app) {
                throw new Exception("Application not found");
            }
            
            // Update application status
            $update_app_sql = "UPDATE {$this->table} SET status = 'accepted', reviewed_at = NOW() WHERE id = ?";
            $update_app_stmt = $this->conn->prepare($update_app_sql);
            $update_app_stmt->bind_param("i", $application_id);
            $update_app_stmt->execute();
            
            // Create project
            $project_sql = "INSERT INTO projects (request_id, sme_id, student_id, current_milestone, status) 
                            VALUES (?, ?, ?, 'ui_design', 'active')";
            $project_stmt = $this->conn->prepare($project_sql);
            $project_stmt->bind_param("iii", $app['request_id'], $app['sme_id'], $app['student_id']);
            $project_stmt->execute();
            
            // Update request status
            $update_req_sql = "UPDATE project_requests SET status = 'in_progress' WHERE id = ?";
            $update_req_stmt = $this->conn->prepare($update_req_sql);
            $update_req_stmt->bind_param("i", $app['request_id']);
            $update_req_stmt->execute();
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error in acceptAndCreateProject: " . $e->getMessage());
            return false;
        }
    }
}
?>