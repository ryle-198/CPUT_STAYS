<?php
session_start();

$mysqli = require __DIR__ . "/database.php";

if($_SERVER["REQUEST_METHOD"]==="POST" && isset($_SESSION["user_id"])){

$sql = "SELECT StudNum FROM student WHERE IDNum = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$stmt->bind_result($stud_number);
$stmt->fetch();
$stmt->close();



$sql = "INSERT INTO booking (name, StartDate, EndDate, StudNum)/*need to get room type*/
        VALUES(?, ?, ?, ?, ?)";

$stmt = $mysqli ->stmt_init();

if(! $stmt -> prepare($sql)){
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sssss",
                $_POST["accommodation"],
                $_POST["room_type"],
                $_POST["starting_date"],
                $_POST["end_date"],
                $stud_number);

if($stmt->execute()){
    header("Location: profile.php");
    exit;

}else{

    error_log("Database insert failed: " . $stmt->error);

    echo "Sorry, we couldn't process your booking right now. Please try again later.";
   


    }
}
?>