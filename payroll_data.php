'''''''''''''''''''<?php
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
    $searchQuery = mysqli_real_escape_string($conn, $_GET['search']);
    // Query to fetch employee info from employees table
    $sql = "SELECT e.id, e.surname, e.first_name, e.position, e.status, e.branch
            FROM employees e
            WHERE e.first_name LIKE '%$searchQuery%' 
               OR e.surname LIKE '%$searchQuery%'";
} else {
    // Otherwise fetch all employees
    $sql = "SELECT e.id, e.surname, e.first_name, e.position, e.status, e.branch
            FROM employees e";
}

$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Helper function to safely apply htmlspecialchars
function safe_htmlspecialchars($value) {
    return htmlspecialchars($value === NULL ? '' : $value, ENT_QUOTES, 'UTF-8');
}

// Helper function to capitalize the first letter of each word
function ucfirst_safe($value) {
    return ucfirst(safe_htmlspecialchars($value));
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
        /* Styles for table, search bar, and layout */
        body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
}
h1 {
    color: white;
    text-align: center;
}
table {
    border-collapse: collapse;
    width: 100%;
    margin: 20px auto;
    background-color: #fff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}
thead {
    background-color: #007bff;
    color: white;
}
th, td {
    padding: 10px 12px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}
tr:hover {
    background-color: #f9f9f9;
}
th {
    font-weight: bold;
}

.sidenav a.active {
    background-color: #007bff;
    color: white;
    font-weight: bold;
}

/* Search bar */
.search-container {
    margin: 20px 0;
    text-align: relative;
}
.search-container input {
    width: 240px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

/* Responsive for smaller screens */
@media (max-width: 768px) {
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
}
@media (max-width: 480px) {
    .search-container input {
        width: 350px;
        margin-bottom: 20px;
    }
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
}

/* Style for PUB, STELLA, DOIS box containers */
.pub-container, .stella-container, .dois-container {
    display: inline-block;
    margin: 10px;
}
.pub-box, .stella-box, .dois-box {
    display: block;
    padding: 20px 40px;
    border-radius: 10px;
    text-decoration: none;
    font-size: 24px;
    text-align: center;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 200px;
    color: white;
}
.pub-box {
    background-color: rgb(255, 0, 0); /* PUB */
    margin-right: 5px;
}
.stella-box {
    background-color: rgb(30, 255, 0); /* STELLA */
    margin-left: 45px;
}
.dois-box {
    background-color: rgb(255, 132, 0); /* DOIS */
    margin-left: 1px;
}
.pub-container, .stella-container, .dois-container {
    margin-right: 80px;
    margin-top: 20px;
}

/* 🔴🔵 Payslip Status Colors */
.status-ongoing {
    color: #ff0000; /* Red for ONGOING */
    font-weight: bold;
}
.status-done {
    color: #28a745; /* Green for DONE */
    font-weight: bold;
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
    <a href="ojt_employees.php">OJT Employees</a>
    <a href="ojt_hours.php" >OJT HOURS</a>
    <a href="upload.php">Upload Employee Data</a>
    <a href="payroll_upload.php">Upload Payroll Data</a>
    <a href="notification.php">Employee Evaluations</a>
    <a href="evaluated_employees.php">Evaluated Employees</a>
    <a href="employee_tracking.php">Employee Actions Tracking</a>
    <a href="payroll_data.php" class="active">View Payroll Data</a>
    <a href="logout.php" class="logout-button">Log Out</a>
</div>

<div class="main">
    <header>
        <h1>Payroll Data</h1>
    </header>

    <!-- Boxes Outside the Table -->
    <div class="pub-container">
        <a href="pub_payroll.php" class="pub-box">PUB</a> 
    </div>
    <div class="stella-container">
        <a href="stella_payroll.php" class="stella-box">STELLA</a> 
    </div>
    <div class="dois-container">
        <a href="dois_payroll.php" class="dois-box">DOIS</a> 
    </div>

    <!-- Search Bar -->
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search by employee name..." onkeyup="searchEmployee()">
    </div>

    <!-- Main Employee Table -->
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Employee Name</th>
                <th>Position</th>
                <th>Status</th>
                <th>Branch</th>
                <th>Payslip Status</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <?php
            // Check if there are any results
            if ($result->num_rows > 0) {
                $counter = 1;
                while ($row = $result->fetch_assoc()) {
                    // Prepare default status
                    $payslip_status = 'ONGOING';

                    // Determine which payroll table to check, based on the branch
                    if (strtoupper($row['branch']) === 'STELLA') {
                        $checkSql = "SELECT payslip_status 
                                      FROM stella_payroll 
                                      WHERE employee_id = " . (int)$row['id'] . " 
                                      ORDER BY id DESC LIMIT 1";
                    } 
                    else if (strtoupper($row['branch']) === 'DOIS') {
                        $checkSql = "SELECT payslip_status 
                                      FROM dois_payroll 
                                      WHERE employee_id = " . (int)$row['id'] . " 
                                      ORDER BY id DESC LIMIT 1";
                    } 
                    else if (strtoupper($row['branch']) === 'PUB') {
                        $checkSql = "SELECT payslip_status 
                                      FROM pub_payroll 
                                      WHERE employee_id = " . (int)$row['id'] . " 
                                      ORDER BY id DESC LIMIT 1";
                    } 
                    else {
                        // If none of the above, skip or keep as ONGOING
                        $checkSql = "";
                    }

                    if (!empty($checkSql)) {
                        $checkResult = $conn->query($checkSql);
                        if ($checkResult && $checkResult->num_rows > 0) {
                            $statRow = $checkResult->fetch_assoc();
                            $payslip_status = $statRow['payslip_status'];
                        }
                    }

                    echo "<tr>";
                    echo "<td>" . $counter++ . "</td>";
                    echo "<td>" 
                         . ucfirst_safe($row['first_name']) . " " 
                         . ucfirst_safe($row['surname']) . "</td>";
                    echo "<td>" . ucfirst_safe($row['position']) . "</td>";
                    echo "<td>" . ucfirst_safe($row['status']) . "</td>";
                    echo "<td>" . ucfirst_safe($row['branch']) . "</td>";
                    $statusClass = ($payslip_status === 'DONE') ? 'status-done' : 'status-ongoing';
                    echo "<td class='$statusClass'>" . safe_htmlspecialchars($payslip_status) . "</td>";
                    
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function searchEmployee() {
        let query = document.getElementById("searchInput").value.trim();
        let xhr = new XMLHttpRequest();

        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById("tableBody").innerHTML = xhr.responseText;
            }
        };

        xhr.open("GET", "payroll_data.php?search=" + query, true);
        xhr.send();
    }
</script>                                                                                                                                                                                                                                                                                                                                           
</body>
</html>
'''''''''''''''''''