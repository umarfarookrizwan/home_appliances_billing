<?php
require 'db.php';

$date = $_GET['date'] ?? date('Y-m-d');

// Query to get the total profit for the specified date
$stmt = $conn->prepare("SELECT SUM(total_amount) as total_profit FROM payments WHERE DATE(payment_date) = ?");
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();
$total_profit = $result->fetch_assoc()['total_profit'] ?? 0;
$stmt->close();

// Query to get the number of products sold for the specified date
$stmt = $conn->prepare("SELECT SUM(sales_count) as total_products_sold FROM products WHERE DATE(last_sold) = ?");
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();
$total_products_sold = $result->fetch_assoc()['total_products_sold'] ?? 0;
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Profit</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="daily-profit">
        <h2>Daily Profit for <?= htmlspecialchars($date) ?></h2>
        <p>Total Profit: ₹<?= htmlspecialchars($total_profit) ?></p>
        <p>Total Products Sold: <?= htmlspecialchars($total_products_sold) ?></p>
    </div>
</body>
</html>
