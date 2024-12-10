<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../models/User.php';

// Start session
session_start();

try {
    // Get posted data
    $data = json_decode(file_get_contents("php://input"));

    if (!$data || empty($data->email) || empty($data->password)) {
        throw new Exception("Email and password are required.");
    }

    // Initialize database
    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        throw new Exception("Database connection failed.");
    }

    // Create user model instance
    $user = new User($db);
    
    // Verify user credentials
    $userData = $user->login($data->email, $data->password);
    
    if ($userData) {
        // Set session data
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['user_email'] = $userData['email'];
        $_SESSION['user_name'] = $userData['full_name'];
        
        // Regenerate session ID for security
        session_regenerate_id(true);

        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => "Login successful",
            "user" => $userData
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            "success" => false,
            "message" => "Invalid email or password"
        ]);
    }
} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Login failed: " . $e->getMessage()
    ]);
}