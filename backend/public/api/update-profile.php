<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_log', __DIR__ . '/../../../../logs/error.log');

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/User.php';

session_start();

error_log("Session data: " . print_r($_SESSION, true));
error_log("Cookies: " . print_r($_COOKIE, true));

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    error_log("User not logged in. Session ID: " . session_id());
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized access. Please log in."
    ]);
    exit();
}

try {
    // Get posted data
    $rawData = file_get_contents("php://input");
    error_log("Raw request data: " . $rawData);
    
    $data = json_decode($rawData);
    error_log("Decoded data: " . print_r($data, true));

    if (!$data || empty($data->fullName) || empty($data->email)) {
        throw new Exception("Name and email are required.");
    }

    // Initialize database
    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        error_log("Database connection failed");
        throw new Exception("Database connection failed");
    }

    // Create user model instance
    $user = new User($db);
    
    // Check if email is already taken by another user
    $existingUser = $user->emailExists($data->email, $_SESSION['user_id']);
    if ($existingUser) {
        throw new Exception("Email is already taken by another user.");
    }

    // Update user profile
    $updateData = [
        'id' => $_SESSION['user_id'],
        'fullName' => $data->fullName,
        'email' => $data->email,
        'password' => !empty($data->password) ? $data->password : null
    ];

    error_log("Attempting to update profile with data: " . print_r($updateData, true));

    $result = $user->updateProfile($updateData);

    if ($result) {
        // Update session data
        $_SESSION['user_email'] = $data->email;
        $_SESSION['user_name'] = $data->fullName;

        $responseData = [
            "success" => true,
            "message" => "Profile updated successfully",
            "user" => [
                "id" => $_SESSION['user_id'],
                "email" => $data->email,
                "full_name" => $data->fullName
            ]
        ];

        error_log("Sending success response: " . print_r($responseData, true));
        echo json_encode($responseData);
    } else {
        throw new Exception("Profile update failed");
    }
} catch (Exception $e) {
    error_log("Error in update-profile.php: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
} 