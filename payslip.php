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

// Fetch employee details (if an ID is provided)
if (isset($_GET['id'])) {
    $employeeId = $_GET['id'];

    // SQL query to fetch employee data
    $sql = "SELECT * FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $employeeId);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();
} else {
    // Redirect if no employee ID is provided
    header('Location: payroll_data.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Payslip</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        /* Main content styling */
        .main {
            margin-left: 400px; /* Ensure content doesn't overlap the sidebar */
            padding: 20px;
            background-color:rgb(255, 255, 255) ;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            flex-grow: 1;
            max-width: 1100px; /* Container to limit the width */
            margin: 300px; /* Center the content */
        }

        /* Header styling */
        .header {
            text-align: center;
            font-size: 22px; /* Slightly smaller header */
            margin-bottom: 20px;
        }

        .header img {
            width: 210px;
            margin-bottom: 10px;
        }

        .company-info {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .payroll-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .payroll-info div {
            width: 48%;
            margin-bottom: 10px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
            text-decoration: underline;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 8px 10px; /* Reduced padding for compactness */
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color:#d59090;
            font-weight: bold;
        }

        table td {
            background-color: #f9f9f9;
        }

        table tfoot td {
            font-weight: bold;
        }

        /* Footer Styling */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
        }

        .footer div {
            margin-top: 5px;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .payroll-info {
                flex-direction: column;
                align-items: flex-start;
            }

            .payroll-info div {
                width: 100%;
                margin-bottom: 15px;
            }

            .sidenav {
                width: 100%;
                position: static;
                height: auto;
            }

            .main {
                margin-left: 0;
                padding: 10px;
            }
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
        <a href="payroll_data.php" class="active">View Payroll Data</a>
        <a href="notification.php">Employee Evaluations</a>
        <a href="evaluated_employees.php">Evaluated Employees</a>
        <a href="employee_tracking.php">Employee Actions Tracking</a>
        <a href="logout.php" class="logout-button">Log Out</a>
    </div>

    <div class="main">
        <div class="header">
            <img src="images/Nina.jpg" alt="Company Logo">
            <h2>Nina Food Products Trading</h2>
        </div>

        <div class="company-info">
            <p>Mission Ext. Road Brgy. San Nicolas, Lapaz, Iloilo City</p>
            <p>Email: multipliesiilo@gmail.com | Tel No: 03315012981</p>
        </div>

        <div class="payroll-info">
            <div>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($employee['first_name'] ?? '', ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($employee['surname'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Period Covered:</strong> January 16-31, 2025</p>
          
            </div>
            <div>
                <p><strong>Position:</strong> <?php echo htmlspecialchars($employee['position'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                
            </div>
        </div>

        <!-- Earnings Section -->
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                    <td>No. of days:</td>
                    <td><?php echo number_format($employee['No_of_days'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>No. of hours:</td>
                    <td><?php echo number_format($employee['N_of_hours:'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>Basic Salary</td>
                    <td><?php echo number_format($employee['basic_pay'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>Overtime</td>
                    <td><?php echo number_format($employee['overtime'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>Holiday Pay 100%</td>
                    <td><?php echo number_format($employee['holiday_pay_100'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>Holiday Pay 30%</td>
                    <td><?php echo number_format($employee['holiday_pay_30'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>Allowance</td>
                    <td><?php echo number_format($employee['allowance'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td><strong>Gross Pay</strong></td>
                    <td><strong><?php echo number_format($employee['gross_pay'] ?? 0, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Deductions Section -->
        <div class="section-title">Deductions</div>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>SSS Contribution</td>
                    <td><?php echo number_format($employee['sss_contribution'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>HDMF Contribution</td>
                    <td><?php echo number_format($employee['hdmf_contribution'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>PHIC Contribution</td>
                    <td><?php echo number_format($employee['phic_contribution'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>SSS Loan</td>
                    <td><?php echo number_format($employee['sss_loan'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>HDMF Loan</td>
                    <td><?php echo number_format($employee['hdmf_loan'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>Insurance</td>
                    <td><?php echo number_format($employee['insurance'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>Advances</td>
                    <td><?php echo number_format($employee['advances'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>Bhouse</td>
                    <td><?php echo number_format($employee['bhouse'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>Others</td>
                    <td><?php echo number_format($employee['others'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>Uniform</td>
                    <td><?php echo number_format($employee['uniform'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td><strong>Total Deductions</strong></td>
                    <td><strong><?php echo number_format($employee['total_deductions'] ?? 0, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Adjustment Section -->
        <div class="section-title">Adjustments</div>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Adjustment</td>
                    <td><?php echo number_format($employee['adjustment'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td><strong>Net Pay</strong></td>
                    <td><strong><?php echo number_format($employee['net_pay'] ?? 0, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Footer Section -->
        <div class="footer">
            <div>Prepared by: [Name]</div>
            <div>Received by: [Employee's Name]</div>
        </div>
    </div>

</body>
</html>
