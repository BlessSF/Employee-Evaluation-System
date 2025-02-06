<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'employee_evaluation');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Count total employees
$sql_total = "SELECT COUNT(*) AS total_employees FROM employees";
$result_total = $conn->query($sql_total);
$total_employees = ($result_total) ? $result_total->fetch_assoc()['total_employees'] : 0;

// Count evaluated employees
$sql_evaluated = "
    SELECT COUNT(DISTINCT employee_id) AS evaluated_count FROM evaluations
";
$result_evaluated = $conn->query($sql_evaluated);
$evaluated_employees = ($result_evaluated) ? $result_evaluated->fetch_assoc()['evaluated_count'] : 0;

// Calculate non-evaluated employees
$not_evaluated = $total_employees - $evaluated_employees;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Active link style */
        .sidenav a.active {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .sidenav a:hover {
            background-color: #0056b3;
            color: white;
        }
        .logout-button {
            background-color: #d9534f;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 10px;
            display: block;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .logout-button:hover {
            background-color: #c12e2a;
            color: white;
        }

        /* Statistics container */
        .statistics-section {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        /* Statistics cards */
        .stats-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .stats-card {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 280px;
            height: 150px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f8f9fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #333;
            transition: background-color 0.3s ease, transform 0.2s ease;
            cursor: pointer;
        }

        .stats-card:hover {
            background-color: #007bff;
            color: white;
            transform: translateY(-5px);
        }

        .stats-card i {
            margin-right: 10px;
            font-size: 30px;
        }

        .stats-card .number {
            font-size: 40px;
            font-weight: bold;
            margin-left: 10px;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .sidenav {
                width: 100%;
                height: auto;
                position: relative;
            }
            .sidenav a {
                text-align: center;
                padding: 10px;
            }
            .sidenav .logo-container {
                text-align: center;
            }
            .stats-container {
                flex-direction: column;
                align-items: center;
            }
            .stats-card {
                width: 100%;
                height: auto;
                padding: 15px;
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
        <a href="index.php" class="active">Dashboard</a>
        <a href="employees.php">Probationary Employees</a>
        <a href="regular_employees.php">Regular Employees</a>
        <a href="upload.php">Upload Employee Data</a>
        <a href="notification.php">Employee Evaluations</a>
        <a href="evaluated_employees.php">Evaluated Employees</a>
        <a href="employee_tracking.php">Employee Actions Tracking</a>
        <a href="logout.php" class="logout-button">Log Out</a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <header>
            <h1>Employee Evaluation Dashboard</h1>
        </header>
        <section class="statistics-section">
            <h2>Statistics</h2>
            <div class="stats-container">
                <div class="stats-card">
                    <i class="fas fa-users"></i>
                    <div>
                        Total Employees: <span class="number"><?= $total_employees ?></span>
                    </div>
                </div>
                <div class="stats-card">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        Evaluated Employees: <span class="number"><?= $evaluated_employees ?></span>
                    </div>
                </div>
                <div class="stats-card">
                    <i class="fas fa-times-circle"></i>
                    <div>
                        Not Evaluated Employees: <span class="number"><?= $not_evaluated ?></span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
<?php $conn->close(); ?>