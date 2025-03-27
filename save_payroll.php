<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

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

// Get the POST data
$inputData = json_decode(file_get_contents('php://input'), true);

// Check if input data exists
if (empty($inputData)) {
    echo json_encode(['status' => 'error', 'message' => 'No data received.']);
    exit;
}

// Extract data from the POST
$employeeId = $inputData['employee_id'];
$dailyRate = $inputData['daily_rate'];
$ratePerHour = $inputData['rate_per_hour'];
$noOfDays = $inputData['no_of_days'];
$noOfHours = $inputData['no_of_hours'];
$basicPay = $inputData['basic_pay'];

// Determine which payroll table to use
$sql = "SELECT branch FROM employees WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param('i', $employeeId);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if (!$employee) {
    echo json_encode(['status' => 'error', 'message' => 'Employee not found.']);
    exit;
}

// Based on the branch, choose the correct table
$payrollTable = null;
switch ($employee['branch']) {
    case 'STELLA':
        $payrollTable = 'stella_payroll';
        break;
    case 'DOIS':
        $payrollTable = 'dois_payroll';
        break;
    case 'PUB':
        $payrollTable = 'pub_payroll';
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid branch for payroll.']);
        exit;
}

// Prepare the SQL query to insert payroll data
$sql = "INSERT INTO $payrollTable (
                employee_id, daily_rate, rate_per_hour, no_of_days, no_of_hours, basic_pay
            ) VALUES (?, ?, ?, ?, ?, ?)";

// Prepare and bind the statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param(
    'iddddd',
    $employeeId, $dailyRate, $ratePerHour, $noOfDays, $noOfHours, $basicPay
);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Payroll data saved successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error saving payroll data: ' . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
