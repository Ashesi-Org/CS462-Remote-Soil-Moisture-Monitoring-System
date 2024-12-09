<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_URI'] === '/health') {
    http_response_code(200);
    echo json_encode(['status' => 'healthy']);
    exit;
}

// Your other API endpoints will go here
echo json_encode(['message' => 'Welcome to the API']);

