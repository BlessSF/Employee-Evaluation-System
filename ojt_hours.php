<?php 
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'employee_evaluation');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch all OJT employees with hours_to_achieve and date_hired
$sql = "SELECT ojt_employees.id, ojt_employees.surname, ojt_employees.first_name, ojt_employees.branch, 
               ojt_employees.date_hired, ojt_employees.hours_to_achieve
        FROM ojt_employees
        ORDER BY ojt_employees.surname";

$result = $conn->query($sql);
$ojt_employees = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OJT Hours Tracker</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- jQuery for AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
        /* Active Navbar Link */
        .sidenav a.active {
            background-color: #007BFF !important;
            color: white !important;
        }

        /* Button Styles */
        .subtract-button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 12px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            margin: 2px;
            display: inline-flex;
            align-items: center;
        }

        .subtract-button i {
            margin-right: 5px;
        }

        /* Hover effect */
        .subtract-button:hover {
            background-color: #cc0000;
        }

        /* Table Styling */
  

        /* Remaining Hours Color based on Branch */
        .remaining-hours {
            font-weight: bold;
            text-align: center;
        }

        .stella { color: green; }
        .dois { color: orange; }
        .pub { color: red; }
    </style>
</head>
<body>

<div class="sidenav">
    <div class="logo-container">
        <img src="images/Logo.jpg" alt="Nina Trading Logo" class="logo">
    </div>
    <a href="index.php">Dashboard</a>
    <a href="employees.php">Probationary Employees</a>
    <a href="regular_employees.php">Regular Employees</a>
    <a href="ojt_employees.php">OJT Employees</a>
    <a href="ojt_hours.php"class="active">OJT HOURS</a>
    <a href="upload.php">Upload Employee Data</a>
    <a href="notification.php">Employee Evaluations</a>
    <a href="evaluated_employees.php">Evaluated Employees</a>
    <a href="employee_tracking.php">Employee Actions Tracking</a>
    <a href="payroll_data.php">View Payroll Data</a>
    <a href="logout.php" class="logout-button">Log Out</a>
</div>

<div class="main">
    <header>
        <h1>OJT Hours Tracker</h1>
    </header>

    <table class="branch-table">
        <thead>
            <tr>
                <th>Surname</th>
                <th>First Name</th>
                <th>Branch</th>
                <th>Date Hired</th>
                <th>Remaining Hours</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ojt_employees as $ojt): ?>
                <?php 
                    // Determine the CSS class based on the branch
                    $branchClass = strtolower($ojt['branch']); // Convert branch to lowercase for class names
                    $branchClass = ($branchClass == "stella") ? "stella" :
                                   (($branchClass == "dois") ? "dois" :
                                   (($branchClass == "pub") ? "pub" : ""));
                ?>
                <tr id="employee-<?= $ojt['id'] ?>">
                    <td><?= htmlspecialchars($ojt['surname']) ?></td>
                    <td><?= htmlspecialchars($ojt['first_name']) ?></td>
                    <td><?= htmlspecialchars($ojt['branch']) ?></td>
                    <td><?= htmlspecialchars($ojt['date_hired']) ?></td>
                    <td class="remaining-hours <?= $branchClass ?>"><?= htmlspecialchars($ojt['hours_to_achieve']) ?></td>
                    <td>
                        <button class="subtract-button" data-id="<?= $ojt['id'] ?>" data-hours="1">
                            <i class="fas fa-minus-circle"></i> 1
                        </button>
                        <button class="subtract-button" data-id="<?= $ojt['id'] ?>" data-hours="2">
                            <i class="fas fa-minus-circle"></i> 2
                        </button>
                        <button class="subtract-button" data-id="<?= $ojt['id'] ?>" data-hours="4">
                            <i class="fas fa-minus-circle"></i> 4
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $(".subtract-button").click(function() {
        var employeeId = $(this).data("id");
        var hoursToSubtract = $(this).data("hours");

        $.ajax({
            url: "subtract.php",
            type: "POST",
            data: {
                ojt_id: employeeId,
                subtract_hours: hoursToSubtract
            },
            success: function(response) {
                var newRemainingHours = parseInt(response);
                if (!isNaN(newRemainingHours)) {
                    $("#employee-" + employeeId + " .remaining-hours").text(newRemainingHours);
                } else {
                    alert("Error updating hours.");
                }
            },
            error: function() {
                alert("An error occurred. Please try again.");
            }
        });
    });
});
</script>

</body>
</html>
