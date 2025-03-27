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

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM ojt_employees WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_employee'])) {
    $id = $_POST['id'];
    $surname = $_POST['surname'];
    $first_name = $_POST['first_name'];
    $date_hired = $_POST['date_hired'];
    $hours_to_achieve = $_POST['hours_to_achieve'];
    $branch = $_POST['branch'];
    $sub_branch = isset($_POST['sub_branch']) ? $_POST['sub_branch'] : '';  // Sub-branch handling
    $status = $_POST['status'];

    // Update the query to account for sub-branch if applicable
    if ($branch === 'PUB') {
        $stmt = $conn->prepare("UPDATE ojt_employees SET surname = ?, first_name = ?, date_hired = ?, hours_to_achieve = ?, branch = ?, sub_branch = ?, status = ? WHERE id = ?");
        $stmt->bind_param('sssssssi', $surname, $first_name, $date_hired, $hours_to_achieve, $branch, $sub_branch, $status, $id);
    } else {
        $stmt = $conn->prepare("UPDATE ojt_employees SET surname = ?, first_name = ?, date_hired = ?, hours_to_achieve = ?, branch = ?, status = ? WHERE id = ?");
        $stmt->bind_param('ssssssi', $surname, $first_name, $date_hired, $hours_to_achieve, $branch, $status, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['floating_message'] = "Employee information updated successfully.";
        header("Location: ojt_employees.php");
        exit;
    } else {
        echo "Error updating employee: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit OJT Employee</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
      /* Center the form properly */
.edit-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh; /* Full height */
    padding: 20px;
    background-color:rgb(168, 215, 205);
}

/* Center form contents */
.form-container {
    width: 100%;
    max-width: 500px;
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    align-items: center; /* Center contents */
}

/* Center heading */
.form-container h2 {
    text-align: center;
    color: #107454;
}

/* Adjust labels */
.form-container label {
    font-size: 16px;
    margin-bottom: 5px;
    font-weight: bold;
    display: block;
    text-align: left;
    width: 100%;
}

/* Make inputs full width */
.form-container input,
.form-container select {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    border-color: black;
}

/* Date input fix */
.form-container input[type="date"] {
    font-size: 14px;
}

/* Center buttons */
.form-container .save-button,
.form-container .cancel-button {
    width: 100%;
    max-width: 150px;
    text-align: center;
    margin: 10px 0;
}

/* Button hover effects */
.form-container .save-button {
    background-color: #28a745;
    color: white;
    padding: 12px;
    border: none;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
}

.form-container .save-button:hover {
    background-color: #218838;
}

.form-container .cancel-button {
    background-color: #f44336;
    color: white;
    padding: 12px;
    border: none;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
}

.form-container .cancel-button:hover {
    background-color: #d32f2f;
}

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
    <a href="ojt_hours.php">OJT HOURS</a>
    <a href="upload.php">Upload Employee Data</a>
    <a href="notification.php">Employee Evaluations</a>
    <a href="evaluated_employees.php">Evaluated Employees</a>
    <a href="employee_tracking.php">Employee Actions Tracking</a>
    <a href="payroll_data.php">View Payroll Data</a>
    <a href="logout.php" class="logout-button">Log Out</a>
</div>

<div class="main">
    <div class="edit-container">
        <div class="form-container">
            <h2>Edit Employee</h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?= htmlspecialchars($employee['id']) ?>">
                <label for="surname">Surname:</label>
                <input type="text" id="surname" name="surname" value="<?= htmlspecialchars($employee['surname']) ?>" required>

                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($employee['first_name']) ?>" required>

                <label for="date_hired">Date Hired:</label>
                <input type="date" id="date_hired" name="date_hired" value="<?= htmlspecialchars($employee['date_hired']) ?>" required>

                <label for="hours_to_achieve">Hours to Achieve:</label>
                <input type="text" id="hours_to_achieve" name="hours_to_achieve" value="<?= htmlspecialchars($employee['hours_to_achieve']) ?>" required>

                <label for="branch">Branch:</label>
                <select id="branch" name="branch" onchange="toggleSubBranch()" required>
                    <option value="STELLA" <?= $employee['branch'] === 'STELLA' ? 'selected' : '' ?>>STELLA</option>
                    <option value="DOIS" <?= $employee['branch'] === 'DOIS' ? 'selected' : '' ?>>DOIS</option>
                    <option value="PUB" <?= $employee['branch'] === 'PUB' ? 'selected' : '' ?>>PUB</option>
                </select>

                <div id="subBranchContainer" style="display: none;">
                    <label for="sub_branch">Sub-Branch:</label>
                    <select id="sub_branch" name="sub_branch">
                        <option value="Main Office" <?= $employee['sub_branch'] === 'Main Office' ? 'selected' : '' ?>>Main Office</option>
                        <option value="Pub Express Resto-Employers" <?= $employee['sub_branch'] === 'Pub Express Resto-Employers' ? 'selected' : '' ?>>Pub Express Resto-Employers</option>
                        <option value="Nina Food Products Trading" <?= $employee['sub_branch'] === 'Nina Food Products Trading' ? 'selected' : '' ?>>Nina Food Products Trading</option>
                        <option value="Shock Sisig" <?= $employee['sub_branch'] === 'Shock Sisig' ? 'selected' : '' ?>>Shock Sisig</option>
                    </select>
                </div>

                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="OJT" <?= $employee['status'] === 'OJT' ? 'selected' : '' ?>>OJT</option>
                </select>

                <button type="submit" name="update_employee" class="save-button">Save Changes</button>
                <a href="ojt_employees.php" class="cancel-button">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script>
    // Show or hide sub-branch based on the selected branch
    function toggleSubBranch() {
        const branch = document.getElementById('branch').value;
        const subBranchContainer = document.getElementById('subBranchContainer');
        if (branch === 'PUB') {
            subBranchContainer.style.display = 'block';
        } else {
            subBranchContainer.style.display = 'none';
        }
    }

    // Run toggleSubBranch on page load to adjust visibility
    window.onload = toggleSubBranch;
</script>

</body>
</html>

<?php $conn->close(); ?>



