<?php
// Start session at the beginning of each request
session_start();

// Include database configuration
require_once 'app/config/database.php';

// Create database connection
$database = new Database();
$db = $database->getConnection();

// Get URL parameter
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// If no URL is provided, show home page
if (empty($url[0])) {
    include 'home.php';
    exit();
}

// Determine controller name - default to ProductController if empty
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'ProductController';

// Determine action - default to index if empty
$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

// Debug line (uncomment if needed for debugging)
// die("controller=$controllerName - action=$action");

// Check if controller file exists
if (!file_exists('app/controllers/' . $controllerName . '.php')) {
    // Handle controller not found
    http_response_code(404);
    die('Controller not found: ' . $controllerName);
}

// Include and instantiate the controller
require_once 'app/controllers/' . $controllerName . '.php';

// Check if controller class exists
if (!class_exists($controllerName)) {
    http_response_code(500);
    die('Controller class not found: ' . $controllerName);
}

// Create controller instance with database connection
if ($controllerName === 'UserController' || $controllerName === 'ProductController' || $controllerName === 'CategoryController') {
    $controller = new $controllerName($db);
} else {
    $controller = new $controllerName();
}

// Check if action method exists
if (!method_exists($controller, $action)) {
    // Handle action not found
    http_response_code(404);
    die('Action not found: ' . $action . ' in ' . $controllerName);
}

// Call the action with remaining parameters (if any)
try {
    call_user_func_array([$controller, $action], array_slice($url, 2));
} catch (Exception $e) {
    // Handle any exceptions that might occur during execution
    http_response_code(500);
    error_log("Error in $controllerName::$action - " . $e->getMessage());
    die('An error occurred while processing your request.');
}
?>