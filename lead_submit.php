<?php
require_once 'functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php');

$listing_id = intval($_POST['listing_id'] ?? 0);
$dealer_id  = intval($_POST['dealer_id'] ?? 0);
$name       = $conn->real_escape_string($_POST['name'] ?? '');
$email      = $conn->real_escape_string($_POST['email'] ?? '');
$phone      = $conn->real_escape_string($_POST['phone'] ?? '');
$msg        = $conn->real_escape_string($_POST['message'] ?? '');

// 1️⃣ Save lead in DB
$stmt = $conn->prepare("INSERT INTO leads (listing_id,dealer_id,customer_name,customer_email,customer_phone,message) VALUES (?,?,?,?,?,?)");
$stmt->bind_param('iissss', $listing_id, $dealer_id, $name, $email, $phone, $msg);
$stmt->execute();

// 2️⃣ Get dealer info for WhatsApp
$dealer = $conn->query("SELECT phone,name FROM dealers WHERE id=$dealer_id")->fetch_assoc();
$dealer_phone = preg_replace('/\D/', '', $dealer['phone']); // keep digits only
$dealer_name  = $dealer['name'];

// 3️⃣ Build WhatsApp message
$wa_msg = urlencode("Hi $dealer_name, You have a new enquiry from $name.\n\nMessage: $msg\nPhone: $phone\nEmail: $email\nListing: https://yourdomain.com/listings/view.php?id=$listing_id");

// 4️⃣ WhatsApp deep link
$wa_link = "https://wa.me/$dealer_phone?text=$wa_msg";

// 5️⃣ Set notice for user
$_SESSION['notice'] = "Enquiry sent to dealer.";

// 6️⃣ Redirect user to WhatsApp (or listing page if you prefer)
echo "<script>window.location.href='$wa_link';</script>";
exit;
