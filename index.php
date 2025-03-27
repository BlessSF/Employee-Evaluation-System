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

$sql_ojt = "SELECT COUNT(*) AS ojt_count FROM ojt_employees";
$result_ojt = $conn->query($sql_ojt);
$ojt_employees = ($result_ojt) ? $result_ojt->fetch_assoc()['ojt_count'] : 0;

// Count total employees
$sql_total = "SELECT COUNT(*) AS total_employees FROM employees";
$result_total = $conn->query($sql_total);
$total_employees = ($result_total) ? $result_total->fetch_assoc()['total_employees'] : 0;

// Count evaluated employees
$sql_evaluated = "SELECT COUNT(DISTINCT employee_id) AS evaluated_count FROM evaluations";
$result_evaluated = $conn->query($sql_evaluated);
$evaluated_employees = ($result_evaluated) ? $result_evaluated->fetch_assoc()['evaluated_count'] : 0;

// Calculate non-evaluated employees
$not_evaluated = $total_employees - $evaluated_employees;

// Count probationary employees
$sql_probationary = "SELECT COUNT(*) AS probationary_count FROM employees WHERE status = 'Probationary'";
$result_probationary = $conn->query($sql_probationary);
$probationary_employees = ($result_probationary) ? $result_probationary->fetch_assoc()['probationary_count'] : 0;

// Count regular employees
$sql_regular = "SELECT COUNT(*) AS regular_count FROM employees WHERE status = 'Regular'";
$result_regular = $conn->query($sql_regular);
$regular_employees = ($result_regular) ? $result_regular->fetch_assoc()['regular_count'] : 0;

// Count employees in each branch
$sql_branches = "SELECT branch, COUNT(*) AS branch_count FROM employees GROUP BY branch";
$result_branches = $conn->query($sql_branches);
$branch_counts = [];
if ($result_branches) {
    while ($row = $result_branches->fetch_assoc()) {
        $branch_counts[] = $row;
    }
}
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
        .sidenav a.active {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .section-title {
            font-size: 22px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid green;
            color:rgb(61, 84, 31);
        }

        .stats-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .stats-card {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 280px;
            height: 120px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f8f9fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            font-size: 18px;
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
            font-size: 24px;
        }

        .stats-card .number {
            font-size: 36px;
            font-weight: bold;
            margin-left: 10px;
        }
        /* Tablet and smaller devices (portrait mode) */
        @media (max-width: 768px) {
            .sidenav {
                width: 200px;
            }

            .main {
                margin-left: 0;
                padding: 15px;
            }

            .section-title {
                font-size: 20px;
            }

            .stats-card {
                padding: 12px;
                font-size: 16px;
            }

            .stats-card .number {
                font-size: 28px;
            }

            .sidenav a {
                font-size: 16px;
                padding: 12px;
            }

            /* Stack stats cards vertically for smaller screens */
            .stats-container {
                grid-template-columns: 1fr;
            }

            .sidenav a.active {
                background-color: #007bff;
                color: white;
                font-weight: bold;
            }
        }

        /* Smaller mobile devices (portrait mode) */
        @media (max-width: 480px) {
            .sidenav {
                width: 100%;
                position: relative;
                height: auto;
            }

            .main {
                margin-left: 0;
                padding: 10px;
            }

            .sidenav a {
                font-size: 14px;
                padding: 10px;
            }

            .stats-card {
                padding: 10px;
                font-size: 14px;
            }

            .stats-card .number {
                font-size: 24px;
            }

            .section-title {
                font-size: 18px;
            }

            /* Adjust the side navigation to a more compact look */
            .sidenav {
                width: 100%;
            }
        }

        /* Larger screens (PC/desktop) */
        @media (min-width: 1024px) {
            .sidenav {
                width: 250px;
            }

            .stats-container {
                grid-template-columns: repeat(3, 1fr);
            }

            .stats-card {
                padding: 20px;
                font-size: 18px;
            }

            .stats-card .number {
                font-size: 40px;
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
        <a href="ojt_employees.php">OJT Employees</a>
        <a href="ojt_hours.php">OJT HOURS</a>
        <a href="upload.php">Upload Employee Data</a>
        <a href="notification.php">Employee Evaluations</a>
        <a href="evaluated_employees.php">Evaluated Employees</a>
        <a href="employee_tracking.php">Employee Actions Tracking</a>
        <a href="payroll_data.php">View Payroll Data</a>
        <a href="logout.php" class="logout-button">Log Out</a>
        
    </div>

    <!-- Main Content -->
    <div class="main">
        <header>
            <h1>Employee Evaluation Dashboard</h1>
        </header>

        <!-- Statistics Section -->
        <section>
            <h2 class="section-title">Statistics</h2>
            <div class="stats-container">
                <div class="stats-card">
                    <i class="fas fa-users"></i>
                    <div>
                        Total Employees: <span class="number"><?= $total_employees ?></span>
                    </div>
                </div>
                <div class="stats-card">
                    <i class="fas fa-user-clock"></i>
                    <div>
                        
                        Probationary Employees: <span class="number"><?= $probationary_employees ?></span>
                    </div>
                </div>
                <div class="stats-card">
                    <i class="fas fa-user-tie"></i>
                    <div>
                        Regular Employees: <span class="number"><?= $regular_employees ?></span>
                    </div>
                </div>
                <div class="stats-card">
    <i class="fas fa-user-graduate"></i>
    <div>
        OJT Employees: <span class="number"><?= $ojt_employees ?></span>
    </div>
</div>

            </div>
        </section>

        <!-- Evaluation Section -->
        <section>
            <h2 class="section-title">Evaluation</h2>
            <div class="stats-container">
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

        <!-- Branches Section -->
        <section>
            <h2 class="section-title">All Branches</h2>
            <div class="stats-container">
                <?php foreach ($branch_counts as $branch): ?>
                    <div class="stats-card">
                        <i class="fas fa-building"></i>
                        <div>
                            <?= htmlspecialchars($branch['branch']) ?> Employees: 
                            <span class="number"><?= $branch['branch_count'] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</body>
</html>
<?php $conn->close(); ?>
