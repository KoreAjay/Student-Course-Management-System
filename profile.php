<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}

// Get session data
$fullname = $_SESSION['fullname'];
$roll_no  = $_SESSION['roll_no'];
$email    = $_SESSION['email'];
$course   = $_SESSION['course'];
$semester = $_SESSION['semester'];

// Connect to DB and get profile_img
$conn = mysqli_connect("localhost", "root", "", "students_db");
if (!$conn) { die("DB connection failed: " . mysqli_connect_error()); }

$sql = "SELECT profile_img FROM registration WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $profile_img = !empty($row['profile_img'])
      ? $row['profile_img']
      : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
} else {
    $profile_img = 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🎓 Student Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    /* --------------------------
       Global Reset & Typography
    --------------------------- */
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family:'Poppins',sans-serif;
      color:#333;
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      background: linear-gradient(135deg,#667eea,#764ba2);
      background-size:400% 400%;
      animation:gradient 15s ease infinite;
      padding:20px;
    }
    @keyframes gradient {
      0%   { background-position:0% 50%; }
      50%  { background-position:100% 50%; }
      100% { background-position:0% 50%; }
    }

    /* --------------------------
       Profile Card
    --------------------------- */
    .profile-card {
      background:#fff;
      border-radius:16px;
      box-shadow:0 16px 40px rgba(0,0,0,0.2);
      overflow:hidden;
      max-width:420px;
      width:100%;
      text-align:center;
      padding:40px 20px;
      animation:slideIn 0.6s ease-out;
      position:relative;
    }
    @keyframes slideIn {
      from { opacity:0; transform:translateY(30px); }
      to   { opacity:1; transform:translateY(0); }
    }

    /* --------------------------
       Profile Image
    --------------------------- */
    .profile-card .avatar {
      width:120px; height:120px;
      border-radius:50%;
      border:4px solid #764ba2;
      object-fit:cover;
      transition:transform 0.3s ease;
      margin-bottom:16px;
    }
    .profile-card .avatar:hover {
      transform:scale(1.05);
    }

    /* --------------------------
       Headings & Info
    --------------------------- */
    .profile-card h2 {
      font-size:1.8rem;
      margin-bottom:8px;
      color:#444;
    }
    .profile-card .info {
      text-align:left;
      margin:24px 0;
    }
    .profile-card .info p {
      display:flex;
      align-items:center;
      margin-bottom:12px;
      font-size:1rem;
    }
    .profile-card .info p strong {
      width:110px;
      color:#764ba2;
    }

    /* --------------------------
       Button Group
    --------------------------- */
    .btn-group {
      display:flex;
      flex-wrap:wrap;
      gap:12px;
      justify-content:center;
    }
    .btn-group a {
      flex:1 1 120px;
      background:#764ba2;
      color:#fff;
      text-decoration:none;
      padding:10px 0;
      border-radius:8px;
      font-weight:600;
      transition:background 0.3s ease,transform 0.3s ease;
    }
    .btn-group a:hover {
      background:#5e3b94;
      transform:translateY(-2px);
    }

    /* --------------------------
       Responsive
    --------------------------- */
    @media (max-width:480px) {
      .profile-card { padding:30px 16px; }
      .profile-card h2 { font-size:1.5rem; }
    }
  </style>
</head>
<body>

  <div class="profile-card">
    <img class="avatar"
         src="<?= htmlspecialchars($profile_img) ?>"
         alt="Profile Picture"
         onerror="this.onerror=null;this.src='https://cdn-icons-png.flaticon.com/512/3135/3135715.png';">
    <h2>👋 Hello, <?= htmlspecialchars($fullname) ?>!</h2>

    <div class="info">
      <p><strong>Roll No:</strong> <?= htmlspecialchars($roll_no) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
      <p><strong>Course:</strong> <?= htmlspecialchars($course) ?></p>
      <p><strong>Semester:</strong> <?= htmlspecialchars($semester) ?></p>
    </div>

    <div class="btn-group">
      <a href="home.html">Dashboard</a>
      <a href="mycourse.php">My Courses</a>
      <a href="change.html">Change Password</a>
      <a href="edit_profile.php">Edit Profile</a>
    </div>
  </div>

</body>
</html>
