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
$sql = "SELECT id, surname, first_name, branch, date_hired, status, position FROM employees WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Employee not found.");
}

$employee = $result->fetch_assoc();

// Handle evaluation form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appraisal_rating = $_POST['appraisal_rating'];
    $summary_performance = $_POST['summary_performance'];
    $recommendation = implode(", ", $_POST['recommendation'] ?? []);

    // Insert evaluation into `evaluations` table
    $insert_sql = "INSERT INTO evaluations (employee_id, appraisal_rating, summary_performance, recommendation, evaluation_date) VALUES (?, ?, ?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iiss", $employee_id, $appraisal_rating, $summary_performance, $recommendation);

    if ($insert_stmt->execute()) {
        header("Location: evaluated_employees.php"); // Redirect to evaluated employees page
        exit;
    } else {
        echo "<div class='error'>Error submitting evaluation: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluate Employee</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        select {
             width: 400px; 
             padding: 10px;
            font-size: 16px;
            border: 2px solidrgb(0, 0, 0);
   
            background-color: white;
            color: black;
            cursor: pointer;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

        select:focus {
            border-color: #0056b3;
            box-shadow: 0 0 5px rgba(0, 86, 179, 0.5);
            outline: none;
}

        .evaluation-container {
            max-width: 1000px;
            margin: 0 auto;
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

        .evaluation-container label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            color: #333;
            padding: 10px;
        }

        .evaluation-container input[type="number"] {
            color: black;
            width: 250px;
            padding: 10px;
            border: 2px solid black;
            border-radius: 5px;
            text-align: center;
            margin-left: 10px;
            height: 20px;
        }

        .rating-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .rating-table th, .rating-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .rating-table th {
            background-color: #f2f2f2;
        }

        .recommendation-options label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            cursor: pointer;
        }

        .recommendation-options input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .btn-submit {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            width: 100%;
            margin-top: 15px;
            transition: background-color 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }
        /* Ensure form and table fit in the viewport on smaller screens */
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
        font-size: 14px; /* Adjust font size */
    }

    .evaluation-container input[type="number"],
    .evaluation-container select {
        width: 100%;
        padding: 10px;
        font-size: 14px;
        border: 2px solid black;
        border-radius: 5px;
        text-align: center;
        margin-left: 0;
        margin-top: 10px;
        box-sizing: border-box;
    }

    .rating-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        overflow-x: auto;
    }

    .rating-table th, .rating-table td {
        padding: 6px;
        text-align: center;
        font-size: 14px; /* Adjust font size */
    }

    .rating-table th {
        background-color: #f2f2f2;
    }

    .recommendation-options label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        cursor: pointer;
    }

    .recommendation-options input[type="checkbox"] {
        width: 16px;
        height: 16px;
    }

    .btn-submit {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        display: block;
        width: 100%;
        margin-top: 15px;
        transition: background-color 0.3s ease;
    }

    .btn-submit:hover {
        background-color: #0056b3;
    }

    /* Ensure no overflow on small screens */
    .evaluation-container {
        overflow-x: auto;
    }

    /* Remove unnecessary margins and padding */
    .main {
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
        <div class="header-title">Evaluate Employee</div>

        <div class="evaluation-container">
            <h2>Employee Details</h2>
            <p><strong>Surname:</strong> <?= htmlspecialchars($employee['surname']) ?></p>
            <p><strong>First Name:</strong> <?= htmlspecialchars($employee['first_name']) ?></p>
            <p><strong>Branch:</strong> <?= htmlspecialchars($employee['branch']) ?></p>
            <p><strong>Date Hired:</strong> <?= htmlspecialchars($employee['date_hired']) ?></p>
            <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($employee['status'])) ?></p>
            <p><strong>Position:</strong> <?= htmlspecialchars($employee['position'] ?? 'Not specified') ?></p>

            <h2>Appraisal Rating and Description</h2>
            <table class="rating-table">
                <thead>
                    <tr>
                        <th>Numerical Rating</th>
                        <th>Descriptive Rating</th>
                        <th>Definition of the Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>5</td><td>EXCELLENT</td><td>Definitely the best performance</td></tr>
                    <tr><td>4</td><td>ABOVE SATISFACTORY</td><td>Consistently fulfills the job requirements and exceeds expectations</td></tr>
                    <tr><td>3</td><td>SATISFACTORY</td><td>Consistently fulfills the job requirements and follows the standards</td></tr>
                    <tr><td>2</td><td>BELOW SATISFACTORY</td><td>Consistently fails to meet job requirements, needs improvement</td></tr>
                    <tr><td>1</td><td>POOR</td><td>Definitely cannot perform what is expected of the job requirements</td></tr>
                </tbody>
            </table>

            <h2>Evaluation Form</h2>
            <form method="POST">
                <label for="appraisal_rating">Appraisal Rating:</label>
                <select name="appraisal_rating" id="appraisal_rating" required>
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Above Satisfactory</option>
                    <option value="3">3 - Satisfactory</option>
                    <option value="2">2 - Below Satisfactory</option>
                    <option value="1">1 - Poor</option>
                </select>

                <label for="summary_performance">Summary of Overall Performance:</label>
                <input type="number" name="summary_performance" id="summary_performance" step="0.1" min="1" max="5" required>

                <h3>Recommendation (check all that apply):</h3>
                <div class="recommendation-options">
                    <label><input type="checkbox" name="recommendation[]" value="Regularization"> For Regularization</label>
                    <label><input type="checkbox" name="recommendation[]" value="Promotion"> For Promotion</label>
                    <label><input type="checkbox" name="recommendation[]" value="Continue"> Continue status to improve performance</label>
                    <label><input type="checkbox" name="recommendation[]" value="Termination"> For termination</label>
                </div>

                <button type="submit" class="btn-submit">Submit Evaluation</button>
            </form>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const checkboxes = document.querySelectorAll('.recommendation-options input[type="checkbox"]');
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                checkboxes.forEach(cb => {
                    if (cb !== this) cb.checked = false;
                });
            });
        });
    });
</script>

</body>
</html>
<?php $conn->close(); ?>
