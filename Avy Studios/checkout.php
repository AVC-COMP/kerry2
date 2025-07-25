<?php
session_start();

// Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
    exit;
}

// Connect to the database
$db = new mysqli('localhost', 'root', '', 'avy_studios');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Get the products in the cart
$ids = implode(',', array_keys($_SESSION['cart']));
$sql = "SELECT * FROM products WHERE id IN ($ids)";
$result = $db->query($sql);

$total = 0;
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $total += $row['price'] * $_SESSION['cart'][$row['id']];
    }
}

echo "Total: $" . $total;

// Clear the cart
$_SESSION['cart'] = array();

$db->close();
?>
