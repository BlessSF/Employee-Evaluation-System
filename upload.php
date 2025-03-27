<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file'];

    // Check for upload errors
    if ($file['error'] !== 0) {
        die("Error uploading the file. Please try again.");
    }

    // Ensure the uploads directory exists
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filePath = $uploadDir . basename($file['name']);

    // Move uploaded file to the uploads directory
    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        die("Failed to save the uploaded file.");
    }

    try {
        // Load the Excel file
        $spreadsheet = IOFactory::load($filePath);
        $sheetNames = $spreadsheet->getSheetNames(); // Get all worksheet names

        // Connect to the database
        $conn = new mysqli('localhost', 'root', '', 'employee_evaluation');
        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }

        foreach ($sheetNames as $branchName) {
            $sheet = $spreadsheet->getSheetByName($branchName);
            $headerRow = $sheet->rangeToArray('A1:' . $sheet->getHighestColumn() . '1')[0];

            foreach ($headerRow as $key => $value) {
                $headerRow[$key] = is_string($value) ? strtolower(trim($value)) : ''; // Normalize header names
            }

            // Map the columns based on normalized header names
            $columns = array_flip($headerRow);

            // Check if required columns exist
            if (!isset($columns['surname'], $columns['first name'], $columns['date hired'], $columns['employment status'], $columns['group'], $columns['position'])) {
                die("Error: Required columns (Surname, First Name, Date Hired, Employment Status, Group, Position) not found in the Excel file.");
            }

            // Iterate through rows, starting at row 2 (data rows)
            foreach ($sheet->getRowIterator(2) as $row) {
                $rowIndex = $row->getRowIndex();
                $rowData = $sheet->rangeToArray('A' . $rowIndex . ':' . $sheet->getHighestColumn() . $rowIndex)[0];

                $surname = $rowData[$columns['surname']] ?? null;
                $first_name = $rowData[$columns['first name']] ?? null;
                $date_hired = $rowData[$columns['date hired']] ?? null;
                $employment_status = strtolower(trim($rowData[$columns['employment status']] ?? ''));
                $group = $rowData[$columns['group']] ?? null;
                $position = $rowData[$columns['position']] ?? null; // NEW: Extract Position

                // Determine branch and sub-branch
                if ($branchName === 'PUB' && ($employment_status === 'probationary' || $employment_status === 'regular')) {
                    $branch = 'PUB';
                    $sub_branch = $group;
                } else if ($employment_status === 'probationary' || $employment_status === 'regular') {
                    $branch = $branchName;
                    $sub_branch = null;
                } else {
                    continue; // Skip rows that don't meet criteria
                }

                // Validate data
                if (empty($surname) || empty($first_name) || empty($date_hired)) {
                    continue; // Skip rows with missing data
                }

                // Check for duplicates
                $check_query = "SELECT id FROM employees WHERE surname = ? AND first_name = ? AND date_hired = ? AND branch = ?";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bind_param('ssss', $surname, $first_name, $date_hired, $branch);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();

                if ($check_result->num_rows > 0) {
                    continue; // Skip duplicate entries
                }

                // Insert into the database (Now includes Position)
                $stmt = $conn->prepare("INSERT INTO employees (surname, first_name, date_hired, status, branch, sub_branch, position) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('sssssss', $surname, $first_name, $date_hired, $employment_status, $branch, $sub_branch, $position);
                $stmt->execute();
            }
        }

        echo "<div class='success'>Employee data uploaded and saved successfully!</div>";
    } catch (Exception $e) {
        die("Error processing the Excel file: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Employee Data</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        h1, h2 {
            color: white;
            text-align: center;
        }
        form {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f2f2f2;
            border-radius: 10px;
            box-shadow: 0px 2px 6px rgba(230, 225, 225, 0.1);
        }
        label {
            font-weight: bold;
            color: #555;
        }
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .success {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="sidenav">
    <div class="logo-container">
        <img src="images/Logo.jpg" alt="Nina Trading Logo" class="logo">
    </div>
    <a href="index.php">Dashboard</a>
    <a href="employees.php">Probationary Employees</a>
    <a href="regular_employees.php">Regular Employees</a>
    <a href="upload.php" class="active">Upload Employee Data</a>
    <a href="notification.php">Employee Evaluations</a>
    <a href="evaluated_employees.php" >Evaluated Employees</a>
    <a href="employee_tracking.php">Employee Actions Tracking</a>
    <a href="logout.php" class="logout-button">Log Out</a>
</div>
<div class="main">
    <header>
        <h1>Upload Employee Data</h1>
    </header>
    <section>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="excel_file">Choose Excel File:</label>
            <input type="file" name="excel_file" id="excel_file" accept=".xls,.xlsx" required>
            <button type="submit">Upload</button>
        </form>
    </section>
</div>
</body>
</html>
