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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_employee'])) {
    $surname = trim($_POST['surname']);
    $first_name = trim($_POST['first_name']);
    $date_hired = $_POST['date_hired'];
    $status = $_POST['status']; // Can be OJT, Probationary, or Regular
    $branch = $_POST['branch'];
    $sub_branch = ($branch === 'PUB') ? ($_POST['sub_branch'] ?? null) : null;
    $position = trim($_POST['position']);
    $hours_to_achieve = ($status === 'OJT') ? $_POST['hours_to_achieve'] : null;

    // Server-side validation
    if (empty($surname) || empty($first_name) || empty($date_hired) || empty($status) || empty($branch) || empty($position)) {
        $error_message = "All fields are required!";
    } elseif (!preg_match("/^[a-zA-Z\s]*$/", $position)) {
        $error_message = "Position can only contain letters and spaces.";
    } elseif ($branch === 'PUB' && empty($sub_branch)) {
        $error_message = "Sub-Branch is required for PUB branch!";
    } elseif ($status === 'OJT' && empty($hours_to_achieve)) {
        $error_message = "Hours to achieve is required for OJT status!";
    } else {
        // Insert into the correct table based on employment status
        if ($status === 'OJT') {
            // Insert into ojt_employees table, including the hours_to_achieve
            $stmt = $conn->prepare("INSERT INTO ojt_employees (surname, first_name, date_hired, branch, sub_branch, status, hours_to_achieve) VALUES (?, ?, ?, ?, ?, 'OJT', ?)");
            $stmt->bind_param('sssssi', $surname, $first_name, $date_hired, $branch, $sub_branch, $hours_to_achieve);
            $redirect_page = 'ojt_employees.php';
        } else {
            // Insert into employees table
            $stmt = $conn->prepare("INSERT INTO employees (surname, first_name, date_hired, status, branch, sub_branch, position) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssssss', $surname, $first_name, $date_hired, $status, $branch, $sub_branch, $position);
            $redirect_page = 'employees.php';
        }

        // Execute statement
        if ($stmt->execute()) {
            $_SESSION['floating_message'] = "Employee {$surname}, {$first_name} added successfully!";
            header("Location: $redirect_page");
            exit;
        } else {
            $error_message = "Failed to add employee. Please try again.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Employee</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
      body {
    font-family: Arial, sans-serif;
    background-color:hsl(158, 34.30%, 86.30%);
    margin: 0;
    padding: 0;
}

.main {
    max-width: 600px;
    margin: 50px auto;
    background: white;
    padding: 30px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.form-container {
    padding: 20px;
}

label {
    font-weight: bold;
    display: block;
    margin: 10px 0 5px;
    color: #333;
    font-size: 14px;
}

input, select {
    width: 450px;
    padding: 12px;
    border: 2px solid black;
    margin-top: 10px;
    margin-bottom: 5px;
    border-radius: 5px;
    font-size: 16px;
    background-color: white;
    outline: none;
    transition: 0.3s;
}

input:focus, select:focus {
    border-color: #007bff;
    box-shadow: 0px 0px 5px rgba(0, 123, 255, 0.5);
}

.error-message {
    color: red;
    background-color: #ffd6d6;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    text-align: center;
}

.button-container {
    text-align: center;
    margin-top: 30px;  /* Increased margin-top to move button down */
}

button {
    background-color: #007bff;
    color: white;
    border: none;
    margin-top: 30px;  /* Added more space */
    padding: 15px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    width: 250px;
    display: block;
    margin: auto;
}

button:hover {
    background-color: #0056b3;
}

/* Hide extra fields initially */
#subBranchContainer, #hoursContainer {
    display: none;
}

/* Responsive design */
@media (max-width: 768px) {
    .main {
        width: 90%;
        padding: 20px;
    }
    
    button {
        width: 100%;
    }
}


    </style>
    <script>
        function toggleSubBranch() {
            const branchSelect = document.getElementById('branch');
            const subBranchContainer = document.getElementById('subBranchContainer');
            subBranchContainer.style.display = branchSelect.value === 'PUB' ? 'block' : 'none';
        }

        function toggleHoursInput() {
            const statusSelect = document.getElementById('status');
            const hoursContainer = document.getElementById('hoursContainer');
            hoursContainer.style.display = statusSelect.value === 'OJT' ? 'block' : 'none';
        }

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

<div class="sidenav">
    <div class="logo-container">
        <img src="images/Logo.jpg" alt="Nina Trading Logo" class="logo">
    </div>
    <a href="index.php">Dashboard</a>
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

    <div class="main">
        <header>
            <h1>Add New Employee</h1>
        </header>
        <section>
            <div class="form-container">
                <h2>Add Employee</h2>
                <?php if (isset($error_message)): ?>
                    <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
                <?php endif; ?>

                <form action="add_employee.php" method="post" onsubmit="return validateForm()">
                    <input type="hidden" name="add_employee" value="1">

                    <label for="surname">Surname</label>
                    <input type="text" name="surname" id="surname" required>

                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" required>

                    <label for="date_hired">Date Hired</label>
                    <input type="date" name="date_hired" id="date_hired" required>

                    <label for="status">Employment Status</label>
                    <select name="status" id="status" required onchange="toggleHoursInput()">
                        <option value="Probationary">Probationary</option>
                        <option value="Regular">Regular</option>
                        <option value="OJT">OJT</option> <!-- 🔹 Added OJT -->
                    </select>

                    <label for="branch">Branch</label>
                    <select name="branch" id="branch" required onchange="toggleSubBranch()">
                        <option value="STELLA">STELLA</option>
                        <option value="DOIS">DOIS</option>
                        <option value="PUB">PUB</option>
                    </select>

                    <div id="subBranchContainer" style="display: none;">
                        <label for="sub_branch">Sub-Branch</label>
                        <select name="sub_branch" id="sub_branch">
                            <option value="Main Office">Main Office</option>
                            <option value="Nina Food Products Trading">Nina Food Products Trading</option>
                            <option value="Shock Sisig">Shock Sisig</option>
                            <option value="Pub Express Resto-Employers">Pub Express Resto-Employers</option>
                        </select>
                    </div>

                    <label for="position">Position</label>
                    <input type="text" name="position" id="position" required>

                    <div id="hoursContainer" style="display: none;">
                        <label for="hours_to_achieve">Required Hours</label>
                        <input type="number" name="hours_to_achieve" id="hours_to_achieve" min="1">
                    </div>

                    <button type="submit">Add Employee</button>
                </form>
            </div>
        </section>
    </div>

</body>
</html>
