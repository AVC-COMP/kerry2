<?php
session_start();

// Connect to the database
$db = new mysqli('localhost', 'root', '', 'avy_studios');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Get the form data
$username = $_POST['username'];
$password = $_POST['password'];

// Get the user from the database
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Verify the password
if ($user && password_verify($password, $user['password'])) {
    // Set the session variables
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username;
    echo "Login successful!";
} else {
    echo "Invalid username or password";
}

$stmt->close();
$db->close();
?>
