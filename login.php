<?php
require_once 'db.php';

$email = $_POST['email'] ?? '';
$pass = $_POST['password'] ?? '';

if (!$email || !$pass) {
    header('Location: index.php?tab=login');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
$stmt->execute([':email'=>$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $user['password'] === md5($pass)) {
    // store minimal user info in session
    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role']
    ];
    header('Location: user_dashboard.php');
    exit;
}

header('Location: index.php?tab=login&err=1');
exit;
?>