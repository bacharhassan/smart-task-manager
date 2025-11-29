<?php
session_start();
require "../db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit;
}

$id = $_GET["id"];
$user_id = $_SESSION["user_id"];

$query = "UPDATE tasks SET completed=1 WHERE id=$id AND user_id=$user_id";
mysqli_query($conn, $query);

header("Location: ../dashboard.php");
exit;
?>
