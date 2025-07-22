<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM incoming WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: incoming_table.php");
    exit;
} else {
    echo "No ID provided.";
}
