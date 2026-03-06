<?php
// controllers/SMEController.php
require_once 'config/database.php';
require_once 'models/User.php';
require_once 'models/ProjectRequest.php';
require_once 'models/Application.php';
require_once 'models/Project.php';

class SMEController {
    private $db;
    private $userModel;
    private $projectRequestModel;
    private $applicationModel;
    private $projectModel;
    
    public function __construct() {
        // Check if user is logged in and is SME
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'sme') {
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
        $sme_id = $_SESSION['user_id'];
        
        $stats = $this->projectRequestModel->getStats($sme_id);
        $requests = $this->projectRequestModel->findBySme($sme_id);
        $projects = $this->projectModel->findBySme($sme_id);
        
        include 'views/sme/dashboard.php';
    }
    
    public function postRequest() {
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $required_skills = trim($_POST['required_skills']);
            $budget_range = $_POST['budget_range'];
            $sme_id = $_SESSION['user_id'];
            
            if (strlen($title) < 5) {
                $error = "Title must be at least 5 characters";
            } elseif (strlen($description) < 20) {
                $error = "Description must be at least 20 characters";
            } else {
                $data = [
                    'sme_id' => $sme_id,
                    'title' => $title,
                    'description' => $description,
                    'required_skills' => $required_skills,
                    'budget_range' => $budget_range
                ];
                
                if ($this->projectRequestModel->create($data)) {
                    $success = "Project request posted successfully!";
                    $this->userModel->logActivity($sme_id, 'post_request', "Posted: " . $title);
                } else {
                    $error = "Failed to post request";
                }
            }
        }
        
        include 'views/sme/post_request.php';
    }
    
    public function editRequest($id) {
        $request = $this->projectRequestModel->findById($id);
        
        // Verify ownership
        if (!$request || $request['sme_id'] != $_SESSION['user_id']) {
            header("Location: index.php?url=sme/dashboard");
            exit();
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'required_skills' => trim($_POST['required_skills']),
                'budget_range' => $_POST['budget_range'],
                'status' => $_POST['status']
            ];
            
            if (strlen($data['title']) < 5) {
                $error = "Title must be at least 5 characters";
            } elseif (strlen($data['description']) < 20) {
                $error = "Description must be at least 20 characters";
            } else {
                if ($this->projectRequestModel->update($id, $data)) {
                    $success = "Request updated successfully!";
                    $request = array_merge($request, $data);
                } else {
                    $error = "Failed to update request";
                }
            }
        }
        
        include 'views/sme/edit_request.php';
    }
    
    public function viewApplications($request_id = null) {
        $sme_id = $_SESSION['user_id'];
        $requests = $this->projectRequestModel->findBySme($sme_id);
        
        if ($request_id) {
            // Verify ownership
            $request = $this->projectRequestModel->findById($request_id);
            if (!$request || $request['sme_id'] != $sme_id) {
                header("Location: index.php?url=sme/dashboard");
                exit();
            }
            
            $applications = $this->applicationModel->findByRequest($request_id);
        }
        
        include 'views/sme/view_applications.php';
    }
    
    public function updateApplicationStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['application_id']) && isset($_POST['action'])) {
            $application_id = $_POST['application_id'];
            $action = $_POST['action'];
            
            $status = ($action == 'accept') ? 'accepted' : 'rejected';
            
            if ($this->applicationModel->updateStatus($application_id, $status)) {
                $this->userModel->logActivity($_SESSION['user_id'], $action . '_application', "Application ID: " . $application_id);
            }
            
            header("Location: index.php?url=sme/viewApplications/" . $_POST['request_id']);
            exit();
        }
    }
    
    public function approveMilestone($project_id) {
        $project = $this->projectModel->findById($project_id);
        
        // Verify ownership
        if (!$project || $project['sme_id'] != $_SESSION['user_id']) {
            header("Location: index.php?url=sme/dashboard");
            exit();
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve'])) {
            if ($this->projectModel->advanceMilestone($project_id)) {
                $success = "Milestone approved!";
                $project = $this->projectModel->findById($project_id);
                $this->userModel->logActivity($_SESSION['user_id'], 'approve_milestone', "Project ID: " . $project_id);
            } else {
                $error = "Failed to approve milestone";
            }
        }
        
        include 'views/sme/approve_milestone.php';
    }
}
?>