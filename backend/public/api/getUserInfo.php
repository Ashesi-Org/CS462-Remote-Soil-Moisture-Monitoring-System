<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');

if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
    echo json_encode([
        'success' => true,
        'name' => $_SESSION['user_name'],
        'isLoggedIn' => true
    ]);
} else {
    echo json_encode([
        'success' => false,
        'name' => 'Guest',
        'isLoggedIn' => false
    ]);
}
?>