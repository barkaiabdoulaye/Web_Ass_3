<?php
//controllers/AdminController.php
require_once 'config/database.php';
require_once 'models/User.php';
require_once 'models/ProjectRequest.php';
require_once 'models/Application.php';
require_once 'models/Project.php';
//

// Remplacer tous les "include 'views/...'" par :
//include __DIR__ . '/../views/admin/dashboard.php';
//include __DIR__ . '/../views/admin/users.php';
//include __DIR__ . '/../views/admin/projects.php';
//include __DIR__ . '/../views/admin/assign_team.php';
//include __DIR__ . '/../views/admin/view_project.php';

class AdminController {
    private $db;
    private $userModel;
    private $projectRequestModel;
    private $applicationModel;
    private $projectModel;
    
    public function __construct() {
        // Check if user is logged in and is admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
            header("Location: index.php?url=auth/login");
            exit();
        }
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
        $this->projectRequestModel = new ProjectRequest($this->db);
        $this->applicationModel = new Application($this->db);
        $this->projectModel = new Project($this->db);
    }
    
    public function dashboard() {
        // Get statistics
        $userStats = $this->userModel->getStats();
        
        // Project statistics
        $projectStats = $this->getProjectStats();
        
        // Get pending applications ready for assignment
        $pendingApplications = $this->applicationModel->getPendingWithDetails();
        
        // Get recent activity
        $activityLog = $this->getActivityLog(10);
        
        include 'views/admin/dashboard.php';
    }
    
    public function users() {
        $users = $this->userModel->getAll();
        include 'views/admin/users.php';
    }
    
    public function projects($status = 'all') {
        $projects = $this->projectModel->getAll($status);
        include 'views/admin/projects.php';
    }
    
    public function assignTeam($application_id) {
        if (!$application_id) {
            header("Location: index.php?url=admin/dashboard");
            exit();
        }
        
        $application = $this->applicationModel->findById($application_id);
        
        if (!$application) {
            header("Location: index.php?url=admin/dashboard");
            exit();
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign'])) {
            if ($this->applicationModel->acceptAndCreateProject($application_id, $_SESSION['user_id'])) {
                $this->userModel->logActivity(
                    $_SESSION['user_id'], 
                    'assign_team', 
                    "Assigned team to project: " . $application['project_title']
                );
                
                $_SESSION['success'] = "Team assigned successfully!";
                header("Location: index.php?url=admin/dashboard");
                exit();
            } else {
                $error = "Failed to assign team";
            }
        }
        
        include 'views/admin/assign_team.php';
    }
    
    public function toggleUser($user_id) {
        if (!$user_id) {
            header("Location: index.php?url=admin/users");
            exit();
        }
        
        // Get user current status
        $user = $this->userModel->findById($user_id);
        
        if ($user) {
            $new_status = $user['is_active'] ? 0 : 1;
            
            $sql = "UPDATE users SET is_active = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $new_status, $user_id);
            
            if ($stmt->execute()) {
                $this->userModel->logActivity(
                    $_SESSION['user_id'],
                    'toggle_user',
                    "Changed user " . $user['email'] . " status to " . ($new_status ? 'active' : 'inactive')
                );
            }
        }
        
        header("Location: index.php?url=admin/users");
        exit();
    }
    
    public function deleteUser($user_id) {
        if (!$user_id) {
            header("Location: index.php?url=admin/users");
            exit();
        }
        
        // Don't allow deleting yourself
        if ($user_id == $_SESSION['user_id']) {
            $_SESSION['error'] = "You cannot delete your own account";
            header("Location: index.php?url=admin/users");
            exit();
        }
        
        $user = $this->userModel->findById($user_id);
        
        if ($user) {
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $user_id);
            
            if ($stmt->execute()) {
                $this->userModel->logActivity(
                    $_SESSION['user_id'],
                    'delete_user',
                    "Deleted user: " . $user['email']
                );
                
                $_SESSION['success'] = "User deleted successfully";
            }
        }
        
        header("Location: index.php?url=admin/users");
        exit();
    }
    
    public function viewProject($project_id) {
        if (!$project_id) {
            header("Location: index.php?url=admin/projects");
            exit();
        }
        
        $project = $this->projectModel->findById($project_id);
        
        if (!$project) {
            header("Location: index.php?url=admin/projects");
            exit();
        }
        
        include 'views/admin/view_project.php';
    }
    
    private function getProjectStats() {
        $sql = "SELECT 
                    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_projects,
                    COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_projects,
                    COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled_projects,
                    COUNT(*) as total_projects
                FROM projects";
        
        $result = $this->db->query($sql);
        return $result->fetch_assoc();
    }
    
    private function getActivityLog($limit = 20) {
        $sql = "SELECT al.*, u.full_name, u.role 
                FROM activity_log al
                LEFT JOIN users u ON al.user_id = u.id
                ORDER BY al.created_at DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $logs = [];
        while ($row = $result->fetch_assoc()) {
            $logs[] = $row;
        }
        
        return $logs;
    }
}
?>