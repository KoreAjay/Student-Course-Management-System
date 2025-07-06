<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: admin_login.php");
  exit();
}

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $conn = mysqli_connect("localhost", "root", "", "students_db");

  if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
  }

  // Optional: Delete course-related enrollments if needed
  // mysqli_query($conn, "DELETE FROM enrollments WHERE course_id = $id");

  $delete = mysqli_query($conn, "DELETE FROM courses WHERE id = $id");

  if ($delete) {
    header("Location: admin_panel.php");
    exit();
  } else {
    echo "Failed to delete course.";
  }
}
?>
