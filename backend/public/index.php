<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

// Corrected require_once paths
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get the request URI
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

// Default response for unmatched routes
http_response_code(404);
echo json_encode(['error' => 'Not Found', 'uri' => $request_uri]);

