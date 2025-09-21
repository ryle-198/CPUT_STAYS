<?php
session_start();
$mysqli = require __DIR__ . "/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "student") {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $booking_id = (int)$_POST["booking_id"];
    $room_id = (int)$_POST["room_id"];
    $studnum = $_SESSION["user_id"];

    // Verify booking belongs to this student
    $sql = "SELECT BkStatus FROM booking WHERE BookingID = ? AND StudNum = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $booking_id, $studnum);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    $stmt->close();

    if (!$status) {
        die("Booking not found.");
    }
    if ($status === "Cancelled") {
        die("This booking is already cancelled.");
    }

    // Mark booking as cancelled
    $sql = "UPDATE booking SET BkStatus = 'Cancelled' WHERE BookingID = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->close();

    // Restore room availability
    $sql = "UPDATE rooms SET AvailableRms = AvailableRms + 1 WHERE RmNum = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $stmt->close();

    header("Location: profile.php");
    exit;
} else {
    die("Invalid request.");
}
?>
