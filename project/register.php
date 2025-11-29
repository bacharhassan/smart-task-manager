<?php
require "db.php";

if (!empty($_POST["email"]) && !empty($_POST["password"])) {
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Error: Could not register user.";
    }
}
?>

<link rel="stylesheet" href="style.css">

<div class="container">
    <h2>Register</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Register</button>
    </form>

    <?php if (!empty($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
</div>
