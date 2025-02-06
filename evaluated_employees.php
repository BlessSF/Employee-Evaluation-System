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
            padding: 8px;
            font-size: 16px;
            width: 50%;
            border-radius: 4px;
            border: 1px solid black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            cursor: pointer;
            position: relative;
            padding-right: 20px;
        }

        .view-details {
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .view-details:hover {
            background-color: #0056b3;
        }

        .download-pdf {
            background-color: green;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .download-pdf:hover {
            background-color: darkgreen;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .search-bar input {
                width: 80%;
            }

            .evaluation-container {
                padding: 15px;
            }

            .evaluation-container h2 {
                font-size: 1.5em;
            }

            table th, table td {
                font-size: 0.9em;
                padding: 8px;
            }

            .view-details, .download-pdf {
                font-size: 12px;
                padding: 6px 8px;
            }

            .sidenav a {
                padding: 10px;
                font-size: 16px;
            }

            /* Make tables scrollable on mobile */
            table {
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

            .view-details, .download-pdf {
                font-size: 12px;
                padding: 5px 7px;
            }

            table th, table td {
                font-size: 0.8em;
                padding: 6px;
            }

            .evaluation-container h2 {
                font-size: 1.2em;
            }

            .evaluation-container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Side Navigation -->
    <div class="sidenav">
        <div class="logo-container">
            <img src="images/Logo.jpg" alt="Company Logo" class="logo">
        </div>
        <a href="index.php">Dashboard</a>
        <a href="employees.php">Probationary Employees</a>
        <a href="regular_employees.php">Regular Employees</a>
        <a href="upload.php">Upload Employee Data</a>
        <a href="notification.php">Employee Evaluations</a> 
        <a href="evaluated_employees.php" class="active">Evaluated Employees</a>
        <a href="employee_tracking.php">Employee Actions Tracking</a>
        <a href="logout.php" class="logout-button">Log Out</a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <div class="header-title">Evaluated Employees</div>

        <div class="evaluation-container">
            <h2>List of Evaluated Employees</h2>

            <!-- Search Bar -->
            <div class="search-bar">
                <input type="text" id="globalSearch" onkeyup="searchEmployee()" placeholder="Search employee by surname...">
            </div>

            <table id="evaluatedTable" border="1" cellpadding="10" data-sort-order="asc">
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
