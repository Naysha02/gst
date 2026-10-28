<?php
require_once '../functions.php';
$q = $conn->real_escape_string($_GET['q'] ?? '');
$where = " WHERE status='published' ";
if ($q) $where .= " AND (title LIKE '%$q%' OR make LIKE '%$q%' OR model LIKE '%$q%') ";
$res = $conn->query("SELECT id,title,price,year FROM listings $where ORDER BY created_at DESC LIMIT 200");
$data = [];
while($r = $res->fetch_assoc()) $data[] = $r;
header('Content-Type: application/json');
echo json_encode($data);
