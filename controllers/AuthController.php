<?php
//ontrollers/AuthController.php
require_once 'config/database.php';
require_once 'models/User.php';
// Remplacer tous les "include 'views/...'" par :
//include __DIR__ . '/../views/auth/login.php';
//include __DIR__ . '/../views/auth/register.php';

class AuthController {
    private $db;
    private $userModel;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
    }
    
    public function login() {
        // Redirect if already logged in
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit();
        }
        
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            
            $user = $this->userModel->findByEmail($email);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_role'] = $user['role'];
                
                $this->userModel->logActivity($user['id'], 'login', 'User logged in');
                
                // Redirect based on role
                switch($user['role']) {
                    case 'sme':
                        header("Location: index.php?url=sme/dashboard");
                        break;
                    case 'student':
                        header("Location: index.php?url=student/dashboard");
                        break;
                    case 'admin':
                        header("Location: index.php?url=admin/dashboard");
                        break;
                    default:
                        header("Location: index.php");
                }
                exit();
            } else {
                $error = "Invalid email or password";
            }
        }
        
        include 'views/auth/login.php';
    }
    
    public function register() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit();
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $full_name = trim($_POST['full_name']);
            $role = $_POST['role'];
            $company_name = ($role == 'sme') ? trim($_POST['company_name']) : null;
            $phone = trim($_POST['phone']);
            
            // Validation
            if ($password !== $confirm_password) {
                $error = "Passwords do not match";
            } elseif (strlen($password) < 6) {
                $error = "Password must be at least 6 characters";
            } elseif ($role == 'sme' && empty($company_name)) {
                $error = "Company name is required for SME owners";
            } else {
                // Check if email exists
                if ($this->userModel->findByEmail($email)) {
                    $error = "Email already registered";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    $userData = [
                        'email' => $email,
                        'password' => $hashed_password,
                        'full_name' => $full_name,
                        'role' => $role,
                        'company_name' => $company_name,
                        'phone' => $phone
                    ];
                    
                    if ($this->userModel->create($userData)) {
                        $success = "Registration successful! Please login.";
                    } else {
                        $error = "Registration failed";
                    }
                }
            }
        }
        
        include 'views/auth/register.php';
    }
    
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $this->userModel->logActivity($_SESSION['user_id'], 'logout', 'User logged out');
        }
        
        // Destroy session
        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        
        // Redirect to home
        header("Location: index.php");
        exit();
    }
}
?>