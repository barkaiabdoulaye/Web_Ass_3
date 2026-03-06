<?php
// controllers/HomeController.php
require_once 'config/database.php';



class HomeController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function index() {
        // Get statistics
        $stats = [];
        
        $sme_result = $this->db->query("SELECT COUNT(*) as count FROM users WHERE role='sme'");
        $stats['smes'] = $sme_result->fetch_assoc()['count'];
        
        $student_result = $this->db->query("SELECT COUNT(*) as count FROM users WHERE role='student'");
        $stats['students'] = $student_result->fetch_assoc()['count'];
        
        $projects_result = $this->db->query("SELECT COUNT(*) as count FROM projects WHERE status='active'");
        $stats['active_projects'] = $projects_result->fetch_assoc()['count'];
        
        include 'views/home.php';
    }
}
?>