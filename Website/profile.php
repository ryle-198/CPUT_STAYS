<?php
session_start();

if (isset($_SESSION["user_id"]) && $_SESSION["role"] === "student") {
    $mysqli = require __DIR__ . "/database.php";
    
    // Fetch student info
    $sql = "SELECT * FROM student WHERE StudNum = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Student Profile</title>
  <link rel="stylesheet" href="style.css"/>
  <style>
    .logout-button {
      margin-top: 20px;
      padding: 10px 15px;
      background: #0073e6;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .logout-button:hover { background: #005bb5; }
  </style>
</head>
<body>
  <header>
    <div class="logo">
      <a href="homepage.php" style="text-decoration: none; color: white;">
        <img src="logo.jpg" alt="cput logo" style="height: 40px; margin-right: 10px; vertical-align: middle;">
        CPUT STAYS
      </a>
    </div>
    <nav>
      <a href="homepage.php">Home</a>
    </nav>
  </header>

  <main class="form-container">
    <?php if (isset($user)): ?>
      <h1>Welcome, <?= htmlspecialchars($user["FirstName"]) ?></h1>

      <section>
        <h2>Your Info</h2>
        <p class = "user-info">ID NUMBER: <?= htmlspecialchars($user["IDNum"]) ?></p>
        <p class = "user-info">First Name: <?= htmlspecialchars($user["FirstName"]) ?></p>
        <p class = "user-info">Last Name: <?= htmlspecialchars($user["LastName"]) ?></p>
        <p class = "user-info">Student Number: <?= htmlspecialchars($user["StudNum"]) ?></p>
        <p class = "user-info">Email: <?= htmlspecialchars($user["Email"]) ?></p>
        <p class = "user-info">Cell: <?= htmlspecialchars($user["CellNumr"]) ?></p>
        <p class = "user-info">Enrollment Year: <?= htmlspecialchars($user["EnrollYr"]) ?></p>
      </section>

      <div><a href ="booking-summary.php">View Your Bookings</a></div>
      <div><a href ="payment-summary.html">View Your Payments</a></div>
      <div><a href ="logout.php"><button class="logout-button">Logout</button></a></div>
    <?php else: ?>
      <p>Please log in to view your profile.</p>
    <?php endif; ?>
  </main>

  <footer>
    <p>© 2025 CPUT STAYS. All rights reserved.</p>
  </footer>
</body>
</html>
