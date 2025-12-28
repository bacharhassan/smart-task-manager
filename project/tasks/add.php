<?php
session_start();
require "../db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION["user_id"];

if (!empty($_POST["new_category"])) {
    $newCat = trim($_POST["new_category"]);
    if ($newCat !== '') {
        $queryCat = "INSERT INTO categories (user_id, name) VALUES ($user_id, '$newCat')";
        mysqli_query($conn, $queryCat);
    }
}


if (!empty($_POST["title"])) {
    $title = $_POST["title"];
    $priority = $_POST["priority"] ?? 'medium';
    $due_date = !empty($_POST["due_date"]) ? $_POST["due_date"] : null;
    $category_id = !empty($_POST["category_id"]) ? (int)$_POST["category_id"] : "NULL";

    $dueSql = $due_date ? "'$due_date'" : "NULL";

    $query = "INSERT INTO tasks (user_id, title, completed, priority, due_date, category_id) 
              VALUES ($user_id, '$title', 0, '$priority', $dueSql, $category_id)";
    mysqli_query($conn, $query);

    header("Location: ../dashboard.php");
    exit;
}

$catResult = mysqli_query($conn, "SELECT * FROM categories WHERE user_id=$user_id ORDER BY name ASC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Task</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="navbar">
  <div class="brand">Smart Task Manager</div>
  <div class="nav-right">
    <a class="btn btn-outline" href="../dashboard.php">Dashboard</a>
    <button id="themeToggle" class="btn btn-outline" type="button">🌙 Dark</button>
    <a class="btn btn-secondary" href="../logout.php">Logout</a>
  </div>
</div>

<div class="page">
  <div class="container">
    <h2 class="card-title">Add Task</h2>
    <form method="POST" id="addTaskForm">
      <input type="text" name="title" id="taskTitle" placeholder="Task title" required>
      <label for="category_id">Category:</label>
      <select name="category_id" id="category_id">
        <option value="">None</option>
        <?php while ($cat = mysqli_fetch_assoc($catResult)): ?>
          <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
        <?php endwhile; ?>
      </select>
      <label for="new_category">Or add a new category:</label>
      <input type="text" name="new_category" id="new_category" placeholder="e.g. School, Work">
      <label for="priority">Priority:</label>
      <select name="priority" id="priority">
        <option value="low">Low</option>
        <option value="medium" selected>Medium</option>
        <option value="high">High</option>
      </select>
      <label for="due_date">Due date:</label>
      <input type="date" name="due_date" id="due_date">

      <button type="submit">Add Task</button>
    </form>
    <a class="add-back" href="../dashboard.php">← Back to Dashboard</a>
  </div>
</div>  
  </div>
  <script src="../script.js"></script>
</body>
</html>