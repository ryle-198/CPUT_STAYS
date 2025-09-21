<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/database.php";
    
    // Fetch student info
    $sql = "SELECT * FROM student WHERE IDNum = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // Fetch bookings for this student
    if ($user) {
        $sql = "SELECT * FROM booking WHERE StudNum = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $user["StudNum"]);
        $stmt->execute();
        $bookings = $stmt->get_result();
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Booking Summary</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <header>
    <div class="logo">
	<a href="homepage.html" style="text-decoration: none; color: white;">
	<img src="logo.jpg" alt="cput logo" style="height: 40px; margin-right: 10px; vertical-align: middle;">
	CPUT STAYS
	</a>
	</div>
    <nav>
      <a href="profile.php">Profile</a>
    </nav>
  </header>
  <main class="form-container">
    
<h2>Booking Summary</h2>
<?php if ($bookings && $bookings->num_rows > 0): ?>
    <?php while ($b = $bookings->fetch_assoc()): ?>
        <div class="booking-card">

            <div class="summary-section">
              <h3>Accommodation Details</h3>
              <div class="summary-item">
                <span class="label">Accommodation:</span>
                <span class="value"><?= htmlspecialchars($b["ACCOMMODATION"]) ?></span>
              </div>
              <div class="summary-item">
                <span class="label">Room Type:</span>
                <span class="value"><?= htmlspecialchars($b["ROOM_TYPE"]) ?></span>
              </div>
              <div class="summary-item">
                <span class="label">Location:</span>
                <span class="value">Cape Town CBD</span>
              </div>
            </div>

            <div class="summary-section">
              <h3>Booking Period</h3>
              <div class="summary-item">
                <span class="label">Check-in Date:</span>
                <span class="value"><?= htmlspecialchars($b["StartDate"]) ?></span>
              </div>
              <div class="summary-item">
                <span class="label">Check-out Date:</span>
                <span class="value"><?= htmlspecialchars($b["EndDate"]) ?></span>
              </div>
            </div>

        </div> <!-- end .booking-card -->
    <?php endwhile; ?>
<?php else: ?>
    <p>No bookings found.</p>
<?php endif; ?>

  </main>
  <footer>
    <p>© 2025 CPUT STAYS. All rights reserved.</p>
  </footer>
</body>
</html>