<?php
require 'db.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($file['name']);

    // Move uploaded file to the uploads directory
    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        // Load the spreadsheet
        $spreadsheet = IOFactory::load($uploadFile);
        $sheet = $spreadsheet->getActiveSheet();

        // Loop through each row of the spreadsheet
        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $cells = [];
            foreach ($cellIterator as $cell) {
                $cells[] = $cell->getValue();
            }

            // Assuming your columns are: ID, Name, Price
            $id = $cells[0];
            $name = $cells[1];
            $price = $cells[2];

            // Insert the product into the database
            $stmt = $conn->prepare("INSERT INTO products (id, name, price) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE name=?, price=?");
            $stmt->bind_param("issds", $id, $name, $price, $name, $price);
            $stmt->execute();
        }

        echo "Products have been uploaded successfully.";
    } else {
        echo "Failed to upload file.";
    }
} else {
    echo "Invalid request.";
}
?>
