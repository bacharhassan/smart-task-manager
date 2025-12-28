<?php
session_start();
require "db.php";

if (!empty($_POST["email"]) && !empty($_POST["password"])) {
  $email = trim($_POST["email"]);
  $password = $_POST["password"];

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = "Please enter a valid email.";
  } elseif (strlen($password) < 6) {
      $error = "Password must be at least 6 characters.";
  } else {
      $emailEsc = mysqli_real_escape_string($conn, $email);
      $query = "SELECT * FROM users WHERE email='$emailEsc' LIMIT 1";
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
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Smart Task Manager</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
  <div class="brand">Smart Task Manager</div>
  <div class="nav-right">
    <a class="btn btn-outline" href="register.php">Register</a>
    <button id="themeToggle" class="btn btn-outline" type="button">🌙 Dark</button>
  </div>
</div>

<div class="page">
  <div class="container">
    <h2 class="card-title">Login</h2>
    <form method="POST" id="loginForm">
      <input type="email" name="email" id="loginEmail" placeholder="Email" required>
      <input type="password" name="password" id="loginPassword" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>

    <p style="text-align:center; margin-top:10px;">
      Don't have an account? <a href="register.php">Register here</a>
    </p>

    <?php if (!empty($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
  </div>
</div>


  <script src="script.js"></script>
</body>
</html>
