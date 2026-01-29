<?php
require_once 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php?tab=login');
    exit;
}

$booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
$from_admin = isset($_POST['from_admin']) ? true : false;

if ($booking_id <= 0) {
    header('Location: user_dashboard.php');
    exit;
}

if ($from_admin && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') {
    $stmt = $pdo->prepare('DELETE FROM bookings WHERE id = :id');
    $stmt->execute([':id'=>$booking_id]);
    header('Location: admin_actions.php');
    exit;
}

// user cancel: ensure ownership
$uid = $_SESSION['user']['id'];
$stmt = $pdo->prepare('DELETE FROM bookings WHERE id = :id AND user_id = :uid');
$stmt->execute([':id'=>$booking_id, ':uid'=>$uid]);

header('Location: user_dashboard.php');
exit;
?>
