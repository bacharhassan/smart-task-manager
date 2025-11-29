<?php
session_start();
require "db.php";
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}
$user_id = $_SESSION["user_id"];

$result = mysqli_query($conn, "SELECT * FROM tasks WHERE user_id=$user_id ORDER BY id DESC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard - Smart Task Manager</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <div class="navbar">
    Smart Task Manager
    <a href="logout.php" class="btn-secondary" style="padding:8px 14px; font-size:14px;">Logout</a>
  </div>

  <div class="container" style="max-width:760px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
      <h2>Your Tasks</h2>
      <a href="tasks/add.php" style="text-decoration:none;">
        <button style="width:auto; padding:10px 14px; border-radius:8px;">➕ Add Task</button>
      </a>
    </div>

    <?php while ($task = mysqli_fetch_assoc($result)): ?>
      <div class="task-card">
        <div>
          <div class="task-title"><?= htmlspecialchars($task["title"]) ?></div>
          <?php if ($task["completed"]): ?>
            <div style="margin-top:6px;"><span class="completed">✔ Completed</span></div>
          <?php endif; ?>
        </div>

        <div class="task-actions" style="display:flex; gap:8px;">
          <?php if (!$task["completed"]): ?>
            <a href="tasks/complete.php?id=<?= $task['id'] ?>" style="background:#4CAF50;">Complete</a>
          <?php endif; ?>
          <a href="tasks/delete.php?id=<?= $task['id'] ?>" style="background:#e74c3c;">Delete</a>
        </div>
      </div>
    <?php endwhile; ?>

  </div>

</body>
</html>

