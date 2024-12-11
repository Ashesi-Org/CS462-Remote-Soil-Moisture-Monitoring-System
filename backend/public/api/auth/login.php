<?php
header("Access-Control-Allow-Origin: http://localhost");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../models/User.php';

session_start();

try {
    $data = json_decode(file_get_contents("php://input"));

    if (!$data || empty($data->email) || empty($data->password)) {
        throw new Exception("Email and password are required.");
    }

    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);
    
    $userData = $user->login($data->email, $data->password);
    
    if ($userData) {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['user_email'] = $userData['email'];
        $_SESSION['user_name'] = $userData['full_name'];
        
        echo json_encode([
            "success" => true,
            "message" => "Login successful",
            "user" => $userData
        ]);
    } else {
        throw new Exception("Invalid credentials");
    }
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}