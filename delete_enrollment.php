<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "students_db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    mysqli_query($conn, "DELETE FROM enrollment WHERE id = $id");
}
header("Location: admin_panel.php");
exit();
