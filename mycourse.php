<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: index.html");
  exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "students_db";
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$email = mysqli_real_escape_string($conn, $_SESSION['email']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_course'])) {
  $course_to_delete = mysqli_real_escape_string($conn, $_POST['delete_course']);
  $delete_sql = "DELETE FROM enrollments WHERE email = '$email' AND course_name = '$course_to_delete'";
  mysqli_query($conn, $delete_sql);
  header("Location: mycourse.php?deleted=1");
  exit();
}

$sql = "SELECT course_name, enroll_date FROM enrollments WHERE email = '$email'";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Enrolled Courses - QuickLearn</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="favicon.png" type="image/png" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--bg-gradient);
      color: var(--text-color);
      min-height: 100vh;
      transition: background 0.5s, color 0.5s;
    }

    :root {
      --bg-gradient: linear-gradient(to right, #0f2027, #203a43, #2c5364);
      --card-bg: rgba(255, 255, 255, 0.08);
      --text-color: #fff;
      --btn-bg: linear-gradient(135deg, #e74c3c, #c0392b);
    }

    body.light {
      --bg-gradient: #f4f4f4;
      --card-bg: rgba(255, 255, 255, 0.85);
      --text-color: #333;
      --btn-bg: linear-gradient(135deg, #ff7675, #e17055);
    }

    .navbar {
      background: rgba(0, 0, 0, 0.3);
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 100;
      backdrop-filter: blur(10px);
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
      color: #fff;
      text-shadow: 1px 1px 3px #000;
    }

    .nav-links {
      display: flex;
      gap: 20px;
      list-style: none;
    }

    .nav-links a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s;
    }

    .nav-links a:hover {
      color: #00ffff;
    }

    .dark-toggle {
      cursor: pointer;
      font-size: 20px;
      color: #fff;
    }

    .header {
      text-align: center;
      padding: 50px 20px 20px;
    }

    .header h1 {
      font-size: 36px;
      text-shadow: 2px 2px 5px #000;
    }

    .message {
      text-align: center;
      color: #2ecc71;
      font-weight: bold;
      margin-top: 10px;
    }

    .no-course {
      text-align: center;
      color: var(--text-color);
      font-weight: bold;
      margin-top: 20px;
      font-size: 18px;
    }

    .course-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      padding: 40px 60px;
    }

    .course-card {
      background: var(--card-bg);
      backdrop-filter: blur(20px);
      border-radius: 16px;
      padding: 20px;
      color: var(--text-color);
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      transition: transform 0.3s;
    }

    .course-card:hover {
      transform: translateY(-6px);
    }

    .course-name {
      font-size: 22px;
      font-weight: 600;
      margin-bottom: 8px;
    }

    .enroll-date {
      font-size: 14px;
      color: #ccc;
      margin-bottom: 20px;
    }

    .delete-btn {
      padding: 10px 16px;
      border: none;
      border-radius: 8px;
      background: var(--btn-bg);
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    .delete-btn:hover {
      filter: brightness(1.1);
    }

    @media (max-width: 768px) {
      .navbar { flex-direction: column; align-items: flex-start; }
      .nav-links { flex-direction: column; gap: 10px; margin-top: 10px; }
      .course-grid { padding: 20px; }
    }
  </style>
</head>
<body>

<nav class="navbar">
  <div class="logo">🎓 QuickLearn</div>
  <ul class="nav-links">
    <li><a href="home.html"><i class="fas fa-home"></i> Dashboard</a></li>
    <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
    <li><a href="courses.php"><i class="fas fa-book"></i> Courses</a></li>
    <li><a href="index.html" onclick="return confirm('Are you sure you want to logout?')"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
  </ul>
  <div class="dark-toggle" onclick="toggleMode()" title="Toggle Dark/Light Mode">
    <i class="fas fa-adjust"></i>
  </div>
</nav>

<div class="header">
  <h1>📘 My Enrolled Courses</h1>
  <?php if (isset($_GET['deleted'])): ?>
    <div class="message">✅ Course deleted successfully.</div>
  <?php endif; ?>
</div>

<?php if (mysqli_num_rows($result) > 0): ?>
  <div class="course-grid">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
      <div class="course-card">
        <div class="course-name"><?= htmlspecialchars($row['course_name']) ?></div>
        <div class="enroll-date">📅 Enrolled on: <?= htmlspecialchars($row['enroll_date']) ?></div>
        <form method="post" onsubmit="return confirm('Are you sure you want to delete this course?');">
          <input type="hidden" name="delete_course" value="<?= htmlspecialchars($row['course_name']) ?>">
          <button type="submit" class="delete-btn">Delete</button>
        </form>
      </div>
    <?php } ?>
  </div>
<?php else: ?>
  <div class="no-course">❌ You have not enrolled in any courses yet.</div>
<?php endif; ?>

<script>
  function toggleMode() {
    document.body.classList.toggle('light');
  }
</script>

</body>
</html>
