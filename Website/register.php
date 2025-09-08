<?php

if(empty($_POST["first_name"])){
    die("First Name is required");
}

if(empty($_POST["last_name"])){
    die("Last Name is required");

}

if(empty($_POST["stud_number"])){
   die("Student Number is required"); 
}

if(! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
    die("Email is required");
}

if(empty($_POST["cell_number"])){
    die("Cell Number is required");
}

if(empty($_POST["id_num"])){
    die("ID number is required");
}

if(empty($_POST["enrollment_year"])){
    die("Enrolment year is required");
}

if(strlen($_POST["password"])<8){
    die("Password must be at least 8 characters long");
}

if(empty($_POST["funding"])){
    die("Funding is required");
}

//keeping password saved
$password_hash=password_hash($_POST["password"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO student (stud_number, first_name, last_name, id_num, email, cell_number, enrollment_year, password_hash, funding)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->stmt_init();

if(! $stmt->prepare($sql)){
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sssssssss",
                  $_POST["stud_number"],
                  $_POST["first_name"],
                  $_POST["last_name"],
                  $_POST["id_num"],
                  $_POST["email"],
                  $_POST["cell_number"],
                  $_POST["enrollment_year"],
                  $password_hash,
                  $_POST["funding"]);

if($stmt->execute()){
    
    header("Location: login.php");
    exit;

}else{

    if($mysqli->errno === 1062){
        die("email already taken");
    }else{
        die($mysqli->error . " " . $mysqli->errno);
    }
}
