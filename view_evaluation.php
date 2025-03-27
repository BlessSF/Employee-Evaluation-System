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

// Check if employee ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Employee ID is required.");
}

$employee_id = intval($_GET['id']);

// Fetch employee details
$sql = "SELECT id, surname, first_name, branch, position FROM employees WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Employee not found.");
}

$employee = $result->fetch_assoc();

// Fetch all evaluations for the employee
$eval_sql = "SELECT appraisal_rating, summary_performance, recommendation, DATE(evaluation_date) AS evaluation_date 
             FROM evaluations WHERE employee_id = ? ORDER BY evaluation_date DESC";
$eval_stmt = $conn->prepare($eval_sql);
$eval_stmt->bind_param("i", $employee_id);
$eval_stmt->execute();
$evaluations = $eval_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employee Evaluations</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .evaluation-container {
            max-width: 1000px;
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
        }

        .back-button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 15px;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
        /* Ensure side navigation takes full width on smaller screens */
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

    .evaluation-container {
        max-width: 100%;
        margin: 0 auto;
        padding: 15px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .evaluation-container h2 {
        font-size: 18px;
        text-align: center;
    }

    .evaluation-container label {
        font-weight: bold;
        display: block;
        margin-top: 10px;
        color: #333;
        padding: 5px;
        font-size: 14px;
    }

    .evaluation-container p {
        font-size: 14px;
        color: #333;
    }

    table {
        width: 80%;
        border-collapse: collapse;
        margin-top: 5px;
        overflow-x: auto;
    }

    table th, table td {
        padding: 6px;
        font-size: 14px;
        text-align: center;
    }

    table th {
        background-color: #f2f2f2;
    }

    .back-button {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
        width: 60%;
        margin-top: 30px;
        text-align: center;
    }

    .back-button:hover {
        background-color: #0056b3;
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
        <a href="notification.php">Employee Evaluations</a> 
        <a href="evaluated_employees.php">Evaluated Employees</a>
        <a href="employee_tracking.php">Employee Actions Tracking</a>
        <a href="logout.php" class="logout-button">Log Out</a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <div class="header-title">Employee Evaluation History</div>

        <div class="evaluation-container">
            <h2>Employee Details</h2>
            <p><strong>Surname:</strong> <?= htmlspecialchars($employee['surname']) ?></p>
            <p><strong>First Name:</strong> <?= htmlspecialchars($employee['first_name']) ?></p>
            <p><strong>Branch:</strong> <?= htmlspecialchars($employee['branch']) ?></p>
            <p><strong>Position:</strong> <?= htmlspecialchars($employee['position'] ?? 'Not specified') ?></p>

            <h2>Evaluation History</h2>
            <table>
                <thead>
                    <tr>
                        <th>Appraisal Rating</th>
                        <th>Summary of Overall Performance</th>
                        <th>Recommendation</th>
                        <th>Evaluation Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($evaluations->num_rows > 0): ?>
                        <?php while ($row = $evaluations->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['appraisal_rating']) ?></td>
                                <td><?= htmlspecialchars($row['summary_performance']) ?></td>
                                <td><?= htmlspecialchars($row['recommendation']) ?></td>
                                <td><?= htmlspecialchars($row['evaluation_date']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No evaluations found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <a href="evaluated_employees.php" class="back-button">Back to Evaluated Employees</a>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
