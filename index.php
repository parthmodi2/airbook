<?php
require_once 'db.php';
require_once 'functions.php';

$tab = $_GET['tab'] ?? 'search';
$loggedIn = isset($_SESSION['user']);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>AirBook — Flight booking</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body data-logged-in="<?= $loggedIn ? '1' : '0' ?>">

  <div class="container">
    <header>
      <h1>AirBook ©</h1>
      <nav>
        <a href="?tab=search" class="<?= $tab==='search'?'active':'' ?>">Search Flights</a>
        <?php if(!$loggedIn): ?>
          <a href="?tab=login" class="<?= $tab==='login'?'active':'' ?>">Login/Register</a>
        <?php else: ?>
          <a href="user_dashboard.php">My Space</a>
          <a href="logout.php">Logout</a>
        <?php endif; ?>
        <a href="?tab=admin" class="<?= $tab==='admin'?'active':'' ?>">Admin</a>
      </nav>
    </header>

    <main>
      <section class="hero" data-aos="fade-up">
        <div class="hero-text">
          <h2>Fly smarter with AirBook</h2>
          <p>Minimal, smooth booking experience with interactive aircraft visuals.</p>
          <div class="hero-actions">
            <?php if(!$loggedIn): ?>
              <button type="button" class="btn takeoff-btn" id="takeoffLoginBtn" data-target="login">
                <span class="plane-icon">✈</span>
                Login &amp; Take Off
              </button>
            <?php else: ?>
              <button type="button" class="btn takeoff-btn" id="takeoffLoginBtn" data-target="dashboard">
                <span class="plane-icon">✈</span>
                Go to My Space
              </button>
            <?php endif; ?>
            <a href="?tab=search" class="btn ghost">Search flights</a>
          </div>
        </div></section>

      <?php if ($tab === 'search'): ?>
        <section class="card" data-aos="fade-up" data-aos-delay="60">
          <h2>Search flights</h2>
          <form id="searchForm" method="GET" action="search.php">
            <div class="row">
              <input name="origin" placeholder="Origin (e.g. Mumbai)" />
              <input name="destination" placeholder="Destination (e.g. Delhi)" />
              <input name="date" type="date" />
              <button type="submit" class="btn">Search</button>
            </div>
          </form>
        </section>
      <?php elseif ($tab === 'login'): ?>
        <section class="card" id="login-card" data-aos="fade-up" data-aos-delay="60">
          <h2>Login</h2>
          <form method="POST" action="login.php">
            <input name="email" type="email" required placeholder="email" />
            <input name="password" type="password" required placeholder="password" />
            <div class="row">
              <button class="btn" type="submit">Login</button>
              <a class="btn ghost" href="register.php">Register</a>
            </div>
          </form>
        </section>
      <?php elseif ($tab === 'admin'): ?>
        <section class="card" data-aos="fade-up" data-aos-delay="60">
          <h2>Admin login</h2>
          <form method="POST" action="admin.php">
            <input name="email" type="email" required placeholder="email" />
            <input name="password" type="password" required placeholder="password" />
            <div class="row">
              <button class="btn" type="submit">Enter Admin</button>
            </div>
            <p class="muted">Admin seeded: skips@gmail.com / 12345</p>
          </form>
        </section>
      <?php endif; ?>
    </main>

    <footer>
      <p>© <?= date('Y') ?> AirBook © — All Rights Reserved</p>
    </footer>
  </div>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
