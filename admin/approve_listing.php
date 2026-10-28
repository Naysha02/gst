<?php
require_once '../functions.php';
if (!isAdminLoggedIn()) redirect('login.php');
$id = intval($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';
if ($id && in_array($action, ['publish','reject'])) {
    $status = $action === 'publish' ? 'published' : 'removed';
    $conn->query("UPDATE listings SET status='$status' WHERE id = $id");
}
redirect('dashboard.php');
