<?php 
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "employee_evaluation");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get admin username from session
$admin_username = $_SESSION['admin_username'] ?? 'Unknown';  // Use session username if available

// Handle search query
$search_query = isset($_GET['search']) ? trim($_GET['search']) : "";

// Modify the SQL to handle empty or NULL changed_by values
$sql = "
    SELECT l.*, e.surname, e.first_name,
           IFNULL(NULLIF(l.changed_by, ''), 'Admin') AS changed_by,
           DATE_FORMAT(l.changed_at, '%Y-%m-%d') AS changed_at  -- Format the date to show only the YYYY-MM-DD part
    FROM employee_audit_log AS l
    LEFT JOIN employees AS e ON l.employee_id = e.id
    WHERE (e.surname LIKE ? OR e.first_name LIKE ? OR l.changed_by LIKE ?)
    ORDER BY l.changed_at DESC
";
$stmt = $conn->prepare($sql);

// If there's a search term, filter the records accordingly
$search_param = "%{$search_query}%";
$stmt->bind_param("sss", $search_param, $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();

/**
 * Helper function to parse key-value pairs from log
 */
function parseKeyValueString($str) {
    $pairs = explode(',', $str);
    $data  = [];
    foreach ($pairs as $pair) {
        $pair = trim($pair);
        if (strpos($pair, ':') !== false) {
            list($key, $val) = explode(':', $pair, 2);
            $key = trim($key);
            $val = trim($val);
            $data[$key] = $val;
        }
    }
    return $data;
}

/**
 * Show only changed fields between old & new values
 */
function buildDifferences($oldVal, $newVal) {
    $oldArr = parseKeyValueString($oldVal);
    $newArr = parseKeyValueString($newVal);

    $diffs = [];
    foreach ($newArr as $key => $newV) {
        $oldV = isset($oldArr[$key]) ? $oldArr[$key] : '';
        if ($oldV !== $newV) {
            $diffs[] = "$key: '$oldV' → '$newV'";
        }
    }
    return !empty($diffs) ? implode('; ', $diffs) : "No fields changed.";
}

// If AJAX request, return only the table rows
if (isset($_GET['ajax'])) {
    $rows = '';
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        $rows .= '<tr>
                    <td>' . $counter++ . '</td>
                    <td>' . htmlspecialchars($row['surname'] . ' ' . $row['first_name'] ?: 'Deleted Employee') . '</td>
                    <td>' . htmlspecialchars($row['action']) . ': ' . buildDifferences($row['old_value'], $row['new_value']) . '</td>
                    <td>' . htmlspecialchars($row['changed_by']) . '</td>
                    <td>' . htmlspecialchars($row['changed_at']) . '</td>
                </tr>';
    }
    echo $rows;
    exit;
}

// Format Action and Changes
function formatAction($action, $oldVal, $newVal) {
    $formattedAction = "<strong>" . htmlspecialchars($action) . "</strong>";  // Make the action bold
    if (strcasecmp($action, 'Update') === 0) {
        return $formattedAction . ": " . buildDifferences($oldVal, $newVal);
    } elseif (strcasecmp($action, 'Add') === 0) {
        return $formattedAction . ": Employee Added.";
    } elseif (strcasecmp($action, 'Delete') === 0) {
        return "<strong>Delete</strong>: Employee Deleted.";
    }
    return $formattedAction;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Tracking Logs</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .sidenav a.active {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .main {
            margin-left: 260px;
            padding: 20px;
        }
        h1 {
            text-align: center;
            background-color: #087356;
            color: white;
            padding: 15px;
            border-radius: 5px;
        }
        .sidenav a.active {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        /* Table Styling */
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        thead {
            background-color: #007bff;
            color: black;
        }
        th, td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        th {
            font-weight: bold;
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
        /* Search bar */
        .search-container {
            text-align: center;
            margin: 20px 0;
        }
        .search-container input {
            width: 150px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        /* Responsive for smaller screens */
        @media (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
               
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
                width:100%;
            }
        
            thead {
                background-color: #007bff;
                color: white;    
                
            }

            tr {
                margin-bottom: 15px;
                background-color: #fff;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }

            
            td {
                padding: 8px;
                border-bottom: none;
            }
        }

        @media (max-width: 480px) {
            .search-container input {
                width: 350px;
                margin-bottom: 20px;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .view-details, .download-pdf {
                font-size: 12px;
                padding: 6px 8px;
            }
        }

    </style>
    <script>
        function searchEmployee() {
            let query = document.getElementById("searchInput").value.trim();

            // Create a new XMLHttpRequest object
            let xhr = new XMLHttpRequest();

            // Define what happens when the request finishes
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Update only the table body content with the response (AJAX)
                    document.getElementById("tableBody").innerHTML = xhr.responseText;
                }
            };

            // If the search query is empty, reload the page to show all data
            if (query === "") {
                location.reload();  // Refresh the page to show all records
            } else {
                // Open a GET request to fetch the table rows (AJAX)
                xhr.open('GET', 'employee_tracking.php?search=' + encodeURIComponent(query) + '&ajax=true', true);
                xhr.send();
            }
        }
    </script>
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
        <a href="ojt_hours.php" >OJT HOURS</a>
        <a href="upload.php">Upload Employee Data</a>
        <a href="notification.php">Employee Evaluations</a>
        <a href="evaluated_employees.php">Evaluated Employees</a>
        <a href="employee_tracking.php"class="active">Employee Actions Tracking</a>
        <a href="payroll_data.php">View Payroll Data</a>
        <a href="logout.php" class="logout-button">Log Out</a>
</div>

<div class="main">
    <h1>Employee Audit Log</h1>

    <!-- Search Bar -->
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search employee by name or changed by..." 
               value="<?= htmlspecialchars($search_query) ?>" onkeyup="searchEmployee()">
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Employee Name</th>
                    <th>Action & Changes</th>
                    <th>Changed By</th>
                    <th>Changed At</th>
                </tr>
            </thead>
            <tbody id="tableBody">
            <?php 
            $counter = 1;
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $counter++; ?></td>
                    <td><?= htmlspecialchars($row['surname'] . ', ' . $row['first_name'] ?: 'Deleted Employee') ?></td>
                    <td><?= formatAction($row['action'], $row['old_value'], $row['new_value']) ?></td>
                    <td><?= htmlspecialchars($row['changed_by']) ?></td>
                    <td><?= htmlspecialchars($row['changed_at']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center;">No matching records found.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php $conn->close(); ?>
