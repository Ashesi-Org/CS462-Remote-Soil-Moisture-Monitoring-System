<?php
header("Access-Control-Allow-Origin: http://localhost");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$request_uri = $_SERVER['REQUEST_URI'];

// Route API requests
if (strpos($request_uri, 'auth/register.php') !== false) {
    require __DIR__ . '/api/auth/register.php';
    exit;
}

if (strpos($request_uri, 'auth/login.php') !== false) {
    require __DIR__ . '/api/auth/login.php';
    exit;
}

if (strpos($request_uri, 'getUserInfo.php') !== false) {
    require __DIR__ . '/api/getUserInfo.php';
    exit;
}

// New route for irrigation recommendations
if (strpos($request_uri, 'recommendation.php') !== false) {
    require __DIR__ . '/api/recommendation.php';
    exit;
}

// Health check endpoint
if ($_SERVER['REQUEST_URI'] === '/api/health') {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'healthy']);
    exit;
}

// Add this new route
if (strpos($request_uri, 'user/update-profile.php') !== false) {
    require __DIR__ . '/api/user/update-profile.php';
    exit;
}

// Default response for unmatched routes
http_response_code(404);
echo json_encode(['error' => 'Not Found', 'uri' => $request_uri]);

