<?php
session_start();
require "db.php";

if (!empty($_POST["email"]) && !empty($_POST["password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Invalid email or password";
    }
}
?>


<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login - Smart Task Manager</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Smart Task Manager</h2>

    <form method="POST" action="">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>

    <p style="text-align:center; margin-top:10px;">
      Don't have an account? <a href="register.php">Register here</a>
    </p>

    <?php if (!empty($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
  </div>
</body>
</html>
