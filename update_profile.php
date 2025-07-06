<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "students_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email     = $_SESSION['email'];
$fullname  = $_POST['fullname'];
$roll_no   = $_POST['roll_no'];
$course    = $_POST['course'];
$semester  = $_POST['yearSemister'];

// Handle image upload
$profile_img_path = null;
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $img_tmp  = $_FILES['profile_image']['tmp_name'];
    $img_name = basename($_FILES['profile_image']['name']);
    $img_ext  = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
    $allowed  = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($img_ext, $allowed)) {
        $target_dir  = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $new_filename = uniqid("profile_", true) . "." . $img_ext;
        $target_file  = $target_dir . $new_filename;

        if (move_uploaded_file($img_tmp, $target_file)) {
            $profile_img_path = $target_file;
        }
    }
}

// Update query
if ($profile_img_path) {
    $stmt = $conn->prepare("UPDATE registration SET fullname=?, roll_no=?, course=?, yearSemister=?, profile_img=? WHERE email=?");
    $stmt->bind_param("ssssss", $fullname, $roll_no, $course, $semester, $profile_img_path, $email);
} else {
    $stmt = $conn->prepare("UPDATE registration SET fullname=?, roll_no=?, course=?, yearSemister=? WHERE email=?");
    $stmt->bind_param("sssss", $fullname, $roll_no, $course, $semester, $email);
}

if ($stmt->execute()) {
    // Optional: Update session variables if used elsewhere
    $_SESSION['fullname']  = $fullname;
    $_SESSION['roll_no']   = $roll_no;
    $_SESSION['course']    = $course;
    $_SESSION['semester']  = $semester;
    if ($profile_img_path) {
        $_SESSION['profile_img'] = $profile_img_path;
    }

    echo "<script>alert('Profile updated successfully'); window.location.href='profile.php';</script>";
} else {
    echo "<script>alert('Update failed: " . $stmt->error . "'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
