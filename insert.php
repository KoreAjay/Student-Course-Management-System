<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "students_db";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Get form data
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$roll_no = $_POST['roll_no'];
$course = $_POST['course'];
$yearSemester = $_POST['yearsemester'];
$password = $_POST['password']; // For real apps, hash this!

// Handle Image Upload
$uploadDir = "uploads/";
if (!file_exists(filename: $uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$imageName = basename($_FILES["profile_image"]["name"]);
$imageTmpName = $_FILES["profile_image"]["tmp_name"];
$imagePath = $uploadDir . time() . "_" . $imageName;

// Move uploaded file
if (move_uploaded_file($imageTmpName, $imagePath)) {
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO registration (fullname, email, roll_no, course, yearSemister, password, profile_img) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $fullname, $email, $roll_no, $course, $yearSemester, $password, $imagePath);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Registered successfully!'); window.location.href = 'index.html';</script>";
    } else {
        echo "❌ Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "<script>alert('❌ Failed to upload image.'); window.history.back();</script>";
}

$conn->close();
?>
