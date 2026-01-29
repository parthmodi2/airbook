<?php
require_once 'db.php';
require_once 'functions.php';

if (!isset($_SESSION['user'])) {
    // redirect to login with return
    header('Location: index.php?tab=login');
    exit;
}

$flight_code = $_POST['flight_code'] ?? '';
$depart_time = $_POST['depart_time'] ?? '';

// For demo: if flight_code exists in DB use it, else create a temporary generated record then book
$stmt = $pdo->prepare('SELECT * FROM flights WHERE flight_code = :code LIMIT 1');
$stmt->execute([':code'=>$flight_code]);
$flight = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$flight) {
    // create a simple flight row so booking has a flight_id
    $ins = $pdo->prepare('INSERT INTO flights (flight_code, origin, destination, depart_time, arrive_time, duration_minutes, price) VALUES (:fc,:o,:d,:dt,:at,:du,:pr)');
    $origin = 'Unknown'; $destination = 'Unknown';
    $depart = $depart_time ?: date('Y-m-d H:i:s', strtotime('+1 day'));
    $arr = date('Y-m-d H:i:s', strtotime($depart . ' +2 hours'));
    $ins->execute([':fc'=>$flight_code,':o'=>$origin,':d'=>$destination,':dt'=>$depart,':at'=>$arr,':du'=>120,':pr'=>2500]);
    $flight_id = $pdo->lastInsertId();
    $price = 2500;
} else {
    $flight_id = $flight['id'];
    $price = $flight['price'];
}

// create booking
$book = $pdo->prepare('INSERT INTO bookings (user_id, flight_id, seats, total_price) VALUES (:uid,:fid,1,:tp)');
$book->execute([':uid'=>$_SESSION['user']['id'],':fid'=>$flight_id,':tp'=>$price]);

header('Location: user_dashboard.php?booked=1');
exit;
?>