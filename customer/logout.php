<?php
require_once '../functions.php';
if (isCustomerLoggedIn()) unset($_SESSION['customer_id']);
redirect('../index.php');
