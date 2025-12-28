<?php
require "db.php";

if (!empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["confirm_password"])) {
    $email = trim($_POST["email"]);
    $passPlain = $_POST["password"];
    $confirm = $_POST["confirm_password"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email.";
    } elseif (strlen($passPlain) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($passPlain !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $emailEsc = mysqli_real_escape_string($conn, $email);
        $password = password_hash($passPlain, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (email, password) VALUES ('$emailEsc', '$password')";
        if (mysqli_query($conn, $query)) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Error: Could not register user (email may already exist).";
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Smart Task Manager</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
  <div class="brand">Smart Task Manager</div>
  <div class="nav-right">
    <a class="btn btn-outline" href="index.php">Login</a>
    <button id="themeToggle" class="btn btn-outline" type="button">🌙 Dark</button>
  </div>
</div>

<div class="page">
  <div class="container">
    <h2 class="card-title">Register</h2>
    <form method="POST" id="registerForm">
    <input type="email" name="email" id="registerEmail" placeholder="Email" required>
<input type="password" name="password" id="registerPassword" placeholder="Password (min 6 chars)" required>
<input type="password" name="confirm_password" id="registerConfirmPassword" placeholder="Confirm password" required>
<button type="submit">Register</button>
    </form>
    <?php if (!empty($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
  </div>
</div>
  

  <script src="script.js"></script>
</body>
</html>