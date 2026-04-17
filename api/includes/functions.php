<?php
// includes/functions.php
session_start();

// Sanitize Input
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Redirect Helper
function redirect($url) {
    header("Location: $url");
    exit();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Check if user is teacher
function isTeacher() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'teacher';
}

// Require Login
function requireLogin() {
    if (!isLoggedIn()) {
        redirect('../login.php');
    }
}

// Require Admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        die("Access Denied: You do not have permission to view this page.");
    }
}

// Log Activity
function logActivity($pdo, $user_id, $action, $description = "") {
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $action, $description, $_SERVER['REMOTE_ADDR']]);
}
?>
