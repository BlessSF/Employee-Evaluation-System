<?php 
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Payroll page logic will go here (e.g., file upload handling, processing logic in the future)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Data Upload</title>
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
<!-- Side Navigation -->
<div class="sidenav">
    <div class="logo-container">
        <img src="images/Logo.jpg" alt="Nina Trading Logo" class="logo">
    </div>
    <a href="index.php">Dashboard</a>
    <a href="employees.php">Probationary Employees</a>
    <a href="regular_employees.php">Regular Employees</a>
    <a href="upload.php">Upload Employee Data</a>
    <a href="payroll_upload.php" class="active">Upload Payroll Data</a>
    <a href="notification.php">Employee Evaluations</a>
    <a href="evaluated_employees.php">Evaluated Employees</a>
    <a href="employee_tracking.php">Employee Actions Tracking</a>
    <a href="logout.php" class="logout-button">Log Out</a>
</div>

<div class="main">
    <header>
        <h1>Payroll Data Upload</h1>
    </header>
    <section>
        <form action="payroll_upload.php" method="post" enctype="multipart/form-data">
            <label for="excel_file">Choose Payroll Excel File:</label>
            <input type="file" name="excel_file" id="excel_file" accept=".xls,.xlsx" required>
            <button type="submit">Upload Payroll Data</button>
        </form>
    </section>
</div>

</body>
</html>
