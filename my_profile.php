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

$query = "SELECT name, email FROM users WHERE name = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $adminName);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$adminData = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
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
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #4e54c8, #8f94fb);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      padding: 20px;
    }

    .profile-card {
      background: white;
      padding: 40px 30px;
      border-radius: 16px;
      text-align: center;
      max-width: 420px;
      width: 100%;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
      animation: slideFade 0.6s ease-in-out;
    }

    .avatar {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #6c63ff;
      margin-bottom: 20px;
    }

    h2 {
      margin-bottom: 10px;
      color: #333;
    }

    p {
      margin: 8px 0;
      font-size: 15px;
      color: #555;
    }

    .btn-group {
      margin-top: 25px;
      display: flex;
      justify-content: space-between;
      gap: 15px;
      flex-wrap: wrap;
    }

    .btn {
      padding: 12px 18px;
      background: #6c63ff;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      text-decoration: none;
      font-size: 14px;
      flex: 1;
      text-align: center;
      transition: background 0.3s ease, transform 0.2s;
    }

    .btn:hover {
      background: #4e4ac7;
      transform: translateY(-2px);
    }

    @keyframes slideFade {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 450px) {
      .btn-group {
        flex-direction: column;
      }
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
    <?php else: ?>
      <p>❌ Admin data not found.</p>
    <?php endif; ?>

    <div class="btn-group">
      <a href="admin_panel.php" class="btn">⬅ Dashboard</a>
      <a href="adminchange_password.php" class="btn">🔐 Change Password</a>
    </div>
  </div>
</body>
</html>
