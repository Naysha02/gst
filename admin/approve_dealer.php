<?php
require_once '../functions.php';
if (!isAdminLoggedIn()) redirect('login.php');
$id = intval($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';
if ($id && in_array($action, ['approve','reject'])) {
    $status = $action === 'approve' ? 'approved' : 'rejected';
    $conn->query("UPDATE dealers SET status='$status', verified=" . ($action === 'approve' ? 1 : 0) . " WHERE id = $id");
}
redirect('dashboard.php');
