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
  <title>Student Profile</title>
  <link rel="stylesheet" href="style.css"/>
</head>
<body>
  <header>
    <div class="logo"><a href="homepage.html" style="text-decoration: none; color: white;">
      <img src="logo.jpg" alt="cput logo" style="height: 40px; margin-right: 10px; vertical-align: middle;">
    CPUT STAYS </a></div>
    <nav>
      <a href="homepage.html">Home</a>
      <a href="booking.html">Booking</a>
      <a href="payment.html">Payment</a>
    </nav>
  </header>
    <main class="form-container">
      <?php if (isset($user)): ?>
        <h1>Welcome, <?= htmlspecialchars($user["FirstName"])?></h1>
        <section>
          <h2>Your Info</h2>
          <p class = "user-info"><strong>ID NUMBER:</strong><?= htmlspecialchars($user["IDNum"])?></p>
          <p class = "user-info"><strong>First Name:</strong><?= htmlspecialchars($user["FirstName"])?></p>
          <p class = "user-info"><strong>Last Name:</strong><?= htmlspecialchars($user["LastName"])?></p>
          <p class = "user-info"><strong>Student Number:</strong><?= htmlspecialchars($user["StudNum"])?></p>
          <p class = "user-info"><strong>Email:</strong><?= htmlspecialchars($user["Email"])?></p>
          <p class = "user-info"><strong>Cell:</strong> <?=htmlspecialchars($user["CellNumr"])?> </p>
          <p class = "user-info"><strong>Enrollment Year:</strong><?= htmlspecialchars($user["EnrollYr"])?></p>
        </section>
        <div><a href ="booking-summary.php">View Your Bookings</a></div>
        <div><a href ="payment-summary.html">View Your Payments</a></div>
        <div><a href = "logout.php"><button class = "logout-button">Logout</button></a></div>
  </main>
  <footer>
    <p>© 2025 CPUT STAYS. All rights reserved.</p>
  </footer>
  <?php else: ?>
  <?php endif; ?>
</body>
</html>