<?php
require 'db.php';

$product_id = $_GET['id'];
$sql = "SELECT id, name, price FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($product = $result->fetch_assoc()) {
    echo json_encode($product);
} else {
    echo json_encode(null);
}

$stmt->close();
$conn->close();
?>
