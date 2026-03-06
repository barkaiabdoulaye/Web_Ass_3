<?php
// index.php - Main Router
session_start();

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoloader
spl_autoload_register(function ($class_name) {
    if (file_exists('controllers/' . $class_name . '.php')) {
        require_once 'controllers/' . $class_name . '.php';
    } elseif (file_exists('models/' . $class_name . '.php')) {
        require_once 'models/' . $class_name . '.php';
    }
});

// Get URL
$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = rtrim($url, '/');
$url = explode('/', $url);

// Default controller and method
$controller_name = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'HomeController';
$method_name = !empty($url[1]) ? $url[1] : 'index';
$param = !empty($url[2]) ? $url[2] : null;

// URL mapping
$controller_map = [
    'auth' => 'AuthController',
    'sme' => 'SMEController',
    'student' => 'StudentController',
    'admin' => 'AdminController',
    '' => 'HomeController'
];

$controller_class = isset($controller_map[$url[0]]) ? $controller_map[$url[0]] : null;

if ($controller_class && file_exists('controllers/' . $controller_class . '.php')) {
    require_once 'controllers/' . $controller_class . '.php';
    $controller = new $controller_class();
    
    if ($param) {
        if (method_exists($controller, $method_name)) {
            $controller->$method_name($param);
        } else {
            $controller->index();
        }
    } elseif (method_exists($controller, $method_name)) {
        $controller->$method_name();
    } else {
        $controller->index();
    }
} else {
    // Home page
    require_once 'controllers/HomeController.php';
    $controller = new HomeController();
    $controller->index();
}
?>