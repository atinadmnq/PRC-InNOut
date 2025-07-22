<?php
require 'db_connect.php';

$username = $_POST['user'] ?? '';
$password = $_POST['pass'] ?? '';

if ($username && $password) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed);

    if ($stmt->execute()) {
        echo "User saved successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Username and password are required.";
}

$conn->close();
?>
