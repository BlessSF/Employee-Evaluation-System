<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['floating_message'])): ?>
    <div class="floating-notification">
        <?= htmlspecialchars($_SESSION['floating_message']) ?>
        <button onclick="document.querySelector('.floating-notification').style.display='none';">Close</button>
    </div>
    <?php unset($_SESSION['floating_message']); ?>
<?php endif;

// Database connection
$conn = new mysqli('localhost', 'root', '', 'employee_evaluation');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Handle delete all employees for a branch or sub-branch
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_all'])) {
    $branch_to_delete = $_POST['branch'];
    $sub_branch_to_delete = $_POST['sub_branch'] ?? null;

    if ($branch_to_delete === 'PUB' && $sub_branch_to_delete) {
        // Delete employees in a specific sub-branch
        $stmt = $conn->prepare("DELETE FROM employees WHERE branch = ? AND sub_branch = ? AND status = 'Probationary'");
        $stmt->bind_param('ss', $branch_to_delete, $sub_branch_to_delete);
    } else {
        // Delete employees in a branch
        $stmt = $conn->prepare("DELETE FROM employees WHERE branch = ? AND status = 'Probationary'");
        $stmt->bind_param('s', $branch_to_delete);
    }

    if ($stmt->execute()) {
        $_SESSION['floating_message'] = "All probationary employees from $branch_to_delete" . ($sub_branch_to_delete ? " ($sub_branch_to_delete)" : "") . " have been deleted.";
        header("Location: employees.php");
        exit;
    } else {
        echo "Error deleting employees: " . $conn->error;
    }
}

// Fetch probationary employees grouped by branch
$branches = ['STELLA', 'DOIS', 'PUB'];
$employees_by_branch = [];
$pub_sub_branches = [
    'Main Office',
    'Nina Food Products Trading',
    'Shock Sisig',
    'Pub Express Resto-Employers'
]; // Updated sub-branch names

foreach ($branches as $branch) {
    if ($branch === 'PUB') {
        // For PUB, fetch employees grouped by sub-branch
        $stmt = $conn->prepare("SELECT id, surname, first_name, date_hired, status, sub_branch FROM employees WHERE branch = ? AND status = 'Probationary' ORDER BY sub_branch, surname ASC");
    } else {
        // For other branches, fetch only probationary employees
        $stmt = $conn->prepare("SELECT id, surname, first_name, date_hired, status FROM employees WHERE branch = ? AND status = 'Probationary' ORDER BY surname ASC");
    }
    $stmt->bind_param('s', $branch);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($branch === 'PUB') {
        while ($row = $result->fetch_assoc()) {
            $sub_branch = $row['sub_branch'] ?? 'No Sub-Branch';
            $employees_by_branch[$branch][$sub_branch][] = $row;
        }
        // Ensure all sub-branches are listed, even if empty
        foreach ($pub_sub_branches as $sub_branch) {
            if (!isset($employees_by_branch[$branch][$sub_branch])) {
                $employees_by_branch[$branch][$sub_branch] = [];
            }
        }
    } else {
        // Group probationary employees from non-PUB branches
        $employees_by_branch[$branch] = $result->fetch_all(MYSQLI_ASSOC);
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Probationary Employees by Branch</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Existing styles */
        .sidenav a.active {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .sidenav a:hover {
            background-color: #0056b3;
            color: white;
        }

        .branch-container {
            margin: 20px 0;
        }
        

        .branch-title {
            font-size: 1.8em;
            margin-bottom: 10px;
            text-align: center;
            color: #333;
           
        }
        .branch-title {
            border: 5px solid #107454;
            padding:5px;
            background-color: #107454;
            color: white;
            
        }

        .branch-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
            
        }

        .branch-table th,
        .branch-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            
        }

        .branch-table th {
            background-color:#67eeb8;
        }

        .action-buttons a, .action-buttons button {
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .edit-button {
            background-color: #007bff;
            color: white;
        }

        .edit-button:hover {
            background-color: #0056b3;
        }

        .delete-button, .delete-all-button {
            background-color: rgb(240, 31, 24);
            color: white;
            border: none;
            padding: 12px 10px;
            
        }

        .delete-button:hover, .delete-all-button:hover {
            background-color: #c12e2a;
        }

        .add-button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 10px;
        }

        .add-button:hover {
            background-color: #218838;
        }

        .search-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        .search-bar input {
            padding: 10px;
            font-size: 16px;
            width: 300px;
            border-radius: 4px;
            border: 1px solid black;
        }

        @media (max-width: 768px) {
            .branch-title {
                font-size: 1.5em;
            }

            .branch-table th,
            .branch-table td {
                font-size: 0.9em;
                padding: 8px;
            }

            .add-button {
                display: block;
                margin: 10px auto;
                text-align: center;
            }

            .search-bar input {
                width: 80%;
            }

            .branch-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }

        @media (max-width: 480px) {
            .branch-title {
                font-size: 1.2em;
            }

            .branch-table th,
            .branch-table td {
                font-size: 0.8em;
                padding: 6px;
            }

            .search-bar input {
                width: 100%;
                margin-bottom: 20px;
            }

            .add-button {
                font-size: 14px;
                padding: 8px 12px;
            }

            .action-buttons a,
            .action-buttons button {
                font-size: 12px;
            }

            .sidenav {
                width: 100%;
                padding: 10px 0;
            }

            .sidenav a {
                font-size: 14px;
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
<div class="sidenav">
    <div class="logo-container">
        <img src="images/Logo.jpg" alt="Nina Trading Logo" class="logo">
    </div>
    <a href="index.php">Dashboard</a>
    <a href="employees.php" class="active">Probationary Employees</a>
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
        <h1>Probationary Employees by Branch</h1>
    </header>

    <!-- Single Search Bar -->
    <div class="search-bar">
        <input type="text" id="globalSearch" onkeyup="searchEmployee()" placeholder="Search any employee by surname...">
    </div>

    <?php foreach ($employees_by_branch as $branch => $data): ?>
        <div class="branch-container">
            <h2 class="branch-title"><?= htmlspecialchars($branch) ?> Branch</h2>

            <?php if ($branch === 'PUB'): ?>
                <?php foreach ($data as $sub_branch => $employees): ?>
                    <h3 class="branch-title"><?= htmlspecialchars($sub_branch) ?></h3>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="branch" value="<?= htmlspecialchars($branch) ?>">
                        <input type="hidden" name="sub_branch" value="<?= htmlspecialchars($sub_branch) ?>">
                        <button type="submit" name="delete_all" class="delete-all-button" onclick="return confirm('Are you sure you want to delete all employees in <?= htmlspecialchars($sub_branch) ?>?')">Delete All Employees</button>
                    </form>
                    <a href="add_employee.php" class="add-button">+ Add Employee</a>

                    <table class="branch-table" id="employeeTable<?= htmlspecialchars($branch) ?><?= htmlspecialchars($sub_branch) ?>">
                        <thead>
                            <tr>
                                <th>Surname</th>
                                <th>First Name</th>
                                <th>Date Hired</th>
                                <th>Employment Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($employees)): ?>
                                <?php foreach ($employees as $employee): ?>
                                    <tr class="employee-row">
                                        <td><?= htmlspecialchars($employee['surname']) ?></td>
                                        <td><?= htmlspecialchars($employee['first_name']) ?></td>
                                        <td><?= htmlspecialchars($employee['date_hired']) ?></td>
                                        <td><?= ucfirst(strtolower(htmlspecialchars($employee['status']))) ?></td>
                                        <td class="action-buttons">
                                            <a class="edit-button" href="edit_employee.php?id=<?= $employee['id'] ?>">Edit</a>
                                            <a class="delete-button" href="delete_employee.php?id=<?= $employee['id'] ?>" onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="no-employees">No probationary employees found in this sub-branch</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            <?php else: ?>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="branch" value="<?= htmlspecialchars($branch) ?>">
                    <button type="submit" name="delete_all" class="delete-all-button" onclick="return confirm('Are you sure you want to delete all employees in <?= htmlspecialchars($branch) ?>?')">Delete All Employees</button>
                </form>
                <a href="add_employee.php" class="add-button">+ Add Employee</a>

                <table class="branch-table" id="employeeTable<?= htmlspecialchars($branch) ?>">
                    <thead>
                        <tr>
                            <th>Surname</th>
                            <th>First Name</th>
                            <th>Date Hired</th>
                            <th>Employment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data)): ?>
                            <?php foreach ($data as $employee): ?>
                                <tr class="employee-row">
                                    <td><?= htmlspecialchars($employee['surname']) ?></td>
                                    <td><?= htmlspecialchars($employee['first_name']) ?></td>
                                    <td><?= htmlspecialchars($employee['date_hired']) ?></td>
                                    <td><?= ucfirst(strtolower(htmlspecialchars($employee['status']))) ?></td>
                                    <td class="action-buttons">
                                        <a class="edit-button" href="edit_employee.php?id=<?= $employee['id'] ?>">Edit</a>
                                        <a class="delete-button" href="delete_employee.php?id=<?= $employee['id'] ?>" onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="no-employees">No probationary employees found in this branch</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<script>
    function searchEmployee() {
        const input = document.getElementById('globalSearch').value.toLowerCase();
        const rows = document.querySelectorAll('.employee-row');

        rows.forEach(row => {
            const surname = row.cells[0].textContent.toLowerCase();
            if (surname.indexOf(input) > -1) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>

</body>
</html>

<?php $conn->close(); ?>
