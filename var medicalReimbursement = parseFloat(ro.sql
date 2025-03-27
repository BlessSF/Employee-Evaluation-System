var medicalReimbursement = parseFloat(row.querySelector('[name="medical_reimbursement"]').value) || 0;
            var hoursND = parseFloat(row.querySelector('[name="hours_nd"]').value) || 0;
            var percent10 = parseFloat(row.querySelector('[name="percent10"]').value) || 0;
            var hoursHP = parseFloat(row.querySelector('[name="hours_hp"]').value) || 0;
            var percent100 = parseFloat(row.querySelector('[name="percent100"]').value) || 0;

            // New columns after ALLOW
            echo "<td><input type='number' name='medical_reimbursement' value='0' /></td>";
            echo "<td><input type='number' name='hours_nd' value='0' /></td>";
            echo "<td><input type='number' name='percent10' value='0' /></td>";
            echo "<td><input type='number' name='hours_hp' value='0' /></td>";
            echo "<td><input type='number' name='percent100' value='0' /></td>";
            th>Medical Reimbursement</th>
                    <th>Hours/ND</th>
                    <th>10%</th>
                    <th>Hours/HP</th>
                    <th>100%</th>



                    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            margin: 0;
            height: 100vh;
        }

        .main {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
            overflow-x: auto;
        }

        .download-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            transition: background-color 0.3s ease;
            margin-top: 5px;
        }

        .download-btn:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            table-layout: fixed;
        }

        thead {
            background-color: #007bff;
            color: white;
        }

        th, td {
            padding: 20px;
            border-bottom: 5px solid #ddd;
            width: 200px;
            text-align: left;
        }

        th {
            font-weight: bold;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidenav {
                position: static;
                width: 100%;
                height: auto;
            }

            .main {
                margin-left: 0;
                padding: 10px;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>





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

// Search functionality (if search query is provided)
$searchQuery = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = $_GET['search'];

    // SQL query to fetch PUB employees and their payroll data
    $sql = "SELECT e.id, e.surname, e.first_name, e.position, e.status, e.branch, 
                   p.daily_rate, p.rate_per_hour, p.no_of_days, p.no_of_hours, p.basic_pay
            FROM employees e
            LEFT JOIN pub_payroll p ON e.id = p.employee_id
            WHERE (e.first_name LIKE ? OR e.surname LIKE ?)
            AND e.branch = 'PUB' AND e.branch != 'STELLA' AND e.branch != 'DOIS'";

    $stmt = $conn->prepare($sql);
    $likeSearchQuery = "%$searchQuery%";
    $stmt->bind_param('ss', $likeSearchQuery, $likeSearchQuery);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // SQL query to fetch PUB employees and their payroll data
    $sql = "SELECT e.id, e.surname, e.first_name, e.position, e.status, e.branch, 
                   p.daily_rate, p.rate_per_hour, p.no_of_days, p.no_of_hours, p.basic_pay
            FROM employees e
            LEFT JOIN pub_payroll p ON e.id = p.employee_id
            WHERE e.branch = 'PUB' AND e.branch != 'STELLA' AND e.branch != 'DOIS'";

    $result = $conn->query($sql);
}

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Helper function to safely apply htmlspecialchars
function safe_htmlspecialchars($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Helper function to capitalize the first letter of each word safely
function ucfirst_safe($value) {
    return ucfirst(htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Data</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            margin: 0;
            height: 100vh;
        }

        .main {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
            overflow-x: auto;
        }

        .download-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            transition: background-color 0.3s ease;
            margin-top: 5px;
        }

        .download-btn:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            table-layout: fixed;
        }

        thead {
            background-color: #007bff;
            color: white;
        }

        th, td {
            padding: 20px;
            border-bottom: 5px solid #ddd;
            width: 200px;
            text-align: left;
        }

        th {
            font-weight: bold;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidenav {
                position: static;
                width: 100%;
                height: auto;
            }

            .main {
                margin-left: 0;
                padding: 10px;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
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
        <header>
            <h1>Payroll Data</h1>
        </header>

        <!-- Search Bar -->
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search by employee name..." onkeyup="searchEmployee()">
        </div>

        <!-- Main Employee Table -->
        <form action="save_payroll.php" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Daily Rate</th>
                        <th>Rate/HR</th>
                        <th>No. of Days</th>
                        <th>No. of Hours</th>
                        <th>Basic Pay</th>
                        <th>DAYS</th>
                        <th>AMOUNT</th>
                        <th>RATE</th>
                        <th>HOUR</th>
                        <th>TOTAL</th>
                        <th>ALLOW</th>
                        <th>Medical Reimbursement</th>
                        <th>Hours/ND</th>
                        <th>10%</th>
                        <th>Hours/HP</th>
                        <th>100%</th>
                        <th>HOURS/HD</th>
                        <th>30%</th>
                        <th>Gross Pay</th>
                        <th>PHILHEALTH</th>
                        <th>SSS LOAN</th>
                        <th>SSS</th>
                        <th>HDMF</th>
                        <th>HDMF LOAN</th>
                        <th>INSURANCE</th>
                        <th>CASH ADVANCE</th>
                        <th>BHOUSE</th>
                        <th>Total Deductions</th>
                        <th>Leave with Pay</th>
                        <th>Incentives</th>
                        <th>Net Pay</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><input type='text' name='employee_name[]' value='" . ucfirst_safe($row['first_name']) . " " . ucfirst_safe($row['surname']) . "' disabled /></td>";
                        echo "<td><input type='number' name='daily_rate[]' value='" . (isset($row['daily_rate']) ? $row['daily_rate'] : '0.00') . "' /></td>";
                        echo "<td><input type='number' name='rate_per_hour[]' value='0' readonly /></td>";
                        echo "<td><input type='number' name='no_of_days[]' value='" . (isset($row['no_of_days']) ? $row['no_of_days'] : '0') . "' /></td>";
                        echo "<td><input type='number' name='no_of_hours[]' value='0' readonly /></td>";
                        echo "<td><input type='number' name='basic_pay[]' value='0' readonly /></td>";
                        echo "<td><input type='number' name='days[]' value='0' /></td>";
                        echo "<td><input type='number' name='amount[]' value='0' readonly /></td>";
                        echo "<td><input type='number' name='rate[]' value='0' readonly /></td>";
                        echo "<td><input type='number' name='hour[]' value='0' /></td>";
                        echo "<td><input type='number' name='total[]' value='0' readonly /></td>";
                        echo "<td><input type='number' name='allow[]' value='0' readonly /></td>";
                        echo "<td><input type='number' name='medical_reimbursement[]' value='0' /></td>";
                        echo "<td><input type='number' name='hours_nd[]' value='0' /></td>";
                        echo "<td><input type='number' name='percent10[]' value='0' readonly /></td>";
                        echo "<td><input type='number' name='hours_hp[]' value='0' /></td>";
                        echo "<td><input type='number' name='percent100[]' value='0' readonly /></td>";
                        echo "<td><input type='number' name='hours_hd[]' value='0' /></td>";
                        echo "<td><input type='number' name='percent30[]' value='0' readonly /></td>";
                        echo "<td><input type='number' name='gross_pay[]' value='0' readonly /></td>";
                        echo "<td><input type='number' name='philhealth[]' value='0' /></td>";
                        echo "<td><input type='number' name='sss_loan[]' value='0' /></td>";
                        echo "<td><input type='number' name='sss[]' value='0' /></td>";
                        echo "<td><input type='number' name='hdmf[]' value='0' /></td>";
                        echo "<td><input type='number' name='hdmf_loan[]' value='0' /></td>";
                        echo "<td><input type='number' name='insurance[]' value='0' /></td>";
                        echo "<td><input type='number' name='cash_advance[]' value='0' /></td>";
                        echo "<td><input type='number' name='bhouse[]' value='0' /></td>";
                        echo "<td><input type='number' name='total_deductions[]' value='0' readonly /></td>";
                        echo "<td><input type='number' name='leave_with_pay[]' value='0' /></td>";
                        echo "<td><input type='number' name='incentives[]' value='0' /></td>";
                        echo "<td><input type='number' name='net_pay[]' value='0' readonly /></td>";
                        echo "<td><button type='submit' class='download-btn'>Saved</button></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div>

   <script>
        // Function to filter employee names by search query
        function searchEmployee() {
            var input = document.getElementById("searchInput").value.toLowerCase();
            var rows = document.getElementById("tableBody").getElementsByTagName("tr");

            for (var i = 0; i < rows.length; i++) {
                var nameCell = rows[i].getElementsByTagName("td")[0]; // Employee Name is in the 1st column
                if (nameCell) {
                    var name = nameCell.textContent || nameCell.innerText;
                    rows[i].style.display = name.toLowerCase().indexOf(input) > -1 ? "" : "none";
                }
            }
        }

        // Function to calculate payroll dynamically
        function calculatePayroll(row) {
            var dailyRate = parseFloat(row.querySelector('[name="daily_rate"]').value) || 0;
            var noOfDays = parseFloat(row.querySelector('[name="no_of_days"]').value) || 0;
            var overtimeRate = parseFloat(row.querySelector('[name="rate_per_hour"]').value) || 0;
            var overtimeHours = parseFloat(row.querySelector('[name="no_of_hours"]').value) || 0;

            // Calculate No. of Hours = No. of Days * 8
            var totalHours = noOfDays * 8;
            row.querySelector('[name="no_of_hours"]').value = totalHours;

            // Calculate Rate/HR = Daily Rate / 8
            var ratePerHour = dailyRate / 8;
            row.querySelector('[name="rate_per_hour"]').value = ratePerHour.toFixed(2);

            // Basic Pay = Daily Rate * No. of Days
            var basicPay = dailyRate * noOfDays;
            row.querySelector('[name="basic_pay"]').value = basicPay.toFixed(2);

            // DAYS and AMOUNT calculation
            var days = parseFloat(row.querySelector('[name="days"]').value) || 0;
            var amount = dailyRate * days;
            row.querySelector('[name="amount"]').value = amount.toFixed(2);

            // Add AMOUNT to the Basic Pay if there's any
            basicPay += amount;
            row.querySelector('[name="basic_pay"]').value = basicPay.toFixed(2);

            // RATE calculation
            var rate = ratePerHour * 1.25;
            row.querySelector('[name="rate"]').value = rate.toFixed(2);

            // TOTAL calculation
            var hours = parseFloat(row.querySelector('[name="hour"]').value) || 0;
            var total = rate * hours;
            row.querySelector('[name="total"]').value = total.toFixed(2);

            // ALLOW calculation
            var allow = 70 * noOfDays;
            row.querySelector('[name="allow"]').value = allow.toFixed(2);

            var medicalReimbursement = parseFloat(row.querySelector('[name="medical_reimbursement"]').value) || 0;
            var hoursND = parseFloat(row.querySelector('[name="hours_nd"]').value) || 0;
            var percent10 = parseFloat(row.querySelector('[name="percent10"]').value) || 0;
            var hoursHP = parseFloat(row.querySelector('[name="hours_hp"]').value) || 0;
            var percent100 = parseFloat(row.querySelector('[name="percent100"]').value) || 0;


            // HOURS calculation
            var hoursInput = parseFloat(row.querySelector('[name="hours"]').value) || 0;
            var percent30 = hoursInput * 68.75 * 0.3;
            row.querySelector('[name="percent30"]').value = percent30.toFixed(2);

            // 10% calculation (Rate/HR * Hours * 0.1)
            var percent10Value = ratePerHour * hours * 0.1;
            row.querySelector('[name="percent10"]').value = percent10Value.toFixed(2);

            // 100% calculation (Hours/HP * Rate/HR)
            var percent100Value = hoursHP * ratePerHour;
            row.querySelector('[name="percent100"]').value = percent100Value.toFixed(2);

            // Gross pay calculation
            var grossPay = basicPay + total + allow + percent30 + medicalReimbursement + percent10 + percent100;
            row.querySelector('[name="gross_pay"]').value = grossPay.toFixed(2);

            // Deductions calculation
            var philhealth = parseFloat(row.querySelector('[name="philhealth"]').value) || 0;
            var sssLoan = parseFloat(row.querySelector('[name="sss_loan"]').value) || 0;
            var sss = parseFloat(row.querySelector('[name="sss"]').value) || 0;
            var hdmf = parseFloat(row.querySelector('[name="hdmf"]').value) || 0;
            var hdmfLoan = parseFloat(row.querySelector('[name="hdmf_loan"]').value) || 0;
            var insurance = parseFloat(row.querySelector('[name="insurance"]').value) || 0;
            var cashAdvance = parseFloat(row.querySelector('[name="cash_advance"]').value) || 0;
            var bhouse = parseFloat(row.querySelector('[name="bhouse"]').value) || 0;

            // Sum of all deductions
            var totalDeductions = philhealth + sssLoan + sss + hdmf + hdmfLoan + insurance + cashAdvance + bhouse;
            row.querySelector('[name="total_deductions"]').value = totalDeductions.toFixed(2);

            // Leave with Pay and Incentives
            var leaveWithPay = parseFloat(row.querySelector('[name="leave_with_pay"]').value) || 0;
            var incentives = parseFloat(row.querySelector('[name="incentives"]').value) || 0;

            // Add Leave with Pay and Incentives to Gross Pay
            grossPay += leaveWithPay + incentives;

            // Net pay calculation (Gross Pay - Total Deductions + Leave with Pay + Incentives)
            var netPay = grossPay - totalDeductions;
            row.querySelector('[name="net_pay"]').value = netPay.toFixed(2);
        }

        // Attach the calculate function to inputs dynamically
        document.querySelectorAll('input').forEach(function(input) {
            input.addEventListener('input', function() {
                var row = this.closest('tr');
                calculatePayroll(row);
            });
        }); 
    </script>

</body>
</html>
