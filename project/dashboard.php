<?php
session_start();
require "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$catResult = mysqli_query($conn, "SELECT * FROM categories WHERE user_id=$user_id ORDER BY name ASC");
$filterCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$filterPriority = isset($_GET['priority']) && $_GET['priority'] !== '' ? strtolower($_GET['priority']) : 'all';
$allowedPriorities = ['all','low','medium','high'];

$sql = "SELECT t.*, c.name AS category_name 
        FROM tasks t 
        LEFT JOIN categories c ON t.category_id = c.id
        WHERE t.user_id=$user_id";

if ($filterCategory > 0) {
    $sql .= " AND t.category_id = $filterCategory";
}

if ($filterPriority !== 'all') {
  $sql .= " AND t.priority = '$filterPriority'";
}
if (!in_array($filterPriority, $allowedPriorities)) {
    $filterPriority = 'all';
}
$stats = mysqli_query($conn, "SELECT 
  COUNT(*) AS total,
  SUM(completed=1) AS done,
  SUM(completed=0) AS pending
  FROM tasks WHERE user_id=$user_id");
$st = mysqli_fetch_assoc($stats);

$sql .= " ORDER BY t.due_date IS NULL, t.due_date ASC, t.id DESC";

$result = mysqli_query($conn, $sql);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Smart Task Manager</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
  <div class="brand">Smart Task Manager</div>
  <div class="nav-right">
    <a class="btn btn-outline" href="tasks/add.php">Add Task</a>
    <button id="themeToggle" class="btn btn-outline" type="button">🌙 Dark</button>
    <a class="btn btn-secondary" href="logout.php">Logout</a>
  </div>
</div>

<div class="page">
  <div class="container">
    <div class="dashboard-header">
      <h2>Your Tasks</h2>
      <div class="dashboard-actions">
        <a href="tasks/add.php">
          <button type="button">➕ Add Task</button>
        </a>
      </div>
    </div>

    <div class="stats">
  <div class="stat-card"><div class="stat-num"><?= $st['total'] ?></div><div class="stat-label">Total</div></div>
  <div class="stat-card"><div class="stat-num"><?= $st['done'] ?></div><div class="stat-label">Completed</div></div>
  <div class="stat-card"><div class="stat-num"><?= $st['pending'] ?></div><div class="stat-label">Pending</div></div>
</div>

    <form method="GET" class="filter-form">
  <label for="categoryFilter">Category:</label>
  <select name="category" id="categoryFilter" onchange="this.form.submit()">
    <option value="0">All</option>
    <?php mysqli_data_seek($catResult, 0); ?>
    <?php while ($cat = mysqli_fetch_assoc($catResult)): ?>
      <option value="<?= $cat['id'] ?>" <?= $filterCategory == $cat['id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($cat['name']) ?>
      </option>
    <?php endwhile; ?>
  </select>

  <label for="priorityFilter">Priority:</label>
  <select name="priority" id="priorityFilter" onchange="this.form.submit()">
    <option value="all" <?= $filterPriority === 'all' ? 'selected' : '' ?>>All</option>
    <option value="high" <?= $filterPriority === 'high' ? 'selected' : '' ?>>High</option>
    <option value="medium" <?= $filterPriority === 'medium' ? 'selected' : '' ?>>Medium</option>
    <option value="low" <?= $filterPriority === 'low' ? 'selected' : '' ?>>Low</option>
  </select>
</form>
  </div>
</div>

  
    

    <?php while ($task = mysqli_fetch_assoc($result)): ?>
      <?php
        $priority = $task['priority'];
        $priorityClass = 'priority-medium';
        if ($priority === 'high') $priorityClass = 'priority-high';
        if ($priority === 'low') $priorityClass = 'priority-low';
      ?>
      <div 
        class="task-card" 
        data-due-date="<?= $task['due_date'] ?>" 
        data-completed="<?= (int)$task['completed'] ?>"
      >
        <div>
          <div class="task-title">
            <?= htmlspecialchars($task["title"]) ?>
          </div>

          <div class="task-meta">
            <?php if ($task['category_name']): ?>
              <span class="category-badge"><?= htmlspecialchars($task['category_name']) ?></span>
            <?php endif; ?>

            <span class="priority-badge <?= $priorityClass ?>">
              <?= ucfirst($priority) ?> priority
            </span>

            <?php if ($task['due_date']): ?>
              <span class="due-date-label">Due: <?= htmlspecialchars($task['due_date']) ?></span>
            <?php endif; ?>
          </div>

          <?php if ($task["completed"]): ?>
            <div style="margin-top:6px;">
              <span class="completed">✔ Completed</span>
            </div>
          <?php endif; ?>
        </div>

        <div class="task-actions">
        <a href="tasks/edit.php?id=<?= $task['id'] ?>" class="btn">Edit</a>
          <?php if (!$task["completed"]): ?>
            <a href="tasks/complete.php?id=<?= $task['id'] ?>" 
               class="btn-complete js-confirm-complete"
               data-confirm="Mark this task as completed?">
              Complete
            </a>
          <?php endif; ?>
          <a href="tasks/delete.php?id=<?= $task['id'] ?>" 
             class="btn-delete js-confirm-delete"
             data-confirm="Are you sure you want to delete this task?">
            Delete
          </a>
        </div>
      </div>
    <?php endwhile; ?>

  </div>

  <script src="script.js"></script>
</body>
</html>

