<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$response = [
    'name' => $_SESSION['user_name'] ?? 'User',
    'isLoggedIn' => isset($_SESSION['user_id'])
];

echo json_encode($response);
?>