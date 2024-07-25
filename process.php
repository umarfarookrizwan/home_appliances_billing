<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'] ?? '';
    $quantities = $_POST['quantities'] ?? [];
    $product_ids = $_POST['product_ids'] ?? [];
    $payment_method = $_POST['payment_method'] ?? '';

    if (!empty($customer_name) && !empty($quantities) && !empty($product_ids) && !empty($payment_method)) {
        $total_amount = 0;
        $tax_rate = 0.18;
        $items = [];

        // Calculate total amount and collect item details
        foreach ($product_ids as $index => $product_id) {
            $quantity = $quantities[$index] ?? 0;
            if ($quantity > 0) {
                $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
                if (!$stmt) {
                    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
                }
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($product = $result->fetch_assoc()) {
                    $item_total = $product['price'] * $quantity;
                    $total_amount += $item_total;

                    // Update sales count for the product and set last_sold to current date
                    $updateStmt = $conn->prepare("UPDATE products SET sales_count = sales_count + ?, last_sold = CURDATE() WHERE id = ?");
                    if (!$updateStmt) {
                        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
                    }
                    $updateStmt->bind_param("ii", $quantity, $product_id);
                    $updateStmt->execute();
                    $updateStmt->close();

                    $items[] = [
                        'name' => $product['name'],
                        'quantity' => $quantity,
                        'price' => $product['price'],
                        'total' => $item_total
                    ];
                }
                $stmt->close();
            }
        }

        if (!empty($items)) {
            $tax_amount = $total_amount * $tax_rate;
            $grand_total = $total_amount + $tax_amount;

            // Insert payment details with the date
            $payment_date = date('Y-m-d');
            $paymentStmt = $conn->prepare("INSERT INTO payments (customer_name, payment_method, total_amount, tax_amount, payment_date) VALUES (?, ?, ?, ?, ?)");
            if (!$paymentStmt) {
                die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }
            $paymentStmt->bind_param("sssds", $customer_name, $payment_method, $grand_total, $tax_amount, $payment_date);
            $paymentStmt->execute();
            $paymentStmt->close();

            // Display bill details to the user
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Bill Details</title>
                <link rel="stylesheet" href="styles.css">
            </head>
            <body>
                <div class="bill">
                    <h2>Bill Details</h2>
                    <p>Shop Name: Home Appliances Shop</p>
                    <p>Customer Name: <?= htmlspecialchars($customer_name) ?></p>
                    <p>Date: <?= date('Y-m-d') ?></p>
                    <table>
                        <tr><th>Item</th><th>Quantity</th><th>Price</th><th>Total</th></tr>
                        <?php foreach ($items as $item) { ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td><?= htmlspecialchars($item['quantity']) ?></td>
                                <td>₹<?= htmlspecialchars($item['price']) ?></td>
                                <td>₹<?= htmlspecialchars($item['total']) ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                    <p>Tax: ₹<?= htmlspecialchars($tax_amount) ?></p>
                    <p>Total Amount: ₹<?= htmlspecialchars($grand_total) ?></p>
                    <p>Payment Method: <?= htmlspecialchars($payment_method) ?></p>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "No valid products selected.";
        }
    } else {
        echo "Missing required fields.";
    }
} else {
    echo "Invalid request method.";
}
?>
