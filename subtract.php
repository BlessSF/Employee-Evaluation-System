<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ojt_id = $_POST['ojt_id'] ?? 0;
    $subtract_hours = $_POST['subtract_hours'] ?? 0;

    if (!is_numeric($ojt_id) || !is_numeric($subtract_hours)) {
        echo "Error";
        exit;
    }

    $conn = new mysqli('localhost', 'root', '', 'employee_evaluation');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Fetch the current hours
    $sql = "SELECT hours_to_achieve FROM ojt_employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $ojt_id);
    $stmt->execute();
    $stmt->bind_result($current_hours);
    $stmt->fetch();
    $stmt->close();

    if ($current_hours !== null) {
        $new_hours = max(0, $current_hours - $subtract_hours); // Prevent negative hours
        $update_sql = "UPDATE ojt_employees SET hours_to_achieve = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('ii', $new_hours, $ojt_id);
        $update_stmt->execute();
        $update_stmt->close();

        echo $new_hours;
    } else {
        echo "Error";
    }

    $conn->close();
}
?>
