<?php
session_start();
$error = "";

// DB connection
$conn = mysqli_connect("localhost", "root", "", "students_db");
if (!$conn) {
  die("Database connection failed: " . mysqli_connect_error());
}

// Handle login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  $stmt = $conn->prepare("SELECT * FROM users WHERE name = ? AND password = ?");
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $_SESSION['admin'] = $row['name'];
    header('Location: admin_panel.php');
    exit();
  } else {
    $error = "❌ Invalid admin credentials!";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Login - QuickLearn</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      height: 100vh;
      background-color: #121212;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-container {
      display: flex;
      width: 100%;
      max-width: 1000px;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
      background-color: #1f1f1f;
    }

    .left-panel {
      width: 50%;
      padding: 60px 40px;
      background-color: #1f1f1f;
      color: white;
    }

    .left-panel h2 {
      font-size: 32px;
      margin-bottom: 10px;
      color: #bb86fc;
    }

    .left-panel p {
      font-size: 14px;
      margin-bottom: 30px;
      color: #ccc;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group input {
      width: 100%;
      padding: 12px;
      background-color: #2a2a2a;
      border: none;
      border-bottom: 2px solid #444;
      color: white;
      font-size: 14px;
      border-radius: 6px;
    }

    .form-group input::placeholder {
      color: #888;
    }

    .form-group input:focus {
      outline: none;
      border-bottom-color: #bb86fc;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #bb86fc;
      border: none;
      color: #121212;
      font-weight: 600;
      font-size: 15px;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background-color: #9a5cff;
    }

    .error {
      background-color: #ff4d4d;
      padding: 10px;
      color: white;
      margin-bottom: 20px;
      border-radius: 6px;
      font-size: 13px;
      text-align: center;
    }

    .right-panel {
      background-color: #a259ff;
      width: 50%;
      padding: 40px 30px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: white;
    }

    .right-panel h1 {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .right-panel h3 {
      font-size: 16px;
      font-weight: 400;
      margin-bottom: 20px;
    }

    .right-panel img {
      width: 100%;
      max-width: 350px;
      height: auto;
    }

    @media (max-width: 768px) {
      .login-container {
        flex-direction: column;
      }

      .left-panel, .right-panel {
        width: 100%;
        padding: 40px 25px;
      }

      .right-panel {
        order: -1;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="left-panel">
      <h2>🛡️ Admin Login</h2>
      <p>Enter your admin credentials</p>
      <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
      <form method="post" autocomplete="off">
        <div class="form-group">
          <input type="text" name="username" placeholder="Admin Username" required>
        </div>
        <div class="form-group">
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit">Login</button>
      </form>
    </div>
    <div class="right-panel">
      <h1>Welcome Admin</h1>
      <h3>Access your dashboard securely</h3>
      <img src="img/admin.jpg" alt="Admin Illustration" />
    </div>
  </div>
</body>
</html>
