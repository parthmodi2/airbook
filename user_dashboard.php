<?php
require_once 'db.php';
if (!isset($_SESSION['user'])) { header('Location: index.php?tab=login'); exit; }
$uid = $_SESSION['user']['id'];

$stmt = $pdo->prepare('SELECT b.*, f.flight_code, f.origin, f.destination, f.depart_time, f.arrive_time FROM bookings b JOIN flights f ON b.flight_id = f.id WHERE b.user_id = :uid ORDER BY b.booked_at DESC');
$stmt->execute([':uid'=>$uid]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>My AirBook Space</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
</head>
<body>
  <div class="bg-spline">
    <iframe src="https://my.spline.design/airplanewithvolumetricclouds-UDyLX3Tb9fTrk3hyr7I1uM4K/" frameborder="0" width="100%" height="100%"></iframe>
  </div>
  <div class="container">
  <header>
    <h1>My Space</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="search.php">Search</a>
      <a href="logout.php">Logout</a>
    </nav>
  </header>

  <main>
    <section class="hero" data-aos="fade-up">
      <div class="hero-text">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></h2>
        <p>Your active bookings and upcoming flights in one place.</p>
      </div></section>

    <section class="card" data-aos="fade-up" data-aos-delay="80">
      <h3>Your bookings</h3>
      <?php if (!empty($bookings)): ?>
        <?php foreach($bookings as $b): ?>
          <div class="flight-card card" style="margin-top:12px;">
            <div class="row between">
              <div>
                <strong><?= htmlspecialchars($b['flight_code']) ?></strong>
                <div><?= htmlspecialchars($b['origin']) ?> → <?= htmlspecialchars($b['destination']) ?></div>
                <small>Dep: <?= $b['depart_time'] ?> | Arr: <?= $b['arrive_time'] ?></small>
              </div>
              <div class="price-area">
                <div>Seats: <?= intval($b['seats']) ?></div>
                <div>Total: ₹<?= number_format($b['total_price'],2) ?></div>
                <form method="POST" action="cancel_booking.php" onsubmit="return confirm('Cancel this booking?');" style="margin-top:8px;">
                  <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                  <button class="btn ghost small" type="submit">Cancel</button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No bookings yet. <a href="search.php">Search a flight</a>.</p>
      <?php endif; ?>
    </section>
  </main>
</div>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
