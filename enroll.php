<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "students_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$email = $_SESSION['email'] ?? '';
$course = $_POST['course'] ?? '';

if ($email && $course) {
  // Check if already enrolled
  $check = "SELECT * FROM enrollments WHERE email = '$email' AND course_name = '$course'";
  $result = mysqli_query($conn, $check);

  if (mysqli_num_rows($result) > 0) {
    echo "<script>alert('You are already enrolled in this course.'); window.location.href='courses.php';</script>";
  } else {
    $sql = "INSERT INTO enrollments (email, course_name) VALUES ('$email', '$course')";
    if (mysqli_query($conn, $sql)) {
      header("Location: courses.php?success=1");
    } else {
      echo "<script>alert('❌ Enrollment failed.'); window.history.back();</script>";
    }
  }
} else {
  echo "<script>alert('Please login first.'); window.location.href='login.html';</script>";
}

mysqli_close($conn);
?>
