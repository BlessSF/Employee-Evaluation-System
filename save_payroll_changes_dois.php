<?php 
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Ensure necessary columns exist
$columns = ['payslip_status', 'thirty_percent', 'gross_pay', 'sss_loan', 'sss_d', 'phic', 'pag_ibig', 'insurance', 'others', 'cp', 'cash_advance', 'total_deductions', 'adjustment', 'net_pay'];

foreach ($columns as $column) {
    $checkColumn = $conn->query("SHOW COLUMNS FROM dois_payroll LIKE '$column'");
    if ($checkColumn->num_rows == 0) {
        $conn->query("ALTER TABLE dois_payroll ADD COLUMN $column VARCHAR(255) NOT NULL DEFAULT 'ONGOING'");
    }
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debugging: log the POST data
    file_put_contents('debug_log.txt', print_r($_POST, true), FILE_APPEND);

    // Retrieve and sanitize POST data
    $employeeId = $_POST['employee_id'] ?? null;
    $payslipStatus = 'DONE'; // Auto-update to DONE
    $adjustment = $_POST['adjustment'] ?? 0; 
    $netPay = $_POST['net_pay'] ?? 0; // New Net Pay field

    $dailyRate = $_POST['daily_rate'] ?? 0;
    $ratePerHour = $_POST['rate_per_hour'] ?? 0;
    $noOfDays = $_POST['no_of_days'] ?? 0;
    $noOfHours = $_POST['no_of_hours'] ?? 0;
    $basicPay = $_POST['basic_pay'] ?? 0;
    $days = $_POST['days'] ?? 0;
    $amount = $_POST['amount'] ?? 0;    
    $rateOVT = $_POST['rate_ovt'] ?? 0;
    $hourOVT = $_POST['hour_ovt'] ?? 0;
    $totalOVT = $_POST['total_ovt'] ?? 0;
    $noOfDaysTR = $_POST['no_of_days_tr'] ?? 0;
    $traineeAllowance = $_POST['trainee_allowance'] ?? 0;
    $allowance = $_POST['allowance'] ?? 0;
    $medicalReimbursement = $_POST['medical_reimbursement'] ?? 0;
    $hoursND = $_POST['hours_nd'] ?? 0;
    $tenPercent = $_POST['ten_percent'] ?? 0;
    $hoursHP = $_POST['hours_hp'] ?? 0;
    $hundredPercent = $_POST['hundred_percent'] ?? 0;
    $hoursHDP = $_POST['hours_hdp'] ?? 0;
    $thirtyPercent = $_POST['thirty_percent'] ?? 0;
    $grossPay = $_POST['gross_pay'] ?? 0;
    $sssLoan = $_POST['sss_loan'] ?? 0;
    $sssD = $_POST['sss_d'] ?? 0;
    $phic = $_POST['phic'] ?? 0;
    $pagIbig = $_POST['pag_ibig'] ?? 0;
    $insurance = $_POST['insurance'] ?? 0;
    $others = $_POST['others'] ?? 0;
    $cp = $_POST['cp'] ?? 0;
    $cashAdvance = $_POST['cash_advance'] ?? 0;
    $totalDeductions = $_POST['total_deductions'] ?? 0;
    $adjustment = $_POST['adjustment'] ?? 0;
    $netPay = $_POST['net_pay'] ?? 0;

    // Validate required numeric fields
    $numericFields = [
        $employeeId, $dailyRate, $ratePerHour, $noOfDays, $noOfHours, $basicPay, 
        $days, $amount, $rateOVT, $hourOVT, $totalOVT, $noOfDaysTR, 
        $traineeAllowance, $allowance, $medicalReimbursement, $hoursND, 
        $tenPercent, $hoursHP, $hundredPercent, $hoursHDP, $thirtyPercent, 
        $grossPay, $sssLoan, $sssD, $phic, $pagIbig, $insurance, $others, $cp, 
        $cashAdvance, $totalDeductions, $adjustment, $netPay // Added Net Pay
    ];

    foreach ($numericFields as $val) {
        if (!is_numeric($val)) {
            echo "Error: Invalid numeric values.";
            exit;
        }
    }

    // Use ON DUPLICATE KEY UPDATE to avoid redundant SELECT queries
    $sql = "INSERT INTO dois_payroll (
                employee_id, 
                daily_rate, 
                rate_per_hour, 
                no_of_days, 
                no_of_hours, 
                basic_pay, 
                days, 
                amount, 
                rate_ovt, 
                hour_ovt, 
                total_ovt, 
                no_of_days_tr, 
                trainee_allowance, 
                allowance,
                medical_reimbursement,
                hours_nd,
                ten_percent,
                hours_hp,
                hundred_percent,
                hours_hdp,
                thirty_percent,
                gross_pay,
                sss_loan,
                sss_d,
                phic,
                pag_ibig,
                insurance,
                others,
                cp,
                cash_advance,
                total_deductions,
                adjustment,
                net_pay,
                payslip_status  -- Update payslip_status column
            ) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                daily_rate = VALUES(daily_rate), 
                rate_per_hour = VALUES(rate_per_hour),
                no_of_days = VALUES(no_of_days),
                no_of_hours = VALUES(no_of_hours),
                basic_pay = VALUES(basic_pay),
                days = VALUES(days),
                amount = VALUES(amount),
                rate_ovt = VALUES(rate_ovt),
                hour_ovt = VALUES(hour_ovt),
                total_ovt = VALUES(total_ovt),
                no_of_days_tr = VALUES(no_of_days_tr),
                trainee_allowance = VALUES(trainee_allowance),
                allowance = VALUES(allowance),
                medical_reimbursement = VALUES(medical_reimbursement),
                hours_nd = VALUES(hours_nd),
                ten_percent = VALUES(ten_percent),
                hours_hp = VALUES(hours_hp),
                hundred_percent = VALUES(hundred_percent),
                hours_hdp = VALUES(hours_hdp),
                thirty_percent = VALUES(thirty_percent),
                gross_pay = VALUES(gross_pay),
                sss_loan = VALUES(sss_loan),
                sss_d = VALUES(sss_d),
                phic = VALUES(phic),
                pag_ibig = VALUES(pag_ibig),
                insurance = VALUES(insurance),
                others = VALUES(others),
                cp = VALUES(cp),
                cash_advance = VALUES(cash_advance),
                total_deductions = VALUES(total_deductions),
                adjustment = VALUES(adjustment),
                net_pay = VALUES(net_pay),
                payslip_status = VALUES(payslip_status)"; // Automatically updates to "DONE"

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param(
            'ddddddddddddddddddddddddddddddddds', // One more 's' for payslip_status
            $employeeId, $dailyRate, $ratePerHour, $noOfDays, $noOfHours, 
            $basicPay, $days, $amount, $rateOVT, $hourOVT, $totalOVT, 
            $noOfDaysTR, $traineeAllowance, $allowance, $medicalReimbursement, 
            $hoursND, $tenPercent, $hoursHP, $hundredPercent, $hoursHDP, 
            $thirtyPercent, $grossPay, $sssLoan, $sssD, $phic, $pagIbig, 
            $insurance, $others, $cp, $cashAdvance, $totalDeductions, $adjustment, $netPay, $payslipStatus
        );

        $stmt->execute();
        echo "Payroll data successfully saved and updated to DONE.";
        $stmt->close();
    }
}

$conn->close();
?>
