<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Appliances Billing</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Reset default margin and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styles */
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: start;
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }

        /* Page header styles */
        h1 {
            text-align: center;
            padding-top: 10px;
            margin-bottom: 20px;
            color: #333;
        }

        /* Form container styles */
        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Form input styles */
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"] {
            width: calc(100% - 10px);
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Button styles */
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        /* Table header styles */
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Link styles */
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #333;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Payment Action styles */
        .payment-action {
            margin-top: 20px;
            text-align: center;
        }

        .payment-action img {
            max-width: 200px;
            height: auto;
        }

        .payment-action .tick-mark {
            font-size: 24px;
            color: green;
        }
    </style>
    <script>
        function addProductRow(productId, productName, productPrice) {
            var table = document.getElementById("productTable");
            var row = table.insertRow();
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);

            cell1.innerHTML = productName;
            cell2.innerHTML = `<input type="hidden" name="product_ids[]" value="${productId}"><input type="number" name="quantities[]" min="1" value="1">`;
            cell3.innerHTML = productPrice;
            cell4.innerHTML = `<button type="button" onclick="removeProductRow(this)">Remove</button>`;
        }

        function removeProductRow(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }

        function scanProduct() {
            var productId = document.getElementById("barcode").value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var product = JSON.parse(this.responseText);
                    if (product) {
                        addProductRow(product.id, product.name, product.price);
                    } else {
                        alert("Product not found.");
                    }
                }
            };
            xhttp.open("GET", "get_product.php?id=" + productId, true);
            xhttp.send();
        }

        function handlePaymentMethod() {
            var paymentMethod = document.getElementById("payment_method").value;
            var paymentActionContainer = document.getElementById("paymentActionContainer");

            // Reset payment action container
            paymentActionContainer.innerHTML = "";

            // Show respective payment action based on selected method
            switch (paymentMethod) {
                case "cash":
                    paymentActionContainer.innerHTML = `<div class="payment-action"><span class="tick-mark">&#10004;</span> Cash Collected</div>`;
                    break;
                case "card":
                    paymentActionContainer.innerHTML = `<div class="payment-action"><span class="tick-mark">&#10004;</span> Transaction Received</div>`;
                    break;
                case "upi":
                    paymentActionContainer.innerHTML = `<div class="payment-action"><img src="path_to_qr_code.png" alt="UPI QR Code"><p>Scan QR Code for UPI Payment</p></div>`;
                    break;
                default:
                    break;
            }
        }
    </script>
</head>
<body>
    <h1>Home Appliances Billing System</h1>
    <a href="upload_form.php">Upload Products</a>
    <a href="daily_profit.php">Check Profit</a>
    <form action="process.php" method="post">
        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required><br><br>

        <label for="barcode">Scan Product Barcode:</label>
        <input type="text" id="barcode" name="barcode">
        <button type="button" onclick="scanProduct()">Add Product</button><br><br>

        <table id="productTable">
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </table><br>

        <label for="payment_method">Payment Method:</label>
        <select id="payment_method" name="payment_method" onchange="handlePaymentMethod()" required>
            <option value="">Select Payment Method</option>
            <option value="cash">Cash</option>
            <option value="card">Card</option>
            <option value="upi">UPI</option>
        </select><br><br>

        <!-- Container to display payment action -->
        <div id="paymentActionContainer"></div>

        <button type="submit">Generate Bill</button>
    </form>
</body>
</html>
