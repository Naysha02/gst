<?php
require_once '../functions.php';
$budget_min = intval($_GET['budget_min'] ?? 0);
$budget_max = intval($_GET['budget_max'] ?? 0);
$fuel = $conn->real_escape_string($_GET['fuel'] ?? '');
$trans = $conn->real_escape_string($_GET['trans'] ?? '');
$where = " WHERE status='published' ";
if ($budget_min) $where .= " AND price >= $budget_min ";
if ($budget_max) $where .= " AND price <= $budget_max ";
if ($fuel) $where .= " AND fuel_type = '$fuel' ";
if ($trans) $where .= " AND transmission = '$trans' ";
$res = $conn->query("SELECT * FROM listings $where ORDER BY created_at DESC LIMIT 10");
$items = [];
while($r = $res->fetch_assoc()) $items[] = $r;
header('Content-Type: application/json');
echo json_encode(['results'=>$items, 'note'=>'Basic rule-based finder. Integrate LLM/ML for smarter answers.']);
