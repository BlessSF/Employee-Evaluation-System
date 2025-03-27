

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
    p.daily_rate, p.rate_per_hour, p.no_of_days, p.no_of_hours, p.basic_pay, 
    p.days, p.amount, p.no_of_days_tr, p.trainee_allowance, p.allowance,
    p.medical_reimbursement, p.hours_nd, p.ten_percent, p.hours_hp, p.hundred_percent, p.hours_hdp, p.thirty_percent, p.gross_pay, p.sss_loan, p.sss_d, p.phic, p.pag_ibig, p.insurance,
    p.others, p.cp, p.cash_advance, p.total_deductions, p.adjustment, p.net_pay

                FROM employees e
                LEFT JOIN pub_payroll p ON e.id = p.employee_id
                WHERE (e.first_name LIKE ? OR e.surname LIKE ?)
                AND e.branch = 'PUB'";  // Updated to filter by PUB branch

        $stmt = $conn->prepare($sql);
        $likeSearchQuery = "%$searchQuery%";
        $stmt->bind_param('ss', $likeSearchQuery, $likeSearchQuery);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        // SQL query to fetch PUB employees and their payroll data
        $sql = "SELECT e.id, e.surname, e.first_name, e.position, e.status, e.branch, 
    p.daily_rate, p.rate_per_hour, p.no_of_days, p.no_of_hours, p.basic_pay, 
    p.days, p.amount, p.no_of_days_tr, p.trainee_allowance, p.allowance,
    p.medical_reimbursement, p.hours_nd, p.ten_percent, p.hours_hp, p.hundred_percent, p.hours_hdp, p.thirty_percent, p.gross_pay, p.sss_loan, p.sss_d, p.phic, p.pag_ibig, p.insurance,
    p.others, p.cp,p.cash_advance, p.total_deductions, p.adjustment, p.net_pay

                FROM employees e
                LEFT JOIN pub_payroll p ON e.id = p.employee_id
                WHERE e.branch = 'PUB'";  // Updated to filter by PUB branch

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
    <title>Payroll Data (PUB)</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
/* Ensure the table container has a proper width and allows horizontal scrolling */
/* Make the table scrollable horizontally */
.main {
    overflow-x:scroll;
    padding-left: 1px; /* Adjust according to your sidebar width */
}

/* Make the first column (Employee Name) sticky */
td:first-child, th:first-child {
    position: sticky;
    left: 0;
    background-color: #70e5d1; /* White background for sticky column */
    z-index: 10; /* Ensure it stays above other content */
}
table td:first-child {
    background-color: #e0f5e4; /* Alternate background color for sticky column */
}

/* Make the header row sticky */
th {
    position: sticky;
    top: 0;
    background-color: #70e5d1;
    z-index: 3; /* Ensure it stays above other content */
}

/* Optional: Add box-shadow for better visibility of the sticky header */
th {
    box-shadow: inset 0 -2px 2px rgba(0, 0, 0, 0.1);
}









        .main{
            background-color: white; 
        }
        header{
            width: 6320px;

        }
        .view-btn {
    display: inline-block;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: bold;
    color: #fff;
    background-color: #007bff; /* Blue */
    border: none;
    border-radius: 5px;
    text-decoration: none;
    transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
    
}
.search-container { 
    display: flex;
    justify-content: flex;
    margin-top: 10px;
}

#searchInput {
    width: 40%;
    padding: 12px 15px;
    font-size: 16px;
    border: 5px  #218838;
    border-radius: 25px;
    outline: none;
    transition: all 0.3s ease-in-out;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.55);
}




.view-btn:hover {
    background-color: #0056b3; /* Darker Blue */
    transform: scale(1.05);
}

.view-btn:active {
    transform: scale(0.95);
}

/* Save Button */
button {
    padding: 8px 16px;
    font-size: 14px;
    font-weight: bold;
    color: #fff;
    background-color: #28a745; /* Green */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
    
}

button:hover {
    background-color: #218838; /* Darker Green */
    transform: scale(1.05);
}

button:active {
    transform: scale(0.95);
}
/* Style the table */
table {
    width: 100%; /* Make it take full width */
    border-collapse: collapse;
}


/* Style table cells */
th, td {
    padding: 12px;
    text-align: center;
    border: 1px solid #ccc;
    min-width: 170px; /* Increases the width of each column */
}

#clearAllBtn {
    background-color: #28a745; /* Green */
    color: #fff;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 25px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 1px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-top:13px;
}

#clearAllBtn:hover {
    background-color: #218838; /* Darker Green */
    transform: scale(1.05);
}

#clearAllBtn:active {
    transform: scale(0.95);
}

    </style>
</head>
<body>

    <!-- Side Navigation -->
    <div class="sidenav">
        <div class="logo-container">
            <img src="images/Logo.jpg" alt="Nina Trading Logo" class="logo">
        </div>
        <a href="index.php" class="active">Dashboard</a>
        <a href="employees.php">Probationary Employees</a>
        <a href="regular_employees.php">Regular Employees</a>
        <a href="ojt_employees.php">OJT Employees</a>
        <a href="ojt_hours.php">OJT HOURS</a>
        <a href="upload.php">Upload Employee Data</a>
        <a href="notification.php">Employee Evaluations</a>
        <a href="evaluated_employees.php">Evaluated Employees</a>
        <a href="employee_tracking.php">Employee Actions Tracking</a>
        <a href="payroll_data.php">View Payroll Data</a>
        <a href="logout.php" class="logout-button">Log Out</a>
        
    </div>

    <div class="main">
        <header>
            <h1>Pub Payroll Data</h1>  <!-- Updated header -->
        </header>

        <!-- Search Bar -->
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search by employee name..." onkeyup="searchEmployee()">
        </div>
        <button id="clearAllBtn" onclick="clearAllData()">Clear All Data</button>
        <!-- Main Employee Table -->
        <table>
            <thead>
                <tr>
                <th>Employee Name</th>
                    <th>Daily Rate</th>
                    <th>Rate/HR</th>
                    <th>No. of Days</th>
                    <th>No. of Hours </th>
                    <th>Basic Pay </th>
                    <th>Days</th>
                    <th>Amount </th>
                    <th>Rate/OVT </th>
                    <th>Hour/OVT</th>
                    <th>Total/OVT </th>
                    <th>No. of Days TR</th>
                    <th>TRAINEE ALLOWANCE </th>
                    <th>Allowance </th>
                    <th>Medical Reimbursement</th>
                    <th>HOURS/ND</th>
                    <th>10%</th>
                    <th>Hours/HP</th>
                    <th>100%</th>
                    <th>Hours/HDP</th>
                    <th>30%</th>
                    <th>Gross Pay  </th>
                    <th>SSS Loan</th>
                    <th>SSS</th>
                    <th>PHIC</th>
                    <th>PAG-IBIG</th>
                    <th>Insurance</th>
                    <th>Others</th>
                    <th>CP</th>
                    <th>Cash Advance</th>
                    <th>Total Deductions</th>
                    <th>Adjustment</th>
                    <th>Net Pay</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
                // Execute the SQL query again (in case it was not executed above)
                $result = $conn->query($sql);

                // Check if we have results
                if ($result->num_rows > 0) {
                    // Loop through each row and display the data
                    while ($row = $result->fetch_assoc()) {
                        $employeeName = ucfirst_safe($row['first_name']) . ' ' . ucfirst_safe($row['surname']);
                        ?>
                        <tr>
                            <td><?php echo $employeeName; ?></td>
                            <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['daily_rate']) ?: '0'; ?>" 
                                    id="dailyRate-<?php echo $row['id']; ?>" 
                                    oninput="calculateRatePerHour(<?php echo $row['id']; ?>)">
                            </td>
                            <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['rate_per_hour']) ?: '0'; ?>" 
                                    id="ratePerHour-<?php echo $row['id']; ?>" readonly>
                            </td>
                            <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['no_of_days']) ?: '0'; ?>" 
                                    id="noOfDays-<?php echo $row['id']; ?>" 
                                    oninput="calculateNoOfHours(<?php echo $row['id']; ?>)">
                            </td>
                            <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['no_of_hours']) ?: '0'; ?>" 
                                    id="noOfHours-<?php echo $row['id']; ?>" readonly>
                            </td>
                            <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['basic_pay']) ?: '0'; ?>" 
                                    id="basicPay-<?php echo $row['id']; ?>" readonly>
                            </td>
                            <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['days']) ?: '0'; ?>" 
                                    id="days-<?php echo $row['id']; ?>" 
                                    oninput="calculateAmount(<?php echo $row['id']; ?>)">
                            </td>
                            <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['amount']) ?: '0'; ?>" 
                                    id="amount-<?php echo $row['id']; ?>" readonly>
                            </td>
                            <td>
                                <input type="number" value="<?php echo number_format((float)($row['rate_per_hour'] * 1.25), 2, '.', ''); ?>" 
                                    id="rateOVT-<?php echo $row['id']; ?>" readonly>
                            </td>
                            <td>
                                <input type="number" value="0" id="hourOVT-<?php echo $row['id']; ?>" 
                                    oninput="calculateOVT(<?php echo $row['id']; ?>)">
                            </td>
                            <td>
                                <input type="number" value="0" id="totalOVT-<?php echo $row['id']; ?>" readonly>
                            </td>
                            <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['no_of_days_tr']) ?: '0'; ?>" 
                                    id="noOfDaysTR-<?php echo $row['id']; ?>" 
                                    oninput="calculateTraineeAllowance(<?php echo $row['id']; ?>)">
                            </td>
                            <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['trainee_allowance']) ?: '0'; ?>" 
                                    id="traineeAllowance-<?php echo $row['id']; ?>" readonly>
                            </td>
                            <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['allowance']) ?: '0'; ?>" 
                                    id="allowance-<?php echo $row['id']; ?>" readonly>
                            </td>
                            <td>
                                <input type="number" value="<?php echo isset($row['medical_reimbursement']) ? safe_htmlspecialchars($row['medical_reimbursement']) : '0'; ?>" 
                                    id="medicalReimbursement-<?php echo $row['id']; ?>">
                            </td>
                            <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['hours_nd']) ?: '0'; ?>" 
                                    id="hoursND-<?php echo $row['id']; ?>"
                                    oninput="calculateTenPercent(<?php echo $row['id']; ?>)">
                            </td>
                            <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['ten_percent']) ?: '0'; ?>" 
                                    id="tenPercent-<?php echo $row['id']; ?>" readonly>
                            </td>
                            <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['hours_hp']) ?: '0'; ?>" 
                                    id="hoursHP-<?php echo $row['id']; ?>"
                                    oninput="calculateHundredPercent(<?php echo $row['id']; ?>)">
                            </td>
                            <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['hundred_percent']) ?: '0'; ?>" 
                                    id="hundredPercent-<?php echo $row['id']; ?>" readonly>
                            </td>
                            <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['hours_hdp']) ?: '0'; ?>" 
                                    id="hoursHDP-<?php echo $row['id']; ?>" 
                                    oninput="calculateThirtyPercent(<?php echo $row['id']; ?>)">
                            </td>
                            <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['thirty_percent']) ?: '0'; ?>" 
                                    id="thirtyPercent-<?php echo $row['id']; ?>" readonly>
                            </td>
                            <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['gross_pay']) ?: '0'; ?>" 
                                    id="grossPay-<?php echo $row['id']; ?>" readonly>
                            </td>
                            <td>
                                        <input type="number" value="<?php echo safe_htmlspecialchars($row['sss_loan']) ?: '0'; ?>" 
                                            id="sssLoan-<?php echo $row['id']; ?>" 
                                            oninput="calculateTotalDeductions(<?php echo $row['id']; ?>)">
                                    </td>
                                    <td>
                                <input type="number" value="<?php echo safe_htmlspecialchars($row['sss_d']) ?: '0'; ?>" 
                                    id="sssD-<?php echo $row['id']; ?>" 
                                    oninput="calculateTotalDeductions(<?php echo $row['id']; ?>)">
                            </td>
                            <td>
                                    <input type="number" value="<?php echo safe_htmlspecialchars($row['phic']) ?: '0'; ?>" 
                                        id="phic-<?php echo $row['id']; ?>" 
                                        oninput="calculateTotalDeductions(<?php echo $row['id']; ?>)">
                                </td>
                                <td>
                                    <input type="number" value="<?php echo safe_htmlspecialchars($row['pag_ibig']) ?: '0'; ?>" 
                                        id="pagIbig-<?php echo $row['id']; ?>" 
                                        oninput="calculateTotalDeductions(<?php echo $row['id']; ?>)">
                                </td>
                                <td><input type="number" value="<?php echo safe_htmlspecialchars($row['insurance']) ?: '0'; ?>" id="insurance-<?php echo $row['id']; ?>"
                                oninput="calculateTotalDeductions(<?php echo $row['id']; ?>)"></td>
                                <td>
                                        <input type="number" value="<?php echo safe_htmlspecialchars($row['others']) ?: '0'; ?>" 
                                            id="others-<?php echo $row['id']; ?>" 
                                            oninput="calculateTotalDeductions(<?php echo $row['id']; ?>)">
                                    </td>

                                    <td>
                                            <input type="number" value="<?php echo safe_htmlspecialchars($row['cp']) ?: '0'; ?>" 
                                                id="cp-<?php echo $row['id']; ?>" 
                                                oninput="calculateTotalDeductions(<?php echo $row['id']; ?>)">
                                        </td>
                                        <td>
                                                <input type="number" value="<?php echo safe_htmlspecialchars($row['cash_advance']) ?: '0'; ?>" 
                                                    id="cashAdvance-<?php echo $row['id']; ?>" 
                                                    oninput="calculateTotalDeductions(<?php echo $row['id']; ?>)">
                                            </td>
                                            <td>
                                                        <input type="number" value="<?php echo safe_htmlspecialchars($row['total_deductions']) ?: '0'; ?>" 
                                                            id="totalDeductions-<?php echo $row['id']; ?>" 
                                                            readonly>
                                                    </td>  

                                             <td>
                                                <input type="number" value="<?php echo safe_htmlspecialchars($row['adjustment']) ?: '0'; ?>" 
                                                    id="adjustment-<?php echo $row['id']; ?>">
                                       </td>

                                       <td>
                                            <input type="number" value="<?php echo safe_htmlspecialchars($row['net_pay']) ?: '0'; ?>" 
                                                id="netPay-<?php echo $row['id']; ?>">
                                        </td>
                                            
                                <td> 
                                <a href="view_pub_payroll.php?id=<?php echo $row['id']; ?>" class="view-btn">View</a> <!-- Updated link -->
                                <button onclick="saveChanges(<?php echo $row['id']; ?>)">Save</button>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='23'>No employees found in PUB branch</td></tr>"; 
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function clearAllData() {
            if (confirm("Are you sure you want to delete all payroll data? This action cannot be undone.")) {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_all_payroll_data_pub.php', true);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        alert("All payroll data has been cleared!");
                        location.reload(); // Refresh to reflect changes
                    } else {
                        alert('Error clearing data');
                    }
                };
                xhr.send();
            }
        }
        
        // Function to handle search input
        function searchEmployee() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let rows = document.getElementById('tableBody').getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                let nameCell = rows[i].getElementsByTagName('td')[0];
                if (nameCell) {
                    let nameText = nameCell.textContent || nameCell.innerText;
                    rows[i].style.display = nameText.toLowerCase().indexOf(input) > -1 ? "" : "none";
                }
            }
        }

         // Function to calculate Total/OVT
         function calculateOVT(employeeId) {
            let rateOVT = parseFloat(document.getElementById('rateOVT-' + employeeId).value) || 0;
            let hourOVT = parseFloat(document.getElementById('hourOVT-' + employeeId).value) || 0;
            let totalOVT = rateOVT * hourOVT;
            document.getElementById('totalOVT-' + employeeId).value = totalOVT.toFixed(2);
            calculateGrossPay(employeeId);
        }

        // Function to calculate 100% as Rate/HR * Hours/HP
        function calculateHundredPercent(employeeId) {
            let ratePerHour = parseFloat(document.getElementById('ratePerHour-' + employeeId).value) || 0;
            let hoursHP = parseFloat(document.getElementById('hoursHP-' + employeeId).value) || 0;
            let hundredPercent = (ratePerHour * hoursHP).toFixed(2);
            document.getElementById('hundredPercent-' + employeeId).value = hundredPercent;
            calculateGrossPay(employeeId);
        }

        // Function to calculate No. of Hours as No. of Days * 8 and recalc allowance
        function calculateNoOfHours(employeeId) {
            let noOfDays = parseFloat(document.getElementById('noOfDays-' + employeeId).value) || 0;
            let noOfHours = noOfDays * 8;
            document.getElementById('noOfHours-' + employeeId).value = noOfHours.toFixed(2);
            calculateBasicPay(employeeId);
            calculateOVT(employeeId);
            calculateAllowance(employeeId);
            calculateGrossPay(employeeId);
        }

        // Function to calculate Basic Pay (Rate/HR * No. of Hours) + Amount
        function calculateBasicPay(employeeId) {
            let ratePerHour = parseFloat(document.getElementById('ratePerHour-' + employeeId).value) || 0;
            let noOfHours = parseFloat(document.getElementById('noOfHours-' + employeeId).value) || 0;
            let amount = parseFloat(document.getElementById('amount-' + employeeId).value) || 0;
            let basicPay = (ratePerHour * noOfHours) + amount;
            document.getElementById('basicPay-' + employeeId).value = basicPay.toFixed(2);
            calculateGrossPay(employeeId);
        }

        // Function to calculate Amount (Days * Daily Rate)
        function calculateAmount(employeeId) {
            let days = parseFloat(document.getElementById('days-' + employeeId).value) || 0;
            let dailyRate = parseFloat(document.getElementById('dailyRate-' + employeeId).value) || 0;
            let amount = days * dailyRate;
            document.getElementById('amount-' + employeeId).value = amount.toFixed(2);
            calculateBasicPay(employeeId);
            calculateGrossPay(employeeId);
        }

        // Function to calculate Trainee Allowance (No. of Days TR * 310)
        function calculateTraineeAllowance(employeeId) {
            let noOfDaysTR = parseFloat(document.getElementById('noOfDaysTR-' + employeeId).value) || 0;
            let traineeAllowance = noOfDaysTR * 310;
            document.getElementById('traineeAllowance-' + employeeId).value = traineeAllowance.toFixed(2);
            calculateGrossPay(employeeId);
        }

        // Function to calculate Allowance (No. of Days * 65.39)
        function calculateAllowance(employeeId) {
            let noOfDays = parseFloat(document.getElementById('noOfDays-' + employeeId).value) || 0;
            let allowance = noOfDays * 65.39;
            document.getElementById('allowance-' + employeeId).value = allowance.toFixed(2);
            calculateGrossPay(employeeId);
        }

        // Function to calculate 10% as RATE/HR * HOURS/ND * 10%
        function calculateTenPercent(employeeId) {
            let ratePerHour = parseFloat(document.getElementById('ratePerHour-' + employeeId).value) || 0;
            let hoursND = parseFloat(document.getElementById('hoursND-' + employeeId).value) || 0;
            let tenPercent = (ratePerHour * hoursND * 0.10).toFixed(2);
            document.getElementById('tenPercent-' + employeeId).value = tenPercent;
            calculateGrossPay(employeeId);
        }

        // Function to calculate 30% (Rate/HR * Hours/HDP * 0.3)
        function calculateThirtyPercent(employeeId) {
            let ratePerHour = parseFloat(document.getElementById('ratePerHour-' + employeeId).value) || 0;
            let hoursHDP = parseFloat(document.getElementById('hoursHDP-' + employeeId).value) || 0;
            let thirtyPercent = (ratePerHour * hoursHDP * 0.3).toFixed(2);
            document.getElementById('thirtyPercent-' + employeeId).value = thirtyPercent;
            calculateGrossPay(employeeId);
        }

        // Function to calculate Rate/HR and Rate/OVT
        function calculateRatePerHour(employeeId) {
            let dailyRate = parseFloat(document.getElementById('dailyRate-' + employeeId).value) || 0;
            let ratePerHour = dailyRate / 8;
            document.getElementById('ratePerHour-' + employeeId).value = ratePerHour.toFixed(2);
            
            // Calculate Rate/OVT as Rate/HR * 1.25
            let rateOVT = ratePerHour * 1.25;
            document.getElementById('rateOVT-' + employeeId).value = rateOVT.toFixed(2);
            
            calculateBasicPay(employeeId);
            calculateNoOfHours(employeeId);
            calculateAmount(employeeId);
            calculateOVT(employeeId);
            calculateTenPercent(employeeId);
            calculateGrossPay(employeeId);
        }

        // Function to calculate Gross Pay by summing:
        // Basic Pay + Total/OVT + TRAINEE ALLOWANCE + Allowance + Medical Reimbursement + 10% + 100% + 30%
        function calculateGrossPay(employeeId) {
            let basicPay = parseFloat(document.getElementById('basicPay-' + employeeId).value) || 0;
            let totalOVT = parseFloat(document.getElementById('totalOVT-' + employeeId).value) || 0;
            let traineeAllowance = parseFloat(document.getElementById('traineeAllowance-' + employeeId).value) || 0;
            let allowance = parseFloat(document.getElementById('allowance-' + employeeId).value) || 0;
            let medicalReimbursement = parseFloat(document.getElementById('medicalReimbursement-' + employeeId).value) || 0;
            let tenPercent = parseFloat(document.getElementById('tenPercent-' + employeeId).value) || 0;
            let hundredPercent = parseFloat(document.getElementById('hundredPercent-' + employeeId).value) || 0;
            let thirtyPercent = parseFloat(document.getElementById('thirtyPercent-' + employeeId).value) || 0;
            
            let grossPay = basicPay + totalOVT + traineeAllowance + allowance + medicalReimbursement + tenPercent + hundredPercent + thirtyPercent;
            document.getElementById('grossPay-' + employeeId).value = grossPay.toFixed(2);

            // Calculate Net Pay
            calculateNetPay(employeeId);
        }

        // Function to calculate Net Pay (Gross Pay - Total Deductions)
        function calculateNetPay(employeeId) {
            let grossPay = parseFloat(document.getElementById('grossPay-' + employeeId).value) || 0;
            let totalDeductions = parseFloat(document.getElementById('totalDeductions-' + employeeId).value) || 0;
            let netPay = grossPay - totalDeductions;
            document.getElementById('netPay-' + employeeId).value = netPay.toFixed(2);
        }
        
        // Function to calculate Total Deductions dynamically
        function calculateTotalDeductions(employeeId) {
            let sssLoan = parseFloat(document.getElementById('sssLoan-' + employeeId).value) || 0;
            let sssD = parseFloat(document.getElementById('sssD-' + employeeId).value) || 0;
            let phic = parseFloat(document.getElementById('phic-' + employeeId).value) || 0;
            let pagIbig = parseFloat(document.getElementById('pagIbig-' + employeeId).value) || 0;
            let insurance = parseFloat(document.getElementById('insurance-' + employeeId).value) || 0;
            let others = parseFloat(document.getElementById('others-' + employeeId).value) || 0;
            let cp = parseFloat(document.getElementById('cp-' + employeeId).value) || 0;
            let cashAdvance = parseFloat(document.getElementById('cashAdvance-' + employeeId).value) || 0;

            // Sum all deductions
            let totalDeductions = sssLoan + sssD + phic + pagIbig + insurance + others + cp + cashAdvance;

            // Update the total deductions field (readonly)
            document.getElementById('totalDeductions-' + employeeId).value = totalDeductions.toFixed(2);

            // Automatically recalculate Gross Pay and Net Pay
            calculateGrossPay(employeeId);
        }

        function viewDetails() {
    // Redirect directly to the payroll page without an ID
    window.location.href = "view_pub_payroll.php";
}


        // Function to save changes via AJAX
        function saveChanges(employeeId) {
    let dailyRate = document.getElementById('dailyRate-' + employeeId).value || 0;
    let ratePerHour = document.getElementById('ratePerHour-' + employeeId).value || 0;
    let noOfDays = document.getElementById('noOfDays-' + employeeId).value || 0;
    let noOfHours = document.getElementById('noOfHours-' + employeeId).value || 0;
    let basicPay = document.getElementById('basicPay-' + employeeId).value || 0;
    let days = document.getElementById('days-' + employeeId).value || 0;
    let amount = document.getElementById('amount-' + employeeId).value || 0;
    let rateOVT = document.getElementById('rateOVT-' + employeeId).value || 0;
    let hourOVT = document.getElementById('hourOVT-' + employeeId).value || 0;
    let totalOVT = document.getElementById('totalOVT-' + employeeId).value || 0;
    let noOfDaysTR = document.getElementById('noOfDaysTR-' + employeeId).value || 0;
    let traineeAllowance = document.getElementById('traineeAllowance-' + employeeId).value || 0;
    let allowance = document.getElementById('allowance-' + employeeId).value || 0;
    let medicalReimbursement = document.getElementById('medicalReimbursement-' + employeeId).value || 0;
    let hoursND = document.getElementById('hoursND-' + employeeId).value || 0;
    let tenPercent = document.getElementById('tenPercent-' + employeeId).value || 0;
    let hoursHP = document.getElementById('hoursHP-' + employeeId).value || 0;
    let hundredPercent = document.getElementById('hundredPercent-' + employeeId).value || 0;
    let hoursHDP = document.getElementById('hoursHDP-' + employeeId).value || 0;
    let thirtyPercent = document.getElementById('thirtyPercent-' + employeeId).value || 0;
    let grossPay = document.getElementById('grossPay-' + employeeId).value || 0;
    let sssLoan = document.getElementById('sssLoan-' + employeeId).value || 0;
    let sssD = document.getElementById('sssD-' + employeeId).value || 0;
    let phic = document.getElementById('phic-' + employeeId).value || 0;
    let pagIbig = document.getElementById('pagIbig-' + employeeId).value || 0;
    let insurance = document.getElementById('insurance-' + employeeId).value || 0;
    let others = document.getElementById('others-' + employeeId).value || 0;
    let cp = document.getElementById('cp-' + employeeId).value || 0;
    let cashAdvance = document.getElementById('cashAdvance-' + employeeId).value || 0;
    let totalDeductions = document.getElementById('totalDeductions-' + employeeId).value || 0;
    let adjustment = document.getElementById('adjustment-' + employeeId).value || 0;
    let netPay = document.getElementById('netPay-' + employeeId).value || 0;

    let formData = new FormData();
    formData.append('employee_id', employeeId);
    formData.append('daily_rate', dailyRate);
    formData.append('rate_per_hour', ratePerHour);
    formData.append('no_of_days', noOfDays);
    formData.append('no_of_hours', noOfHours);
    formData.append('basic_pay', basicPay);
    formData.append('days', days);
    formData.append('amount', amount);
    formData.append('rate_ovt', rateOVT);
    formData.append('hour_ovt', hourOVT);
    formData.append('total_ovt', totalOVT);
    formData.append('no_of_days_tr', noOfDaysTR);
    formData.append('trainee_allowance', traineeAllowance);
    formData.append('allowance', allowance);
    formData.append('medical_reimbursement', medicalReimbursement);
    formData.append('hours_nd', hoursND);
    formData.append('ten_percent', tenPercent);
    formData.append('hours_hp', hoursHP);
    formData.append('hundred_percent', hundredPercent);
    formData.append('hours_hdp', hoursHDP);
    formData.append('thirty_percent', thirtyPercent);
    formData.append('gross_pay', grossPay);
    formData.append('sss_loan', sssLoan);
    formData.append('sss_d', sssD);
    formData.append('phic', phic);
    formData.append('pag_ibig', pagIbig);
    formData.append('insurance', insurance);
    formData.append('others', others);
    formData.append('cp', cp);
    formData.append('cash_advance', cashAdvance);
    formData.append('total_deductions', totalDeductions);
    formData.append('adjustment', adjustment);
    formData.append('net_pay', netPay);
    formData.append('payslip_status', 'DONE'); // Automatically update payslip status to DONE

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'save_payroll_changes_pub.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            alert("Payroll data successfully saved and status updated to DONE!");
            location.reload(); // Refresh to reflect changes
        } else {
            alert('Error updating payroll');
        }
    };
    xhr.send(formData);
}

    </script>
</body>
</html>
