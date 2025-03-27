<?php 
// Start the session and check if the user is an admin
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Check if 'id' parameter is passed through the URL
if (isset($_GET['id'])) {
    $employee_id = $_GET['id'];

    // Sanitize the employee ID to prevent SQL injection
    $employee_id = filter_var($employee_id, FILTER_SANITIZE_NUMBER_INT);

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'employee_evaluation');
    
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Prepare and execute the DELETE query for the specific employee
    $stmt = $conn->prepare("DELETE FROM ojt_employees WHERE id = ?");
    $stmt->bind_param('i', $employee_id);

    // Debugging: Check if the prepared statement is valid
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    // Execute the statement
    if ($stmt->execute()) {
        // Check if rows were affected
        if ($stmt->affected_rows > 0) {
            // Set a session message to confirm the deletion
            $_SESSION['floating_message'] = "Employee with ID $employee_id has been deleted.";
        } else {
            // If no rows were affected, show a different message
            $_SESSION['floating_message'] = "No employee found with ID $employee_id to delete.";
        }
        // Redirect to the OJT Employees page
        header("Location: ojt_employees.php");
        exit;
    } else {
        // Handle errors in case the deletion fails
        echo "Error deleting employee: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // If the 'id' parameter is missing, redirect to the OJT employees page
    header("Location: ojt_employees.php");
    exit;
}
?>
