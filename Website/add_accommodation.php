<?php
session_start();
$mysqli = require __DIR__ . "/database.php";

// Ensure admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("You must be logged in as an admin to add accommodation.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validation
    if (empty($_POST['name'])) die("Accommodation name is required");
    if (empty($_POST['address'])) die("Address is required");
    if (empty($_POST['contact_num'])) die("Contact number is required");
    if (empty($_POST['amenities'])) die("Amenities are required");

    // Gather data
    $name = $_POST['name'];
    $address = $_POST['address'];
    $contact_num = $_POST['contact_num'];
    $amenities = $_POST['amenities'];

    // Insert accommodation and link it directly to this admin
    $sql = "INSERT INTO accommodation (AdminID, Name, Address, ContactNum, Amenities)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("issss", $_SESSION['user_id'], $name, $address, $contact_num, $amenities);

    if (!$stmt->execute()) {
        die("Error inserting accommodation: " . $stmt->error);
    }

    echo "Accommodation added and linked to your account successfully!";
    // Optionally redirect:
    // header("Location: dashboard.php");
    // exit;
}
?>
