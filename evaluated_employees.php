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

// Fetch all evaluated employees with only the date in last_evaluated
$sql = "
    SELECT 
        e.id, e.surname, e.first_name, e.branch, e.position,
        COUNT(ev.id) AS evaluation_count,
        DATE(MAX(ev.evaluation_date)) AS last_evaluated
    FROM employees e
    LEFT JOIN evaluations ev ON e.id = ev.employee_id
    WHERE ev.id IS NOT NULL
    GROUP BY e.id
    ORDER BY last_evaluated DESC
";
$result = $conn->query($sql);
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
            const input = document.getElementById('globalSearch').value.toLowerCase().trim();
            const rows = document.querySelectorAll('.employee-row');

            rows.forEach(row => {
                const surname = row.cells[0].textContent.toLowerCase();
                const firstName = row.cells[1].textContent.toLowerCase();
                const fullName = firstName + " " + surname; // Combine full name for search

                if (surname.includes(input) || firstName.includes(input) || fullName.includes(input)) {
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

        .evaluation-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .evaluation-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .search-bar {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-bar input {
            padding: 10px;
            font-size: 16px;
            width: 60%;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            cursor: pointer;
        }

        .view-details, .download-pdf {
            color: white;
            padding: 6px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .view-details {
            background-color: #007bff;
        }

        .view-details:hover {
            background-color: #0056b3;
        }

        .download-pdf {
            background-color: green;
        }

        .download-pdf:hover {
            background-color: darkgreen;
        }
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


        /* Responsive Design */
        @media (max-width: 768px) {
            .search-bar input {
                width: 80%;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            th, td {
                font-size: 0.9em;
                padding: 10px;
            }

            .view-details, .download-pdf {
                font-size: 12px;
                padding: 6px 10px;
            }
        }

        @media (max-width: 480px) {
            .search-bar input {
                width: 100%;
                margin-bottom: 20px;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            th, td {
                font-size: 0.8em;
                padding: 8px;
            }

            .view-details, .download-pdf {
                font-size: 12px;
                padding: 6px 8px;
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
        <a href="index.php" >Dashboard</a>
        <a href="employees.php">Probationary Employees</a>
        <a href="regular_employees.php">Regular Employees</a>
        <a href="ojt_employees.php">OJT Employees</a>
        <a href="ojt_hours.php">OJT HOURS</a>
        <a href="upload.php">Upload Employee Data</a>
        <a href="notification.php">Employee Evaluations</a>
        <a href="evaluated_employees.php"class="active">Evaluated Employees</a>
        <a href="employee_tracking.php">Employee Actions Tracking</a>
        <a href="payroll_data.php">View Payroll Data</a>
        <a href="logout.php" class="logout-button">Log Out</a>
        
    </div>

    <!-- Main Content -->
    <div class="main">
        <div class="header-title">Employee Evaluations</div>

        <div class="evaluation-container">
            <h2>List of Evaluated Employees</h2>

            <!-- Search Bar -->
            <div class="search-bar">
                <input type="text" id="globalSearch" onkeyup="searchEmployee()" placeholder="Search employee by surname...">
            </div>

            <table id="evaluatedTable">
                <thead>
                    <tr>
                        <th>Surname</th>
                        <th>First Name</th>
                        <th>Branch</th>
                        <th>Position</th>
                        <th>Times Evaluated</th>
                        <th>Last Evaluated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="employee-row">
                            <td><?= htmlspecialchars($row['surname']) ?></td>
                            <td><?= htmlspecialchars($row['first_name']) ?></td>
                            <td><?= htmlspecialchars($row['branch']) ?></td>
                            <td><?= htmlspecialchars($row['position'] ?? 'Not specified') ?></td>
                            <td><?= $row['evaluation_count'] ?></td>
                            <td><?= htmlspecialchars($row['last_evaluated']) ?></td>
                            <td>
                                <a href="view_evaluation.php?id=<?= $row['id'] ?>" class="view-details">View Evaluations</a>
                                <a href="generate_report.php?id=<?= $row['id'] ?>" class="download-pdf">Download PDF</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
