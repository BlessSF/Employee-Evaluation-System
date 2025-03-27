<?php
// Database connection details
$host = 'localhost';
$dbname = 'employee_evaluation';
$username = 'root';
$password = '';

// Create the database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check for database connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);  
}

// Delete all payroll data for PUB branch
$sql = "DELETE FROM pub_payroll WHERE employee_id IN (SELECT id FROM employees WHERE branch = 'PUB')";

if ($conn->query($sql) === TRUE) {
    echo "All payroll data for PUB branch has been cleared!";
} else {
    echo "Error clearing data: " . $conn->error;
}

// Close the connection
$conn->close();
?>
