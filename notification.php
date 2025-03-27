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

// Query for probationary employees needing evaluation
$sql_probation = "
    SELECT id, surname, first_name, branch, date_hired, status,
    CASE
        WHEN TIMESTAMPDIFF(MONTH, date_hired, CURDATE()) >= 12 THEN 
            CONCAT(
                FLOOR(TIMESTAMPDIFF(MONTH, date_hired, CURDATE()) / 12), ' year(s)', 
                CASE 
                    WHEN MOD(TIMESTAMPDIFF(MONTH, date_hired, CURDATE()), 12) > 0 
                    THEN CONCAT(' and ', MOD(TIMESTAMPDIFF(MONTH, date_hired, CURDATE()), 12), ' month(s)') 
                    ELSE '' 
                END
            )
        ELSE CONCAT(TIMESTAMPDIFF(MONTH, date_hired, CURDATE()), ' month(s)')
    END AS tenure
    FROM employees
    WHERE status = 'Probationary' AND TIMESTAMPDIFF(MONTH, date_hired, CURDATE()) >= 3
    ORDER BY date_hired ASC;
";
$result_probation = $conn->query($sql_probation);

// Query for regular employees needing evaluation
$sql_regular = "
    SELECT id, surname, first_name, branch, date_hired, status,
    CASE
        WHEN TIMESTAMPDIFF(MONTH, date_hired, CURDATE()) >= 12 THEN 
            CONCAT(
                FLOOR(TIMESTAMPDIFF(MONTH, date_hired, CURDATE()) / 12), ' year(s)', 
                CASE 
                    WHEN MOD(TIMESTAMPDIFF(MONTH, date_hired, CURDATE()), 12) > 0 
                    THEN CONCAT(' and ', MOD(TIMESTAMPDIFF(MONTH, date_hired, CURDATE()), 12), ' month(s)') 
                    ELSE '' 
                END
            )
        ELSE CONCAT(TIMESTAMPDIFF(MONTH, date_hired, CURDATE()), ' month(s)')
    END AS tenure
    FROM employees
    WHERE status = 'Regular'
    ORDER BY date_hired ASC;
";
$result_regular = $conn->query($sql_regular);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluated Employees</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function searchEmployee() {
            const input = document.getElementById('globalSearch').value.toLowerCase();
            const rows = document.querySelectorAll('.employee-row');

            rows.forEach(row => {
                const surname = row.cells[1].textContent.toLowerCase();
                if (surname.includes(input)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
    <style> 
        .sidenav a.active {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        } 
        .sidenav a:hover {
            background-color: #0056b3;
            color: white;
        }

        th {
            cursor: pointer;
            position: relative;
            padding-right: 20px;
        }

        .sort-icon {
            font-size: 14px;
            margin-left: 5px;
            color: black !important;
        }

        .sorted-asc .sort-icon::after {
            content: " ▲";
            visibility: visible;
        }

        .sorted-desc .sort-icon::after {
            content: " ▼";
            visibility: visible;
        }

        .btn-evaluate {
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .evaluation-header{
            border: 5px solid #107454;
            padding:5px;
            background-color: #107454;
            color: white;
        }

        .btn-evaluate:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .search-bar {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-bar input {
            padding: 8px;
            font-size: 16px;
            width: 50%;
            border-radius: 4px;
            border: 1px solid black;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .search-bar input {
                width: 80%;
            }

            .branch-title {
                font-size: 1.5em;
                
            }

            .branch-table th,
            .branch-table td {
                font-size: 0.9em;
                padding: 8px;
                
            }

            .btn-evaluate {
                font-size: 14px;
                padding: 6px 10px;
                
            }

            .sidenav a {
                padding: 10px;
                font-size: 16px;
            }

            /* Make tables scrollable on mobile */
            .branch-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
                
            }
        }

        @media (max-width: 480px) {
            .search-bar input {
                width: 100%;
                margin-bottom: 20px;
            }

            .btn-evaluate {
                font-size: 14px;
                padding: 6px 10px;
            }

            .branch-title {
                font-size: 1.2em;
            }

            .branch-table th, 
            .branch-table td {
                font-size: 0.8em;
                padding: 6px;
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

<!-- Sidebar -->
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
    <a href="notification.php" class="active">Employee Evaluations</a>
    <a href="evaluated_employees.php">Evaluated Employees</a>
    <a href="employee_tracking.php">Employee Actions Tracking</a>
    <a href="payroll_data.php">View Payroll Data</a>
    <a href="logout.php" class="logout-button">Log Out</a>
</div>

<!-- Main Content -->
<div class="main">
    <h1 class="header-title">Employee Evaluations</h1>

    <div class="search-bar">
        <input type="text" id="globalSearch" onkeyup="searchEmployee()" placeholder="Search employee by surname...">
    </div>

    <h2 class="evaluation-header">Probationary Employees Needing Evaluation</h2>
    <table border="1" cellpadding="10" class="branch-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Surname</th>
                <th>First Name</th>
                <th>Branch</th>
                <th>Date Hired</th>
                <th>Tenure</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $counter = 1; ?>
            <?php while ($row = $result_probation->fetch_assoc()): ?>
                <tr class="employee-row">
                    <td><?= $counter++ ?></td>
                    <td><?= htmlspecialchars($row['surname']) ?></td>
                    <td><?= htmlspecialchars($row['first_name']) ?></td>
                    <td><?= htmlspecialchars($row['branch']) ?></td>
                    <td><?= htmlspecialchars($row['date_hired']) ?></td>
                    <td><?= htmlspecialchars($row['tenure']) ?></td>
                    <td><?= ucfirst(htmlspecialchars($row['status'])) ?></td>
                    <td><a href="evaluation.php?id=<?= $row['id'] ?>" class="btn-evaluate">Evaluate</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h2 class="evaluation-header">Regular Employees Needing Evaluation</h2>
    <table border="1" cellpadding="10" class="branch-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Surname</th>
                <th>First Name</th>
                <th>Branch</th>
                <th>Date Hired</th>
                <th>Tenure</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $counter = 1; ?>
            <?php while ($row = $result_regular->fetch_assoc()): ?>
                <tr class="employee-row">
                    <td><?= $counter++ ?></td>
                    <td><?= htmlspecialchars($row['surname']) ?></td>
                    <td><?= htmlspecialchars($row['first_name']) ?></td>
                    <td><?= htmlspecialchars($row['branch']) ?></td>
                    <td><?= htmlspecialchars($row['date_hired']) ?></td>
                    <td><?= htmlspecialchars($row['tenure']) ?></td>
                    <td><?= ucfirst(htmlspecialchars($row['status'])) ?></td>
                    <td><a href="evaluation.php?id=<?= $row['id'] ?>" class="btn-evaluate">Evaluate</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
<?php $conn->close(); ?>
