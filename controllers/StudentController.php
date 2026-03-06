<?php
// controllers/StudentController.php
require_once 'config/database.php';
require_once 'models/User.php';
require_once 'models/ProjectRequest.php';
require_once 'models/Application.php';
require_once 'models/Project.php';

class StudentController {
    private $db;
    private $userModel;
    private $projectRequestModel;
    private $applicationModel;
    private $projectModel;
    
    public function __construct() {
        // Check if user is logged in and is student
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
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
        $student_id = $_SESSION['user_id'];
        
        // Get student statistics
        $stats = $this->getStudentStats($student_id);
        
        // Get recent applications
        $applications = $this->applicationModel->findByStudent($student_id);
        
        // Get active projects
        $projects = $this->projectModel->findByStudent($student_id);
        
        include 'views/student/dashboard.php';
    }
    
    public function browseRequests() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $budget = isset($_GET['budget']) ? $_GET['budget'] : '';
        
        $requests = $this->projectRequestModel->getAllOpen($search, $budget);
        
        // Check if student has already applied to each request
        $student_id = $_SESSION['user_id'];
        $applications = $this->applicationModel->findByStudent($student_id);
        $applied_request_ids = [];
        foreach ($applications as $app) {
            $applied_request_ids[] = $app['request_id'];
        }
        
        include 'views/student/browse_requests.php';
    }
    
    public function apply($request_id) {
        if (!$request_id) {
            header("Location: index.php?url=student/browseRequests");
            exit();
        }
        
        $student_id = $_SESSION['user_id'];
        
        // Check if already applied
        $applications = $this->applicationModel->findByStudent($student_id);
        foreach ($applications as $app) {
            if ($app['request_id'] == $request_id) {
                $_SESSION['error'] = "You have already applied to this project";
                header("Location: index.php?url=student/browseRequests");
                exit();
            }
        }
        
        // Get request details
        $request = $this->projectRequestModel->findById($request_id);
        
        if (!$request || $request['status'] != 'open') {
            header("Location: index.php?url=student/browseRequests");
            exit();
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $message = trim($_POST['message']);
            
            if (empty($message)) {
                $error = "Please include a message explaining why you're interested in this project.";
            } else {
                $data = [
                    'request_id' => $request_id,
                    'student_id' => $student_id,
                    'message' => $message
                ];
                
                if ($this->applicationModel->create($data)) {
                    $success = "Application submitted successfully!";
                    $this->userModel->logActivity($student_id, 'apply', "Applied to project: " . $request['title']);
                } else {
                    $error = "Failed to submit application.";
                }
            }
        }
        
        include 'views/student/apply.php';
    }
    
    public function updateProgress($project_id) {
        if (!$project_id) {
            header("Location: index.php?url=student/dashboard");
            exit();
        }
        
        $student_id = $_SESSION['user_id'];
        
        // Get project details and verify ownership
        $project = $this->projectModel->findById($project_id);
        
        if (!$project || $project['student_id'] != $student_id) {
            header("Location: index.php?url=student/dashboard");
            exit();
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_progress'])) {
            $progress_update = trim($_POST['progress_update']);
            
            if (!empty($progress_update)) {
                // Log the progress update
                $this->userModel->logActivity(
                    $student_id, 
                    'update_progress', 
                    "Updated progress on project: " . $project['title'] . " - " . $progress_update
                );
                
                $success = "Progress update recorded!";
            } else {
                $error = "Please describe your progress.";
            }
        }
        
        include 'views/student/update_progress.php';
    }
    
    private function getStudentStats($student_id) {
        $stats = [];
        
        // Pending applications
        $pending_sql = "SELECT COUNT(*) as count FROM applications WHERE student_id = ? AND status = 'pending'";
        $pending_stmt = $this->db->prepare($pending_sql);
        $pending_stmt->bind_param("i", $student_id);
        $pending_stmt->execute();
        $stats['pending'] = $pending_stmt->get_result()->fetch_assoc()['count'];
        
        // Accepted applications
        $accepted_sql = "SELECT COUNT(*) as count FROM applications WHERE student_id = ? AND status = 'accepted'";
        $accepted_stmt = $this->db->prepare($accepted_sql);
        $accepted_stmt->bind_param("i", $student_id);
        $accepted_stmt->execute();
        $stats['accepted'] = $accepted_stmt->get_result()->fetch_assoc()['count'];
        
        // Active projects
        $active_sql = "SELECT COUNT(*) as count FROM projects WHERE student_id = ? AND status = 'active'";
        $active_stmt = $this->db->prepare($active_sql);
        $active_stmt->bind_param("i", $student_id);
        $active_stmt->execute();
        $stats['active'] = $active_stmt->get_result()->fetch_assoc()['count'];
        
        // Completed projects
        $completed_sql = "SELECT COUNT(*) as count FROM projects WHERE student_id = ? AND status = 'completed'";
        $completed_stmt = $this->db->prepare($completed_sql);
        $completed_stmt->bind_param("i", $student_id);
        $completed_stmt->execute();
        $stats['completed'] = $completed_stmt->get_result()->fetch_assoc()['count'];
        
        return $stats;
    }
}
?>