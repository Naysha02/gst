<?php
// ============================
// Global Config + Connection
// ============================

// Database config
$host = "localhost";
$user = "root";          // apna MySQL username
$pass = "";              // apna MySQL password
$db   = "findusedcarz";  // apna database name

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// ============================
// Session Start (Safe)
// ============================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================
// Helper Functions
// ============================

// Escape output (XSS safe)
function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Redirect to another page
function redirect($url) {
    header("Location: $url");
    exit;
}

// ============================
// Admin Functions
// ============================
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireAdmin() {
    if (!isAdminLoggedIn()) {
        redirect('login.php');
    }
}

// ============================
// Dealer Functions
// ============================
// Dealer login ke time pe session aise set karo:
// $_SESSION['dealer_id'] = $id;
// $_SESSION['dealer_email'] = $email;

function isDealerLoggedIn() {
    return isset($_SESSION['dealer_id']);
}

function requireDealer() {
    if (!isDealerLoggedIn()) {
        redirect('login.php');
    }
}

// ============================
// Customer Functions
// ============================
// Customer login ke time pe session aise set karo:
// $_SESSION['customer_id'] = $id;
// $_SESSION['customer_email'] = $email;

function isCustomerLoggedIn() {
    return isset($_SESSION['customer_id']);
}

function requireCustomer() {
    if (!isCustomerLoggedIn()) {
        redirect('login.php');
    }
}
// ============================
// File Upload Helper
// ============================
function saveUploadedImages($files, $uploadDir = '../uploads/listings/') {
    $savedFiles = [];

    // Create directory if not exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    for ($i = 0; $i < count($files['name']); $i++) {
        $name = basename($files['name'][$i]);
        $tmp  = $files['tmp_name'][$i];
        $ext  = pathinfo($name, PATHINFO_EXTENSION);
        $allowed = ['jpg','jpeg','png','webp','gif'];

        if (!in_array(strtolower($ext), $allowed)) continue;

        // Generate unique name
        $newName = uniqid() . "." . $ext;
        $dest = $uploadDir . $newName;

        if (move_uploaded_file($tmp, $dest)) {
            $savedFiles[] = $dest;
        }
    }

    return $savedFiles; // Array of file paths
}
