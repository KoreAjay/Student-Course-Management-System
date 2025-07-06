<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Available Courses - QuickLearn</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="icon" href="assets/favicon.png" type="image/png" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

  <style>
    :root {
      --light-bg: #f4f4f4;
      --dark-bg: #1e1e2f;
      --light-text: #333;
      --dark-text: #f5f5f5;
      --accent: #2575fc;
      --gradient: linear-gradient(to right, #8360c3, #2ebf91);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--gradient);
      color: var(--light-text);
      min-height: 100vh;
    }

    body.dark-mode {
      background: var(--dark-bg);
      color: var(--dark-text);
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 30px;
      background: rgba(255,255,255,0.1);
      backdrop-filter: blur(10px);
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
      color: #fff;
      text-shadow: 1px 1px 3px #000;
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 25px;
    }

    .nav-links a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s;
    }

    .nav-links a:hover { color: #00fff7; }

    .toggle-mode {
      background: none;
      border: 2px solid #fff;
      padding: 6px 12px;
      color: #fff;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
      transition: 0.3s;
    }

    .toggle-mode:hover {
      background: #fff;
      color: #333;
    }

    .header {
      text-align: center;
      margin: 40px 20px 10px;
    }

    .header h1 {
      font-size: 36px;
      color: #fff;
      text-shadow: 2px 2px 5px #000;
    }

    .success-msg {
      color: #2ecc71;
      font-weight: bold;
      margin-top: 15px;
    }

    .search-bar {
      text-align: center;
      margin: 20px auto;
    }

    .search-bar input {
      width: 60%;
      max-width: 500px;
      padding: 12px 16px;
      border-radius: 8px;
      border: none;
      font-size: 16px;
      outline: none;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .course-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      padding: 40px 60px;
    }

    .course-card {
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(15px);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .course-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }

    .course-image {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .course-content {
      padding: 20px;
    }

    .course-title {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .course-description {
      font-size: 14px;
      line-height: 1.5;
      color: #f0f0f0;
      margin-bottom: 20px;
    }

    .enroll-btn {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      color: #fff;
      transition: background 0.3s;
    }

    .enroll-btn:hover {
      background: linear-gradient(135deg, #2575fc, #6a11cb);
    }

    @media (max-width: 768px) {
      .navbar { flex-direction: column; align-items: flex-start; gap: 10px; }
      .nav-links { flex-direction: column; gap: 10px; }
      .course-container { padding: 20px; }
      .header h1 { font-size: 28px; }
    }
  </style>

  <script>
    function toggleMode() {
      document.body.classList.toggle("dark-mode");
    }

    // Search filter
    document.addEventListener("DOMContentLoaded", () => {
      const searchInput = document.getElementById("searchInput");
      searchInput.addEventListener("input", () => {
        const query = searchInput.value.toLowerCase();
        document.querySelectorAll(".course-card").forEach(card => {
          const text = card.textContent.toLowerCase();
          card.style.display = text.includes(query) ? "block" : "none";
        });
      });
    });
  </script>
</head>
<body>

  <nav class="navbar">
    <div class="logo">🎓 QuickLearn</div>
    <ul class="nav-links">
      <li><a href="home.html"><i class="fas fa-home"></i> Dashboard</a></li>
      <li><a href="courses.php"><i class="fas fa-book"></i> Courses</a></li>
      <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
      <li><a href="logout.php" onclick="return confirm('Are you sure you want to logout?')"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
    <button class="toggle-mode" onclick="toggleMode()">🌙 </button>
  </nav>

  <div class="header">
    <h1>📚 Explore Our Courses</h1>
    <?php if (isset($_GET['success'])): ?>
      <div class="success-msg">✅ Enrolled successfully!</div>
    <?php endif; ?>
  </div>

  <!-- Search bar -->
  <div class="search-bar">
    <input type="text" id="searchInput" placeholder="🔍 Search courses by name or topic...">
  </div>

  <div class="course-container">
    <?php
    $conn = mysqli_connect("localhost", "root", "", "students_db");
    if (!$conn) {
      echo "<p style='color:red;text-align:center;'>❌ DB Connection Error: " . mysqli_connect_error() . "</p>";
      exit;
    }

    $query = "SELECT * FROM courses";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $title = htmlspecialchars($row['course_name']);
        $desc = htmlspecialchars($row['description']);
        $image = !empty($row['image']) ? "uploads/" . $row['image'] : "https://via.placeholder.com/600x300?text=No+Image";

        echo "
        <div class='course-card'>
          <img src='$image' alt='Course Image' class='course-image' onerror=\"this.onerror=null;this.src='https://via.placeholder.com/600x300?text=No+Image';\">
          <div class='course-content'>
            <div class='course-title'>📘 $title</div>
            <div class='course-description'>$desc</div>
            <form method='post' action='enroll.php'>
              <input type='hidden' name='course' value='$title'>
              <button type='submit' class='enroll-btn'>Enroll Now</button>
            </form>
          </div>
        </div>";
      }
    } else {
      echo "<p style='color:white;text-align:center;font-size:18px;'>❌ No courses found.</p>";
    }

    mysqli_close($conn);
    ?>
  </div>

</body>
</html>
