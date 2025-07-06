<?php
session_start();

// Session timeout: 5 minutes
$timeout_duration = 300;

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: admin_login.php");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "students_db");

$users = mysqli_query($conn, "SELECT * FROM users");
$courses = mysqli_query($conn, "SELECT * FROM courses");  
$students = mysqli_query($conn, "SELECT * FROM registration ORDER BY yearSemister ASC");

$adminName = $_SESSION['admin'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #4e54c8;
      --secondary: #8f94fb;
      --light: #f4f4f4;
      --dark: #1e1e2f;
      --text: #333;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Nunito', sans-serif;
    }

    body {
      background: linear-gradient(to right, var(--primary), var(--secondary));
      color: var(--text);
      padding: 30px;
    }

    .navbar {
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .navbar h1 {
      font-size: 26px;
      color: var(--primary);
    }

    .profile-dropdown {
      position: relative;
      display: inline-block;
    }

    .profile img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      cursor: pointer;
      border: 2px solid var(--primary);
    }

    .dropdown-menu {
      display: none;
      position: absolute;
      right: 0;
      top: 60px;
      background-color: white;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      border-radius: 8px;
      overflow: hidden;
      z-index: 1;
      min-width: 150px;
    }

    .dropdown-menu a {
      display: block;
      padding: 12px 16px;
      text-decoration: none;
      color: #333;
      background: #fff;
      border-bottom: 1px solid #f1f1f1;
    }

    .dropdown-menu a:hover {
      background-color: #f4f4f4;
    }

    .show-menu {
      display: block;
    }

    .tabs {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .tab-btn {
      background: #fff;
      color: var(--primary);
      border: none;
      padding: 12px 20px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s ease;
    }

    .tab-btn.active, .tab-btn:hover {
      background: var(--primary);
      color: #fff;
    }

    .container {
      background: #fff;
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      animation: fadeIn 0.6s ease-in-out;
    }

    .search-box {
      margin: 10px 0;
      display: flex;
      justify-content: flex-end;
    }

    .search-box input {
      padding: 8px 14px;
      border: 1px solid #ccc;
      border-radius: 6px;
      width: 250px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 14px;
      border-bottom: 1px solid #eee;
      text-align: center;
    }

    th {
      background-color: var(--primary);
      color: white;
      position: sticky;
      top: 0;
    }

    tr:hover {
      background-color: #f9f9f9;
    }

    .delete-link {
      color: crimson;
      font-weight: bold;
      text-decoration: none;
    }

    .delete-link:hover {
      text-decoration: underline;
    }

    .add-btn {
      background-color: #28a745;
      color: white;
      padding: 10px 18px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s ease;
    }

    .add-btn:hover {
      background-color: #218838;
    }

    .hidden {
      display: none;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media screen and (max-width: 768px) {
      .tab-btn {
        flex: 1;
        padding: 10px;
        font-size: 14px;
      }

      .search-box input {
        width: 100%;
      }
    }
  </style>
</head>
<body>

<div class="navbar">
  <h1>📊 Admin Panel</h1>
  <div class="profile-dropdown">
    <div class="profile" onclick="toggleDropdown()">
      <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Admin">
    </div>
    <div id="dropdownMenu" class="dropdown-menu">
      <a href="my_profile.php">👤 My Profile</a>
      <a href="logout.php">🚪 Logout</a>
    </div>
  </div>
</div>

<div class="tabs">
  <button class="tab-btn active" onclick="showTab('users')">👥 Users</button>
  <button class="tab-btn" onclick="showTab('courses')">📚 Courses</button>
  <button class="tab-btn" onclick="showTab('students')">🎓 Students</button>
</div>

<!-- Users -->
<div class="container" id="users">
  <div class="search-box"><input type="text" placeholder="Search Users..." onkeyup="filterTable(this, 'userTable')"></div>
  <table id="userTable">
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Action</th></tr>
    <?php while ($u = mysqli_fetch_assoc($users)): ?>
      <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['name']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><a class="delete-link" href="delete_user.php?id=<?= $u['id'] ?>" onclick="return confirm('Delete user?')">🗑️ Delete</a></td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<!-- Courses -->
<div class="container hidden" id="courses">
  <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
    <div class="search-box"><input type="text" placeholder="Search Courses..." onkeyup="filterTable(this, 'courseTable')"></div>
    <a href="add_course.php" class="add-btn">➕ Add New Course</a>
  </div>
  <table id="courseTable">
    <tr><th>ID</th><th>Course Name</th><th>Action</th></tr>
    <?php while ($c = mysqli_fetch_assoc($courses)): ?>
      <tr>
        <td><?= $c['id'] ?></td>
        <td><?= htmlspecialchars($c['course_name']) ?></td>
        <td><a class="delete-link" href="delete_course.php?id=<?= $c['id'] ?>" onclick="return confirm('Delete course?')">🗑️ Delete</a></td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<!-- Students -->
<div class="container hidden" id="students">
  <div class="search-box"><input type="text" placeholder="Search Students..." onkeyup="filterTable(this, 'studentTable')"></div>
  <table id="studentTable">
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Roll No</th><th>Course</th><th>Year/Sem</th><th>Password</th><th>Action</th></tr>
    <?php while ($s = mysqli_fetch_assoc($students)): ?>
      <tr>
        <td><?= $s['id'] ?></td>
        <td><?= htmlspecialchars($s['fullname']) ?></td>
        <td><?= htmlspecialchars($s['email']) ?></td>
        <td><?= htmlspecialchars($s['roll_no']) ?></td>
        <td><?= htmlspecialchars($s['course']) ?></td>
        <td><?= htmlspecialchars($s['yearSemister']) ?></td>
        <td><?= htmlspecialchars($s['password']) ?></td>
        <td><a class="delete-link" href="delete_student.php?id=<?= $s['id'] ?>" onclick="return confirm('Delete student?')">🗑️ Delete</a></td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<script>
  function toggleDropdown() {
    document.getElementById("dropdownMenu").classList.toggle("show-menu");
  }

  window.onclick = function(event) {
    if (!event.target.closest('.profile-dropdown')) {
      document.getElementById("dropdownMenu").classList.remove("show-menu");
    }
  }

  function showTab(id) {
    document.querySelectorAll('.container').forEach(el => el.classList.add('hidden'));
    document.getElementById(id).classList.remove('hidden');
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active'); 
  }

  function filterTable(input, tableId) {
    let filter = input.value.toUpperCase();
    let table = document.getElementById(tableId);
    let rows = table.getElementsByTagName("tr");
    for (let i = 1; i < rows.length; i++) {
      let show = false;
      let cells = rows[i].getElementsByTagName("td");
      for (let j = 0; j < cells.length; j++) {
        if (cells[j].innerText.toUpperCase().indexOf(filter) > -1) {
          show = true;
          break;
        }
      }
      rows[i].style.display = show ? "" : "none";
    }
  }
</script>
</body>
</html>
