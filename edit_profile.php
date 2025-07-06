<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: index.html");
  exit();
}

$conn = new mysqli("localhost", "root", "", "students_db");

$email = $_SESSION['email'];
$result = $conn->query("SELECT * FROM registration WHERE email='$email'");
$data = $result->fetch_assoc();

$profile_img = !empty($data['profile_img']) ? $data['profile_img'] : "https://cdn-icons-png.flaticon.com/512/3135/3135715.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
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
      background: linear-gradient(to right, #667eea, #764ba2);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .form-container {
      background: #fff;
      padding: 40px;
      border-radius: 16px;
      max-width: 600px;
      width: 100%;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 30px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      font-weight: 600;
      display: block;
      margin-bottom: 8px;
      color: #555;
    }

    input[type="text"],
    input[type="file"] {
      width: 100%;
      padding: 10px 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
    }

    input[type="file"] {
      padding: 6px;
    }

    .profile-preview {
      text-align: center;
      margin-bottom: 20px;
    }

    .profile-preview img {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      border: 3px solid #764ba2;
      object-fit: cover;
    }

    button {
      background: #764ba2;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 10px;
      width: 100%;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background: #5e3b94;
    }

    @media (max-width: 600px) {
      .form-container {
        padding: 25px 20px;
      }
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>✏️ Edit Your Profile</h2>

  <div class="profile-preview">
    <img src="<?= htmlspecialchars($profile_img) ?>" alt="Current Profile Picture">
  </div>

  <form action="update_profile.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label>Full Name</label>
      <input type="text" name="fullname" value="<?= htmlspecialchars($data['fullname']) ?>" required>
    </div>

    <div class="form-group">
      <label>Roll No</label>
      <input type="text" name="roll_no" value="<?= htmlspecialchars($data['roll_no']) ?>" required>
    </div>

    <div class="form-group">
      <label>Course</label>
      <input type="text" name="course" value="<?= htmlspecialchars($data['course']) ?>" required>
    </div>

    <div class="form-group">
      <label>Semester</label>
      <input type="text" name="yearSemister" value="<?= htmlspecialchars($data['yearSemister']) ?>" required>
    </div>

    <div class="form-group">
      <label>Update Profile Picture</label>
      <input type="file" name="profile_image" accept="image/*">
    </div>

    <button type="submit">Update Profile</button>
  </form>
</div>

</body>
</html>
