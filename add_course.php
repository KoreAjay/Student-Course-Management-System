<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: admin_login.php");
  exit();
}

$conn = mysqli_connect("localhost", "root", "", "students_db");

// Insert course logic
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $course = mysqli_real_escape_string($conn, $_POST['course_name']);
  $desc = mysqli_real_escape_string($conn, $_POST['description']);

  // Handle image upload
  $image_name = $_FILES['image']['name'];
  $image_tmp = $_FILES['image']['tmp_name'];
  $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
  $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

  if (in_array($image_ext, $allowed_ext)) {
    $new_name = uniqid() . "." . $image_ext;
    $target = "uploads/" . $new_name;

    if (move_uploaded_file($image_tmp, $target)) {
      $query = "INSERT INTO courses(course_name, description, image) VALUES('$course', '$desc', '$new_name')";
      if (mysqli_query($conn, $query)) {
        $msg = "✅ Course added successfully!";
      } else {
        $msg = "❌ Database error!";
      }
    } else {
      $msg = "❌ Failed to upload image!";
    }
  } else {
    $msg = "❌ Invalid image format!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Course - QuickLearn Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(120deg, #4e54c8, #8f94fb);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .container {
      background: #fff;
      padding: 35px 30px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 500px;
    }

    h2 {
      margin-bottom: 20px;
      color: #333;
      font-weight: 600;
      text-align: center;
    }

    form input[type="text"],
    form textarea,
    form input[type="file"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 18px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
    }

    form textarea {
      resize: vertical;
      height: 100px;
    }

    form button {
      width: 100%;
      padding: 12px;
      background-color: #4e54c8;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    form button:hover {
      background-color: #5e60e6;
    }

    .msg {
      margin-bottom: 15px;
      text-align: center;
      font-weight: bold;
      color: #d80027;
    }

    .success {
      color: #2e7d32;
    }

    .back-link {
      text-align: center;
      margin-top: 20px;
    }

    .back-link a {
      text-decoration: none;
      color: #4e54c8;
      font-weight: bold;
    }

    .back-link a:hover {
      text-decoration: underline;
    }

    @media (max-width: 500px) {
      .container {
        padding: 25px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>📘 Add New Course</h2>
    <?php if (!empty($msg)): ?>
      <div class="msg <?= strpos($msg, '✅') !== false ? 'success' : '' ?>">
        <?= $msg ?>
      </div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
      <input type="text" name="course_name" placeholder="Course Name" required>
      <textarea name="description" placeholder="Course Description" required></textarea>
      <input type="file" name="image" accept="image/*" required>
      <button type="submit">➕ Add Course</button>
    </form>
    <div class="back-link">
      <a href="admin_panel.php">← Back to Admin Panel</a>
    </div>
  </div>
</body>
</html>
