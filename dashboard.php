<?php
session_start();
include 'config/db.php'; // adjust path as needed

if (!isset($_SESSION['student_id'])) {
  header("Location: login.php");
  exit();
}

$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'] ?? 'Student';

// Fetch enrolled courses
$course_count = 0;
$completed_lessons = 0;

$sql_courses = "SELECT COUNT(*) AS total FROM course_enrollments WHERE student_id = ?";
$stmt = $conn->prepare($sql_courses);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$course_count = $result['total'];

// Fetch completed lessons
$sql_lessons = "SELECT COUNT(*) AS completed FROM lessons_completed WHERE student_id = ?";
$stmt = $conn->prepare($sql_lessons);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$completed_lessons = $result['completed'];

// Calculate remaining to goal (assume goal is 25 lessons)
$goal_target = 25;
$remaining = max(0, $goal_target - $completed_lessons);
session_start();
include 'config/db.php';
if (!isset($_SESSION['student_id'])) header("Location: login.php");

$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'] ?? 'Student';

// Profile photo (default fallback)
$sql_user = "SELECT profile_photo FROM students WHERE id = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$profile_photo = $res['profile_photo'] ?: 'default_avatar.png';

// Stats as before...
// Fetch recent courses (limit 3)
$sql_recent = "
  SELECT c.course_name 
  FROM course_enrollments ce
  JOIN courses c ON ce.course_id = c.id
  WHERE ce.student_id = ?
  ORDER BY ce.enroll_date DESC LIMIT 3";
$stmt = $conn->prepare($sql_recent);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$recent_courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch upcoming lessons (next 3 scheduled lessons)
$sql_upcoming = "
  SELECT l.lesson_title, l.scheduled_date
  FROM lessons l
  JOIN course_enrollments ce USING(course_id)
  WHERE ce.student_id = ? AND l.scheduled_date >= CURDATE()
  ORDER BY l.scheduled_date ASC LIMIT 3";
$stmt = $conn->prepare($sql_upcoming);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$upcoming = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

?>
