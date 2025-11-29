<?php
session_start();
require "../db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit;
}

if (!empty($_POST["title"])) {
    $title = $_POST["title"];
    $user_id = $_SESSION["user_id"];

    $query = "INSERT INTO tasks (user_id, title, completed) VALUES ($user_id, '$title', 0)";
    mysqli_query($conn, $query);

    header("Location: ../dashboard.php");
    exit;
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add Task</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <div class="container" style="max-width:520px;">
    <h2>Add Task</h2>
    <form method="POST" action="">
      <input type="text" name="title" placeholder="Task title" required>
      <button type="submit">Add</button>
    </form>

    <a class="add-back" href="../dashboard.php">← Back to Dashboard</a>
  </div>
</body>
</html>