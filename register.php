<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    if (!$name || !$email || !$pass) {
        header('Location: index.php?tab=login&err=reg'); exit;
    }
    $stmt = $pdo->prepare('INSERT INTO users (name,email,password) VALUES (:n,:e,:p)');
    try {
        $stmt->execute([':n'=>$name,':e'=>$email,':p'=>md5($pass)]);
        header('Location: index.php?tab=login&registered=1');
    } catch (Exception $e) {
        header('Location: index.php?tab=login&err=dup');
    }
}
?>

<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="assets/css/style.css"></head><body>
<div class="container small"><h2>Register</h2><form method="POST" action="register.php"><input name="name" placeholder="Full name" required><input name="email" type="email" placeholder="Email" required><input name="password" type="password" placeholder="Password" required><div class="row"><button class="btn" type="submit">Create</button><a class="btn ghost" href="index.php?tab=login">Back</a></div></form></div></body></html>
