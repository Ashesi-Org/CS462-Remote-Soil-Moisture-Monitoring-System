<?php
header('Content-Type: application/json');
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy the session
session_destroy();

echo json_encode(['status' => 'success', 'message' => 'Successfully logged out']); 