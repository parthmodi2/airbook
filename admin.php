<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $pass = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :e AND role = "admin" LIMIT 1');
    $stmt->execute([':e'=>$email]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($u && $u['password'] === md5($pass)) {
        $_SESSION['user'] = ['id'=>$u['id'],'name'=>$u['name'],'email'=>$u['email'],'role'=>'admin'];
        header('Location: admin_actions.php'); exit;
    } else {
        header('Location: index.php?tab=admin&err=1'); exit;
    }
}
?>