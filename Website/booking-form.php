<?php
session_start();
$mysqli = require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION["user_id"])) {
    $studnum = $_SESSION["user_id"]; // student PK
    $accommodation_id = (int)$_POST["accommodation_id"];
    $room_id = (int)$_POST["room_id"];
    $start = $_POST["starting_date"];
    $end = $_POST["end_date"];
    $today = date("Y-m-d");

    // Fetch room price
    $sql = "SELECT PricePerRmType FROM rooms WHERE RmNum = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $stmt->bind_result($price);
    $stmt->fetch();
    $stmt->close();

    if (!$price) {
        die("Invalid room selection.");
    }

    // Calculate total cost (days * price)
    /*$days = (strtotime($end) - strtotime($start)) / (60 * 60 * 24);
    if ($days <= 0) {
        die("End date must be after start date.");
    }
    $total_cost = $days * $price;*/

    // Insert booking
    $sql = "INSERT INTO booking 
            (AccommodationID, StudNum, RmNum, BookingDate, StartDate, EndDate, PaymentStatus, TotCost, BkStatus) 
            VALUES (?, ?, ?, ?, ?, ?, 'Pending', ?, 'Pending')";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iiisssd", $accommodation_id, $studnum, $room_id, $today, $start, $end, $total_cost);

    if ($stmt->execute()) {
        // Decrease available rooms
        $sql2 = "UPDATE rooms SET AvailableRms = AvailableRms - 1 WHERE RmNum = ?";
        $stmt2 = $mysqli->prepare($sql2);
        $stmt2->bind_param("i", $room_id);
        $stmt2->execute();

        header("Location: profile.php");
        exit;
    } else {
        die("Error creating booking: " . $stmt->error);
    }
}
?>
