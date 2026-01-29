<?php
require_once 'functions.php';

$origin = trim($_GET['origin'] ?? '');
$destination = trim($_GET['destination'] ?? '');
$date = $_GET['date'] ?? '';

$results = search_flights($origin, $destination, $date);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Search results</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
</head>
<body>
  <div class="bg-spline">
    <iframe src="https://my.spline.design/airplanewithvolumetricclouds-UDyLX3Tb9fTrk3hyr7I1uM4K/" frameborder="0" width="100%" height="100%"></iframe>
  </div>
  <div class="container">
  <div class="topbar card" data-aos="fade-down">
    <a href="index.php">← Back</a>
    <h2>Results for <?= htmlspecialchars(($origin?:'Any')) ?> → <?= htmlspecialchars(($destination?:'Any')) ?> <?= $date? 'on '.$date : '' ?></h2>
  </div>

  <div class="results">
    <?php if(!$results): ?>
      <div class="card">No flights found.</div>
    <?php else: ?>
      <?php foreach($results as $f): ?>
      <div class="flight-card card" data-aos="fade-up">
        <div class="row between">
          <div>
            <h3><?= htmlspecialchars($f['flight_code']) ?> — <?= htmlspecialchars($f['origin']) ?> → <?= htmlspecialchars($f['destination']) ?></h3>
            <p><?= date('M j, Y H:i', strtotime($f['depart_time'])) ?> — <?= date('H:i', strtotime($f['arrive_time'])) ?> (<?= intval($f['duration_minutes']) ?>m)</p>
          </div>
          <div class="price-area">
            <p class="price">₹<?= number_format($f['price'],2) ?></p>
            <form method="POST" action="book.php">
              <input type="hidden" name="flight_code" value="<?= htmlspecialchars($f['flight_code']) ?>" />
              <input type="hidden" name="depart_time" value="<?= htmlspecialchars($f['depart_time']) ?>" />
              <button class="btn" type="submit">Book</button>
            </form>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>