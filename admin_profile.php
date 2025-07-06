<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$adminName = $_SESSION['admin'];

// DB connect
$conn = mysqli_connect("localhost", "root", "", "students_db");
if (!$conn) {
    die("❌ Database connection failed: " . mysqli_connect_error());
}

// Get admin data
$query = "SELECT name, email, last_login FROM users WHERE name = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $adminName);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$adminData = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Get total courses
$courseResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM courses");
$totalCourses = mysqli_fetch_assoc($courseResult)['total'] ?? 0;

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #4e54c8, #8f94fb);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .profile-card {
      background: white;
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.2);
      text-align: center;
      max-width: 400px;
      width: 100%;
    }

    .avatar {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      margin-bottom: 15px;
      border: 3px solid #4e54c8;
    }

    h2 {
      margin-bottom: 15px;
      color: #333;
    }

    p {
      margin: 10px 0;
      color: #555;
      font-size: 15px;
    }

    .btn-group {
      margin-top: 25px;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .btn {
      padding: 12px;
      background-color: #4e54c8;
      color: white;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .btn:hover {
      background-color: #3e40a8;
    }
  </style>
</head>
<body>
  <div class="profile-card">
    <img class="avatar" src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Admin Avatar">
    <h2>👤 Admin Profile</h2>

    <?php if ($adminData): ?>
      <p><strong>Name:</strong> <?= htmlspecialchars($adminData['name']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($adminData['email']) ?></p>
      <p><strong>Total Courses:</strong> <?= $totalCourses ?></p>
      <p><strong>Last Login:</strong> <?= $adminData['last_login'] ?? 'Not available' ?></p>
    <?php else: ?>
      <p>❌ Admin data not found.</p>
    <?php endif; ?>

    <div class="btn-group">
      <a href="admin_panel.php" class="btn">⬅ Back to Dashboard</a>
      <a href="change_password.php" class="btn">🔐 Change Password</a>
    </div>
  </div>
</body>
</html>
