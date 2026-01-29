<?php
require_once 'db.php';
require_once 'functions.php';
if (!is_admin()) { header('Location: index.php'); exit; }

// actions: remove user, remove flight
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove_user'])) {
        $uid = intval($_POST['user_id']);
        $pdo->prepare('DELETE FROM users WHERE id = :id AND role != "admin"')->execute([':id'=>$uid]);
    }
    if (isset($_POST['remove_flight'])) {
        $fid = intval($_POST['flight_id']);
        $pdo->prepare('DELETE FROM flights WHERE id = :id')->execute([':id'=>$fid]);
    }
}

$users = $pdo->query('SELECT id,name,email,role,created_at FROM users ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
$flights = $pdo->query('SELECT * FROM flights ORDER BY depart_time DESC LIMIT 300')->fetchAll(PDO::FETCH_ASSOC);
$bookings = $pdo->query('SELECT b.*, u.name AS user_name, f.flight_code, f.origin, f.destination, f.depart_time FROM bookings b JOIN users u ON b.user_id = u.id JOIN flights f ON b.flight_id = f.id ORDER BY b.booked_at DESC LIMIT 300')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Panel — AirBook</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="bg-spline">
    <iframe src="https://my.spline.design/airplanewithvolumetricclouds-UDyLX3Tb9fTrk3hyr7I1uM4K/" frameborder="0" width="100%" height="100%"></iframe>
  </div>
  <div class="container">
<header><h1>Admin Panel</h1><a href="index.php">Home</a></header>

<section class="card">
<h2>Users</h2>
<table class="admin-table">
<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>When</th><th>Action</th></tr></thead>
<tbody>
<?php foreach($users as $u): ?>
<tr>
<td><?= $u['id'] ?></td>
<td><?= htmlspecialchars($u['name']) ?></td>
<td><?= htmlspecialchars($u['email']) ?></td>
<td><?= $u['role'] ?></td>
<td><?= $u['created_at'] ?></td>
<td>
<form method="POST" style="display:inline;">
  <input type="hidden" name="user_id" value="<?= $u['id'] ?>" />
  <?php if ($u['role'] !== 'admin'): ?>
  <button onclick="return confirm('Remove user?')" name="remove_user" class="btn small" type="submit">Remove</button>
  <?php endif; ?>
</form>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</section>

<section class="card">
<h2>Flights</h2>
<table class="admin-table">
<thead><tr><th>ID</th><th>Code</th><th>Route</th><th>Depart</th><th>Price</th><th>Action</th></tr></thead>
<tbody>
<?php foreach($flights as $f): ?>
<tr>
<td><?= $f['id'] ?></td>
<td><?= htmlspecialchars($f['flight_code']) ?></td>
<td><?= htmlspecialchars($f['origin']) ?> → <?= htmlspecialchars($f['destination']) ?></td>
<td><?= $f['depart_time'] ?></td>
<td>₹<?= number_format($f['price'],2) ?></td>
<td>
<form method="POST" style="display:inline;">
  <input type="hidden" name="flight_id" value="<?= $f['id'] ?>" />
  <button onclick="return confirm('Delete flight?')" name="remove_flight" class="btn small" type="submit">Remove</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</section>

<section class="card">
<h2>Bookings</h2>
<table class="admin-table">
<thead><tr><th>ID</th><th>User</th><th>Flight</th><th>Route</th><th>Depart</th><th>Seats</th><th>Total</th><th>Action</th></tr></thead>
<tbody>
<?php foreach($bookings as $b): ?>
<tr>
<td><?= $b['id'] ?></td>
<td><?= htmlspecialchars($b['user_name']) ?></td>
<td><?= htmlspecialchars($b['flight_code']) ?></td>
<td><?= htmlspecialchars($b['origin']) ?> → <?= htmlspecialchars($b['destination']) ?></td>
<td><?= $b['depart_time'] ?></td>
<td><?= intval($b['seats']) ?></td>
<td>₹<?= number_format($b['total_price'],2) ?></td>
<td>
  <form method="POST" style="display:inline;" action="cancel_booking.php" onsubmit="return confirm('Cancel this booking?');">
    <input type="hidden" name="booking_id" value="<?= $b['id'] ?>" />
    <input type="hidden" name="from_admin" value="1" />
    <button class="btn small" type="submit">Cancel</button>
  </form>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</section>

</div>
</body>
</html>
