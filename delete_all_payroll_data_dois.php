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

// Delete all payroll data for DOIS branch
$sql = "DELETE FROM dois_payroll WHERE employee_id IN (SELECT id FROM employees WHERE branch = 'DOIS')";

if ($conn->query($sql) === TRUE) {
    echo "All payroll data for DOIS branch has been cleared!";
} else {
    echo "Error clearing data: " . $conn->error;
}

// Close the connection
$conn->close();
?>
