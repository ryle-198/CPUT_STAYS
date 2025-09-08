<?php
$is_invalid=false;
if($_SERVER["REQUEST_METHOD"] === "POST"){

    $mysqli = require __DIR__ . "/database.php";

    $sql = sprintf("SELECT * FROM student
                    WHERE email ='%s'",
                    $mysqli->real_escape_string($_POST["email"]));

              $result = $mysqli->query($sql);

              $user = $result->fetch_assoc();

                    if($user){

                        if(password_verify($_POST["password"], $user["PASSWORD_HASH"])){

                            session_start();

                            session_regenerate_id();

                            $_SESSION["user_id"]= $user["ID_NUM"];

                            header("Location: homepage.html");
                            //this needs to be changed to a different location
                            exit;

                        }

                    }
                    $is_invalid=true;

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <header>
    <div class="logo">CPUT STAYS</div>
    <nav>
      <a href="index.html">Home</a>
      <a href="register.html">Register</a>
    </nav>
  </header>

  <main class="form-container">

    <h2>Login</h2>
    <?php if($is_invalid): ?>
      <em>Invalid Login</em>
      <?php endif; ?>

    <form method ="post">
      <label for ="email">Email</label>
      <input type="email" name="email" id="email"  
      value="<?= htmlspecialchars($_POST["email"] ?? "")?>" required/>

      <label for ="password">Password</label>
      <input type="password" name="password" id ="password" required />


      <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.html">Register here</a></p>
  </main>
</body>
</html>
