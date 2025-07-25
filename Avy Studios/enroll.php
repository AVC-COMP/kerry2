<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Get the course ID from the URL
$course_id = $_GET['id'];
$username = $_SESSION['username'];

// Connect to the database
$db = new mysqli('localhost', 'root', '', 'avy_studios');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Get the user ID
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

// Check if the user is already enrolled
$sql = "SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("ii", $user_id, $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "You are already enrolled in this course.";
} else {
    // Enroll the user in the course
    $sql = "INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $user_id, $course_id);

    if ($stmt->execute()) {
        echo "You have successfully enrolled in the course!";
    } else {
        echo "Error: " . $sql . "<br>" . $db->error;
    }
}

$stmt->close();
$db->close();
?>
