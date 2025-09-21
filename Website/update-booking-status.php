<?php
session_start();
$mysqli = require __DIR__ . "/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $booking_id = (int)$_POST["booking_id"];
    $action = $_POST["action"];
    
    if (!in_array($action, ["approve", "reject"])) {
        die("Invalid action.");
    }

    // Fetch room linked to this booking
    $sql = "SELECT RmNum, BkStatus FROM booking WHERE BookingID = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->bind_result($room_id, $current_status);
    $stmt->fetch();
    $stmt->close();

    if (!$room_id) {
        die("Booking not found.");
    }
    if ($current_status !== "Pending") {
        die("This booking is already processed.");
    }

    if ($action === "approve") {
        $new_status = "Confirmed";
    } else {
        $new_status = "Rejected";

        // Restore room availability if rejected
        $sql = "UPDATE rooms SET AvailableRms = AvailableRms + 1 WHERE RmNum = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        $stmt->close();
    }

    // Update booking status
    $sql = "UPDATE booking SET BkStatus = ? WHERE BookingID = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("si", $new_status, $booking_id);
    $stmt->execute();
    $stmt->close();

    header("Location: admins-booking.php");
    exit;
} else {
    die("Invalid request.");
}
?>
