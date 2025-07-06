<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$adminName = $_SESSION['admin'];
$message = "";
$success = false;

// DB connection
$conn = mysqli_connect("localhost", "root", "", "students_db");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if (empty($old) || empty($new) || empty($confirm)) {
        $message = "⚠ Please fill in all fields.";
    } elseif ($new !== $confirm) {
        $message = "❌ New passwords do not match.";
    } elseif (strlen($new) < 6) {
        $message = "🔐 New password must be at least 6 characters.";
    } else {
        // Fetch current hashed password
        $query = "SELECT password FROM users WHERE name = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $adminName);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $currentHashedPassword = $row['password'];

            // Verify old password
            if (!password_verify($old, $currentHashedPassword)) {
                $message = "❌ Old password is incorrect.";
            } else {
                // Update new password
                $newHashed = password_hash($new, PASSWORD_DEFAULT);
                $update = "UPDATE users SET password = ? WHERE name = ?";
                $stmt = mysqli_prepare($conn, $update);
                mysqli_stmt_bind_param($stmt, "ss", $newHashed, $adminName);
                if (mysqli_stmt_execute($stmt)) {
                    $success = true;
                    $message = "✅ Password updated successfully!";
                } else {
                    $message = "❌ Failed to update password.";
                }
                mysqli_stmt_close($stmt);
            }
        } else {
            $message = "❌ Admin record not found.";
        }
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);  //fix  error---------------------------- //not updated password
?>
 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Change Admin Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #667eea, #764ba2);
      display: flex; justify-content: center; align-items: center;
      height: 100vh; padding: 20px;
    }
    .container {
      background: white; padding: 40px 30px; border-radius: 16px;
      width: 100%; max-width: 420px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    h2 { text-align: center; color: #333; margin-bottom: 25px; }
    input {
      width: 100%; padding: 12px; margin: 10px 0;
      border: 1px solid #ddd; border-radius: 8px; font-size: 15px;
    }
    button {
      width: 100%; padding: 12px;
      background-color: #667eea; color: white;
      border: none; border-radius: 8px; font-size: 16px; font-weight: bold;
      cursor: pointer; margin-top: 10px;
    }
    button:hover { background-color: #5564c7; }
    .message {
      margin-top: 18px; padding: 10px;
      border-radius: 8px; text-align: center; font-weight: 500;
    }
    .message.success {
      color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb;
    }
    .message.error {
      color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb;
    }
    .back-link {
      display: block; margin-top: 18px;
      text-align: center; text-decoration: none; color: #667eea; font-weight: 600;
    }
    .back-link:hover { color: #5564c7; }
  </style>
</head>
<body>
  <div class="container">
    <h2>🔐 Change Admin Password</h2>
    <form method="POST">
      <input type="password" name="old_password" placeholder="Enter old password" required>
      <input type="password" name="new_password" placeholder="Enter new password" required>
      <input type="password" name="confirm_password" placeholder="Confirm new password" required>
      <button type="submit">Update Password</button>
    </form>

    <?php if (!empty($message)): ?>
      <div class="message <?= $success ? 'success' : 'error' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <a class="back-link" href="my_profile.php">⬅ Back to Profile</a>
  </div>
</body>
</html>
