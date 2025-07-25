<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

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

// Update the user's plan
$sql = "UPDATE users SET plan_id = 2 WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo "You have successfully upgraded your plan!";
} else {
    echo "Error: " . $sql . "<br>" . $db->error;
}

$stmt->close();
$db->close();
?>
