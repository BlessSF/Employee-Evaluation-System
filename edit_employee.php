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

// Check if the employee ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Employee ID is required.");
}
$employee_id = intval($_GET['id']);

// Fetch employee details
$sql = "SELECT id, surname, first_name, date_hired, status, position FROM employees WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Employee not found.");
}
$employee = $result->fetch_assoc();

// --------------------------
// Handling form submission
// --------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $surname    = $_POST['surname'];
    $first_name = $_POST['first_name'];
    $date_hired = $_POST['date_hired'];
    $status     = strtolower($_POST['status']); // Convert status to lowercase
    $position   = $_POST['position'];

    // Fetch old values for logging
    $old_sql    = "SELECT * FROM employees WHERE id = ?";
    $old_stmt   = $conn->prepare($old_sql);
    $old_stmt->bind_param("i", $employee_id);
    $old_stmt->execute();
    $old_result = $old_stmt->get_result();
    $old_row    = $old_result->fetch_assoc();

    $old_value = "surname: {$old_row['surname']}, first_name: {$old_row['first_name']}, date_hired: {$old_row['date_hired']}, status: {$old_row['status']}, position: {$old_row['position']}";
    $new_value = "surname: $surname, first_name: $first_name, date_hired: $date_hired, status: $status, position: $position";

    // Update employee details
    $update_sql = "UPDATE employees SET surname = ?, first_name = ?, date_hired = ?, status = ?, position = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssi", $surname, $first_name, $date_hired, $status, $position, $employee_id);

    if ($update_stmt->execute()) {
        // Log the update in the audit table
        $action     = 'Update';
        $changed_by = $_SESSION['admin_name']; // Assuming admin's name is stored in session

        $log_sql = "INSERT INTO employee_audit_log (employee_id, action, old_value, new_value, changed_by) VALUES (?, ?, ?, ?, ?)";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("issss", $employee_id, $action, $old_value, $new_value, $changed_by);
        $log_stmt->execute();

        // Redirect based on new status
        if ($status === 'regular') {
            header("Location: regular_employees.php");
        } elseif ($status === 'probationary') {
            header("Location: employees.php");
        } else {
            header("Location: employees.php");
        }
        exit;
    } else {
        echo "Error updating employee: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Employee</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        h1 {
            text-align: center;
            background-color:#087356;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }   

        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color:rgb(255, 255, 255);
            border: 1px solid black;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
            color: #333;
        }

        form input, form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid black;
            border-radius: 5px;
        }

        form button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        form button:hover {
            background-color: #0056b3;
        }

        .logout-button {
            margin-top: 20px;
            background-color: #d9534f;
            color: white;
            text-align: center;
            padding: 10px;
            display: block;
            text-decoration: none;
        }

        .logout-button:hover {
            background-color: #c12e2a;
        }
    </style>
    <script>
        function validateForm() {
            const position = document.getElementById('position').value.trim();
            if (position === "") {
                alert("Position cannot be empty!");
                return false;
            }
            if (position.length > 100) {
                alert("Position cannot exceed 100 characters.");
                return false;
            }
            if (!/^[a-zA-Z\s]*$/.test(position)) { 
                alert("Position can only contain letters and spaces.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <!-- Side Navigation -->
    <div class="sidenav">
        <div class="logo-container">
            <img src="images/Logo.jpg" alt="Nina Trading Logo" class="logo">
        </div>
        <a href="index.php">Dashboard</a>
        <a href="employees.php">Probationary Employees</a>
        <a href="upload.php">Upload Employee Data</a>
        <a href="notification.php">Employee Evaluations</a>
        <a href="evaluated_employees.php">Evaluated Employees</a>
        <a href="employee_tracking.php">Employee Actions Tracking</a>
        <a href="logout.php" class="logout-button">Log Out</a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <h1>Edit Employee</h1>

        <form method="POST" onsubmit="return validateForm()">
            <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">

            <label for="surname">Surname:</label>
            <input type="text" name="surname" id="surname" value="<?= htmlspecialchars($employee['surname']) ?>" required>
            
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($employee['first_name']) ?>" required>
            
            <label for="date_hired">Date Hired:</label>
            <input type="date" name="date_hired" id="date_hired" value="<?= htmlspecialchars($employee['date_hired']) ?>" required>
            
            <label for="status">Employment Status:</label>
            <select name="status" id="status" required>
                <option value="probationary" <?= strtolower($employee['status']) === 'probationary' ? 'selected' : '' ?>>Probationary</option>
                <option value="regular" <?= strtolower($employee['status']) === 'regular' ? 'selected' : '' ?>>Regular</option>
            </select>

            <label for="position">Position:</label>
            <input type="text" name="position" id="position" value="<?= htmlspecialchars($employee['position']) ?>" required>

            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>
