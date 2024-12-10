<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../models/User.php';

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Ensure this is set to 0 in production

// Get posted data
$data = json_decode(file_get_contents("php://input"));

if (!$data || empty($data->fullName) || empty($data->email) || empty($data->password)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "All fields are required."
    ]);
    exit();
}

// Initialize database
$database = new Database();
$db = $database->getConnection();

if (!$db) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed."
    ]);
    exit();
}

// Create user model instance
$user = new User($db);

try {
    // Check if email already exists
    if ($user->emailExists($data->email)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Email already exists."
        ]);
        exit();
    }

    // Create the user
    $userId = $user->create([
        "fullName" => $data->fullName,
        "email" => $data->email,
        "password" => $data->password
    ]);

    if ($userId) {
        http_response_code(201);
        echo json_encode([
            "success" => true,
            "message" => "User registered successfully."
        ]);
    } else {
        throw new Exception("User creation failed");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Registration failed: " . $e->getMessage()
    ]);
}