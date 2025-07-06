<?php
session_start();

// DB Config
$servername = "localhost";
$username = "root";
$password = "";
$database = "students_db";

// Connect to DB
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = trim($_POST['email']); // Could be email or roll_no
    $password_input = trim($_POST['password']);

    // Query: match by email or roll_no
    $sql = "SELECT * FROM registration WHERE email = '$user_input' OR roll_no = '$user_input'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Debug output (temporary only):
        // echo "Input: $password_input - DB: " . $row['password'];

        if ($password_input === $row['password']) {
            // Set session
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['roll_no'] = $row['roll_no'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['course'] = $row['course'];
            $_SESSION['semester'] = $row['yearSemister'];

            // Go to home
            header("Location: home.html");
            exit();
        } else {
            echo "<script>alert('❌ Incorrect password'); window.location.href='index.html';</script>";
        }
    } else {
        echo "<script>alert('❌ User not found'); window.location.href='index.html';</script>";
    }
} else {
    echo "Invalid access method.";
}

mysqli_close($conn);
?>
