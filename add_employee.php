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

// 1) This block logs and inserts (if add_employee is set)
// ---------------------------
if (isset($_POST['add_employee'])) {
    $surname = $_POST['surname'];
    $first_name = $_POST['first_name'];
    $date_hired = $_POST['date_hired'];
    $branch = $_POST['branch'];
    $position = $_POST['position'];
    $sub_branch = ($branch === 'PUB') ? $_POST['sub_branch'] : null;  // Handle sub-branch for PUB only

    // Insert the new employee into the database
    $sql = "INSERT INTO employees (surname, first_name, date_hired, branch, sub_branch, position)
            VALUES ('$surname', '$first_name', '$date_hired', '$branch', '$sub_branch', '$position')";
    
    if ($conn->query($sql) === TRUE) {
        // Get the last inserted employee ID
        $employee_id = $conn->insert_id;

        // Log the action in the audit table
        $action = 'Add';
        $old_value = '';
        $new_value = "surname: $surname, first_name: $first_name, date_hired: $date_hired, branch: $branch, sub_branch: $sub_branch, position: $position";
        $changed_by = $_SESSION['admin_name'];  // Assuming session holds admin's name

        $log_sql = "INSERT INTO employee_audit_log (employee_id, action, old_value, new_value, changed_by)
                    VALUES ('$employee_id', '$action', '$old_value', '$new_value', '$changed_by')";
        $conn->query($log_sql);

        // Redirect to the employee tracking page
        header('Location: employee_tracking.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// ---------------------------
// 2) This block also inserts employees (with validation),
//    but only if add_employee is NOT set
// ---------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['add_employee'])) {
    $surname = trim($_POST['surname']);
    $first_name = trim($_POST['first_name']);
    $date_hired = $_POST['date_hired'];
    $status = $_POST['status'];
    $branch = $_POST['branch'];
    $sub_branch = ($branch === 'PUB') ? ($_POST['sub_branch'] ?? null) : null;  // Handle sub-branch for PUB only
    $position = trim($_POST['position']); // New position field

    // Server-side validation
    if (empty($position)) {
        $error_message = "Position is required.";
    } elseif (strlen($position) > 100) {
        $error_message = "Position cannot exceed 100 characters.";
    } elseif (!preg_match("/^[a-zA-Z\s]*$/", $position)) { // Only letters and spaces allowed
        $error_message = "Position can only contain letters and spaces.";
    } elseif (!empty($surname) && !empty($first_name) && !empty($date_hired) && !empty($status) && !empty($branch)) {
        if ($branch === 'PUB' && empty($sub_branch)) {
            $error_message = "Sub-Branch is required for PUB branch!";
        } else {
            $stmt = $conn->prepare("INSERT INTO employees (surname, first_name, date_hired, status, branch, sub_branch, position) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssssss', $surname, $first_name, $date_hired, $status, $branch, $sub_branch, $position);

            if ($stmt->execute()) {
                $_SESSION['floating_message'] = "Employee {$surname}, {$first_name} added successfully!";
                header('Location: employees.php');
                exit;
            } else {
                $error_message = "Failed to add employee. Please try again.";
            }
            $stmt->close();
        }
    } else {
        $error_message = "All fields are required!";
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
        /* Side Navigation */
        .sidenav a.active {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .sidenav a:hover {
            background-color: #0056b3;
            color: white;
        }

        /* Main Content */
        .main {
            margin-left: 260px;
            padding: 20px;
        }

        /* Form Styling */
        .form-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 1px 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid black;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .form-container label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .form-container input,
        .form-container select,
        .form-container button {
            width: 600px;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid black;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-container button {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
        }

        /* Floating Notification */
        .floating-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #d9534f;
            color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .floating-notification button {
            background-color: transparent;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-left: 10px;
        }
    </style>
    <script>
        function toggleSubBranch() {
            const branchSelect = document.getElementById('branch');
            const subBranchContainer = document.getElementById('subBranchContainer');
            subBranchContainer.style.display = branchSelect.value === 'PUB' ? 'block' : 'none';
        }

        // Client-side validation for position
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
            if (!/^[a-zA-Z\s]*$/.test(position)) { // Only letters and spaces allowed
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
            <img src="images/Logo.jpg" alt="Logo" class="logo">
        </div>
        <a href="index.php" >Dashboard</a>
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
                    <select name="status" id="status" required>
                        <option value="Probationary">Probationary</option>
                        <option value="Regular">Regular</option>
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

                    <button type="submit">Add Employee</button>
                </form>
            </div>
        </section>
    </div>
</body>
</html>
