<?php
session_start();
require "../db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit;
}

$user_id = (int)$_SESSION["user_id"];
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id <= 0) {
    header("Location: ../dashboard.php");
    exit;
}

$taskRes = mysqli_query($conn, "SELECT * FROM tasks WHERE id=$id AND user_id=$user_id LIMIT 1");
if (!$taskRes || mysqli_num_rows($taskRes) !== 1) {
    header("Location: ../dashboard.php");
    exit;
}
$task = mysqli_fetch_assoc($taskRes);

$catResult = mysqli_query($conn, "SELECT * FROM categories WHERE user_id=$user_id ORDER BY name ASC");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = mysqli_real_escape_string($conn, trim($_POST["title"]));
    $priority = $_POST["priority"] ?? "medium";
    $allowed = ["low","medium","high"];
    if (!in_array($priority, $allowed, true)) $priority = "medium";

    $due_date = !empty($_POST["due_date"]) ? $_POST["due_date"] : null;
    $dueSql = $due_date ? "'" . mysqli_real_escape_string($conn, $due_date) . "'" : "NULL";

    $category_id = !empty($_POST["category_id"]) ? (int)$_POST["category_id"] : "NULL";

    if (strlen($title) < 2) {
        $error = "Title must be at least 2 characters.";
    } else {
        $q = "UPDATE tasks
              SET title='$title',
                  priority='$priority',
                  due_date=$dueSql,
                  category_id=$category_id
              WHERE id=$id AND user_id=$user_id";
        mysqli_query($conn, $q);

        header("Location: ../dashboard.php");
        exit;
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Task</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="navbar">
  <div class="brand">Smart Task Manager</div>
  <div class="nav-right">
    <button id="themeToggle" class="btn btn-outline" type="button">🌙 Dark</button>
    <a class="btn btn-outline" href="../dashboard.php">Dashboard</a>
    <a class="btn btn-secondary" href="../logout.php">Logout</a>
  </div>
</div>

<div class="page">
  <div class="container">
    <h2 class="card-title">Edit Task</h2>

    <?php if (!empty($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>

    <form method="POST" id="editTaskForm">
      <label>Title</label>
      <input type="text" name="title" id="editTaskTitle" value="<?= htmlspecialchars($task['title']) ?>" required>

      <label>Category</label>
      <select name="category_id">
        <option value="">None</option>
        <?php while ($cat = mysqli_fetch_assoc($catResult)): ?>
          <option value="<?= $cat['id'] ?>" <?= ((int)$task['category_id'] === (int)$cat['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['name']) ?>
          </option>
        <?php endwhile; ?>
      </select>

      <label>Priority</label>
      <select name="priority">
        <option value="high" <?= $task['priority'] === 'high' ? 'selected' : '' ?>>High</option>
        <option value="medium" <?= $task['priority'] === 'medium' ? 'selected' : '' ?>>Medium</option>
        <option value="low" <?= $task['priority'] === 'low' ? 'selected' : '' ?>>Low</option>
      </select>

      <label>Due date</label>
      <input type="date" name="due_date" value="<?= htmlspecialchars($task['due_date'] ?? '') ?>">

      <button type="submit">Save Changes</button>
    </form>

    <div class="center mt-10">
      <a href="../dashboard.php">← Back to Dashboard</a>
    </div>
  </div>
</div>

<script src="../script.js"></script>
</body>
</html>