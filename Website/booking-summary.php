<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/database.php";
    
    // Fetch student info
    $sql = "SELECT * FROM student WHERE ID_NUM = ?";
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
        $stmt->bind_param("s", $user["STUD_NUMBER"]);
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
<style>
    .summary-section {
      margin-bottom: 2em;
      padding: 1.5em;
      background-color: #f8f9fa;
      border-radius: 8px;
      border-left: 4px solid #0054a6;
    }
    
    .summary-section h3 {
      color: #0054a6;
      margin-bottom: 1em;
      font-size: 1.2em;
    }
    
    .summary-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.8em;
      padding: 0.5em 0;
      border-bottom: 1px solid #e9ecef;
    }
    
    .summary-item:last-child {
      border-bottom: none;
      margin-bottom: 0;
    }
    
    .summary-item.total {
      font-weight: bold;
      font-size: 1.1em;
      background-color: #e3f2fd;
      padding: 1em;
      margin-top: 1em;
      border-radius: 5px;
      border-bottom: none;
    }
    
    .label {
      font-weight: 600;
      color: #495057;
    }
    
    .value {
      color: #212529;
      font-weight: 500;
    }
    
    .status-success {
      background-color: #d4edda;
      border: 1px solid #c3e6cb;
      color: #155724;
      padding: 1.5em;
      border-radius: 5px;
      text-align: center;
    }
    
    .status-success strong {
      display: block;
      font-size: 1.2em;
      margin-bottom: 0.5em;
    }
    
    .info-list {
      list-style-type: none;
      padding: 0;
    }
    
    .info-list li {
      padding: 0.5em 0;
      border-bottom: 1px solid #e9ecef;
      position: relative;
      padding-left: 1.5em;
    }
    
    .info-list li:before {
      content: "•";
      color: #0054a6;
      font-weight: bold;
      position: absolute;
      left: 0;
    }
    
    .info-list li:last-child {
      border-bottom: none;
    }
    
    .action-buttons {
      display: flex;
      gap: 1em;
      justify-content: center;
      margin-top: 2em;
      flex-wrap: wrap;
    }
    
    .primary-btn, .secondary-btn {
      padding: 0.8em 1.5em;
      border-radius: 5px;
      text-decoration: none;
      font-weight: 600;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s;
      text-align: center;
    }
    
    .primary-btn {
      background-color: #0054a6;
      color: white;
      font-size: 1.1em;
    }
    
    .primary-btn:hover {
      background-color: #003e80;
    }
    
    .secondary-btn {
      background-color: #6c757d;
      color: white;
    }
    
    .secondary-btn:hover {
      background-color: #545b62;
    }

    .booking-card {
        margin-top:20px;
  margin-bottom: 2.5em;
  padding: 1.5em;
  background: #ffffff;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(44, 54, 60, 0.58);
    }
</style>
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
                <span class="value"><?= htmlspecialchars($b["STARTING_DATE"]) ?></span>
              </div>
              <div class="summary-item">
                <span class="label">Check-out Date:</span>
                <span class="value"><?= htmlspecialchars($b["END_DATE"]) ?></span>
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