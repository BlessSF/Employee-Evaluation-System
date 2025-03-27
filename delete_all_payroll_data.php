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

// SQL query to delete all payroll data
$sql = "DELETE FROM stella_payroll";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "All payroll data has been deleted";
} else {
    echo "Error deleting data: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
