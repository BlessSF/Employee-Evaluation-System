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

// Database connection
$host = 'localhost';
$dbname = 'employee_evaluation';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get employee ID from URL or set a default
$employee_id = isset($_GET['id']) ? intval($_GET['id']) : 1977;

// Fetch employee details
$emp_sql = "SELECT * FROM employees WHERE id = ?";
$emp_stmt = $conn->prepare($emp_sql);
$emp_stmt->bind_param("i", $employee_id);
$emp_stmt->execute();
$emp_result = $emp_stmt->get_result();
$employee = $emp_result->fetch_assoc();

// Fetch payroll details
$pay_sql = "SELECT * FROM pub_payroll WHERE employee_id = ?";
$pay_stmt = $conn->prepare($pay_sql);
$pay_stmt->bind_param("i", $employee_id);
$pay_stmt->execute();
$pay_result = $pay_stmt->get_result();
$payroll = $pay_result->fetch_assoc();

// Function to format values (display "-" if NULL)
function format_value($value) {
    return isset($value) ? number_format($value, 2) : "-";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - Stella Fusion Buffet</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .download-button {
    display: block;
    width: 250px;
    background-color: #007bff;
    color: white;
    padding: 12px;
    font-size: 16px;
    border: none;
    cursor: pointer;
    text-align: center;
    margin: 20px auto; /* Centers it below */
    border-radius: 5px;
}
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(251, 219, 207);
            margin: 20px;
            padding: 20px;

        }

        .payslip-container {
            width: 450px;
            margin: auto;
            border: 1px solid #000;
            padding: 20px;
            background-color: white;
        }

        .company-header {
            text-align: center;
            font-size: 14px;
            margin-bottom: 10px;
            position: relative;
        }

        .logo1 {
            position: absolute;
            left: 0;
            top: 0;
            width: 60px;
            height: auto;
        }

        .payslip-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .employee-details {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .table-section {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .table-section th, .table-section td {
            padding: 5px;
            text-align: left;
        }

        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 15px;
            font-size: 14px;
        }

        .total {
            font-weight: bold;
        }

        .signature-section {
            margin-top: 20px;
            font-size: 14px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin-top: 5px;
        }
        table th{
            background-color: white;
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
        <a href="payroll_upload.php">Upload Payroll Data</a>
        <a href="stella_payroll.php" class="active">View Stella Payroll</a>
        <a href="notification.php">Employee Evaluations</a>
        <a href="evaluated_employees.php">Evaluated Employees</a>
        <a href="employee_tracking.php">Employee Actions Tracking</a>
        <a href="logout.php" class="logout-button">Log Out</a>
    </div>

    <div class="payslip-container">
    <div class="company-header">
        <img src="images/Pub.png" alt="Pub Logo" class="logo1">
        <strong>PUB EXPRESS RESTO </strong><br>
        Annex Sm City Mandurriao, Iloilo City<br>
        Email: pubexpresss@gmail.com<br>
        Tel No.: (033)501-2981<br>
        
    </div>

    <div class="payslip-title">PAYSLIP</div>

    <div class="employee-details">
        <strong>NAME:</strong> <?= htmlspecialchars($employee['surname'] . ', ' . $employee['first_name']); ?><br>
        <strong>PERIOD COVERED:</strong> JANUARY 16-31, 2025
    </div>

    <table class="table-section">
        <tr class="section-title"><td colspan="2">Earnings</td></tr>
        <tr><th>No. of Days:</th><td><?= format_value($payroll['no_of_days'] ?? null); ?></td></tr>
        <tr><th>No. of Hours:</th><td><?= format_value($payroll['no_of_hours'] ?? null); ?></td></tr>
        <tr><th>Amount (Leave WI Pay):</th><td><?= format_value($payroll['leave_with_pay'] ?? null); ?></td></tr>
        <tr><th>Basic Salary:</th><td><?= format_value($payroll['basic_pay'] ?? null); ?></td></tr>
        <tr><th>Total/OVT:</th><td><?= format_value($payroll['overtime'] ?? null); ?></td></tr>
        <tr><th>10% (Night Diff):</th><td><?= format_value($payroll['night_diff'] ?? null); ?></td></tr>
        <tr><th>Holiday Pay 100%:</th><td><?= format_value($payroll['hundred_percent'] ?? null); ?></td></tr>
        <tr><th>Holiday Pay 30%:</th><td><?= format_value($payroll['thirty_percent'] ?? null); ?></td></tr>
        <tr><th>Allowance:</th><td><?= format_value($payroll['allowance'] ?? null); ?></td></tr>
        <tr class="total"><th>Gross Pay:</th><td><?= format_value($payroll['gross_pay'] ?? null); ?></td></tr>
    </table>

    <table class="table-section">
        <tr class="section-title"><td colspan="2">Deductions</td></tr>
        <tr><th>SSS Contribution:</th><td><?= format_value($payroll['sss_d'] ?? null); ?></td></tr>
        <tr><th>PAG-IBIG Contribution:</th><td><?= format_value($payroll['hdmf_contribution'] ?? null); ?></td></tr>
        <tr><th>PHIC Contribution:</th><td><?= format_value($payroll['phic'] ?? null); ?></td></tr>
        <tr><th>SSS Loan:</th><td><?= format_value($payroll['sss_loan'] ?? null); ?></td></tr>
        <tr><th>HDMF Loan:</th><td><?= format_value($payroll['hdmf_loan'] ?? null); ?></td></tr>
        <tr><th>Insurance:</th><td><?= format_value($payroll['insurance'] ?? null); ?></td></tr>
        <tr><th>Advances:</th><td><?= format_value($payroll['cash_advance'] ?? null); ?></td></tr>
        <tr><th>Others:</th><td><?= format_value($payroll['others'] ?? null); ?></td></tr>
        <tr class="total"><th>Total Deductions:</th><td><?= format_value($payroll['total_deductions'] ?? null); ?></td></tr>
    </table>

    <table class="table-section">
        <tr><th>Adjustment:</th><td><?= format_value($payroll['adjustment'] ?? null); ?></td></tr>
        <tr class="total"><th>Net Pay:</th><td><?= format_value($payroll['net_pay'] ?? null); ?></td></tr>
    </table>

    <!-- Signature Section -->
    <div class="signature-section">
        <table class="table-section">
        <tr><th>Prepared by:  _______________________</th></tr>
        <tr><th>Received by:  _______________________</th></tr>
        </table>
    </div>
</div>


    <!-- Download Button -->
    <form action="download_payslip_pub.php" method="GET">
        <input type="hidden" name="id" value="<?= htmlspecialchars($employee_id); ?>">
        <button type="submit" class="download-button">Download Payslip (PDF)</button>
    </form>

</div>

</body>
</html>

<?php
$conn->close();
?>
