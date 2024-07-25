<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Products</title>
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

/* Page container styles */
.container {
    max-width: 600px;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Header styles */
h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

/* Form styles */
form {
    text-align: center;
}

/* Label styles */
label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
}

/* File input styles */
input[type="file"] {
    display: block;
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* Button styles */
button[type="submit"] {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button[type="submit"]:hover {
    background-color: #45a049;
}

    </style>
</head>
<body>
    <h1>Upload Products Excel File</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="file">Choose Excel File:</label>
        <input type="file" name="file" id="file" accept=".xlsx, .xls" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
