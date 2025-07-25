<?php
// Connect to the database
$db = new mysqli('localhost', 'root', '', 'avy_studios');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Get the form data
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert the user into the database
$sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
$stmt = $db->prepare($sql);
$stmt->bind_param("sss", $username, $hashed_password, $email);

if ($stmt->execute()) {
    echo "Registration successful!";
} else {
    echo "Error: " . $sql . "<br>" . $db->error;
}

$stmt->close();
$db->close();
?>
