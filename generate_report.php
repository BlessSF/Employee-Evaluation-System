<?php
require_once('vendor/autoload.php'); // Load TCPDF using Composer
use TCPDF;

// Database connection (Moved to a separate function for reuse)
function getDbConnection() {
    $conn = new mysqli('localhost', 'root', '', 'employee_evaluation');
    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }
    return $conn;
}


// Fetch Employee Details
function getEmployeeDetails($conn, $employee_id) {
    $sql = "SELECT * FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $employee_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}


// Fetch Latest Evaluation
function getLatestEvaluation($conn, $employee_id) {
    $sql_eval = "SELECT appraisal_rating, summary_performance, recommendation 
                 FROM evaluations 
                 WHERE employee_id = ? 
                 ORDER BY evaluation_date DESC 
                 LIMIT 1";
    $stmt = $conn->prepare($sql_eval);
    $stmt->bind_param('i', $employee_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Get Employee ID from URL and Validate
$employee_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($employee_id === 0) {
    die('Invalid Employee ID');
}

try {
    // Establish database connection
    $conn = getDbConnection();

    // Fetch Employee Details
    $employee = getEmployeeDetails($conn, $employee_id);
    if (!$employee) {
        throw new Exception('Employee not found.');
    }

    // Fetch Latest Evaluation
    $evaluation = getLatestEvaluation($conn, $employee_id);
    $performance_rating = $evaluation ? $evaluation['appraisal_rating'] : "N/A";
    $summary_text = $evaluation ? $evaluation['summary_performance'] : "No evaluation record found.";
    $selected_recommendation = $evaluation ? trim($evaluation['recommendation']) : "";

    // Define Recommendation List with Checkmarks (Default)
    $recommendations = [
        "For Regularization" => "[ ] For Regularization",
        "For Promotion" => "[ ] For Promotion",
        "Continue status to improve performance" => "[ ] Continue status to improve performance",
        "For Termination" => "[ ] For Termination"
    ];

    // Apply checkmark to the selected recommendation based on evaluation
    foreach ($recommendations as $key => &$value) {
        if (strcasecmp($selected_recommendation, $key) === 0) { // Case-insensitive match
            $value = "[✔] $key"; // Add checkmark if it matches
        }
    }

    // Create PDF
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Multipliers Corp.');
    $pdf->SetTitle('Employee Evaluation Report');
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(TRUE, 10);

    // Remove the default header line
    $pdf->SetHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255)); // Set empty header

    // Set font for the header
    $pdf->setHeaderFont(Array('helvetica', 'B', 14));
    $pdf->setHeaderMargin(0); // Set header margin to 0 (no extra space)

    $pdf->AddPage();

    // Add Company Logo & Header
    $logoPath = 'images/Logo.jpg';
    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, 15, 10, 40, 0, 'JPG');
    } else {
        $pdf->Cell(0, 5, 'Logo Not Found', 0, 1, 'C');
    }

    // Header Details
    $pdf->SetY(15);
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 5, 'MULTIPLIERS CORP.', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, 'Mission Road Extension, Brgy. San Nicolas, LaPaz, Iloilo City', 0, 1, 'C');
    $pdf->Cell(0, 5, 'Tel No: (033) 801-2981', 0, 1, 'C');
    $pdf->Ln(10);

    // Performance Appraisal Form Title
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 8, 'PERFORMANCE APPRAISAL FORM', 0, 1, 'C');
    $pdf->Ln(10);

    // Employee Details (Including missing fields)
    $pdf->SetFont('helvetica', '', 11);

    // Set X and Y to match the left-aligned labels with right-aligned data.
    $pdf->SetX(15);
    $pdf->Cell(50, 6, "Employee Name:", 0, 0);
    $pdf->SetX(50);  // Adjusted X position for data
    $pdf->Cell(55, 6, "{$employee['first_name']} {$employee['surname']}", 0, 1);

    $pdf->SetX(15);
    $pdf->Cell(50, 6, "Job Title:", 0, 0);
    $pdf->SetX(40);  // Adjusted X position for data
    $pdf->Cell(90, 6, "{$employee['position']}", 0, 1);  // Correctly displaying Job Title (position)

    $pdf->SetX(15);
    $pdf->Cell(50, 6, "Department:", 0, 0);
    $pdf->SetX(40);  // Adjusted X position for data
    $pdf->Cell(60, 6, "{$employee['branch']}", 0, 1);

    $pdf->SetX(15);
    $pdf->Cell(50, 6, "Review Period Coverage:", 0, 0);
    $pdf->SetX(40);  // Adjusted X position for data
    $pdf->Cell(60, 6, "{$employee['review_period_coverage']}", 0, 1);  // Added Review Period Coverage

    $pdf->SetY($pdf->GetY() - 24);// Adjust the X position for the "right-aligned" fields
    
    $pdf->SetX(98);
    $pdf->Cell(50, 6, "Last Performance Review:", 0, 0);
    $pdf->Cell(80, 6, "{$employee['last_performance_review']}", 0, 1);  // Last Performance Review

    $pdf->SetX(98);
    $pdf->Cell(50, 6, "Appraiser Name of Last Review:", 0, 0);
    $pdf->Cell(80, 6, "{$employee['appraiser_name_last_review']}", 0, 1);  // Appraiser Name of Last Review

    $pdf->SetX(98);
    $pdf->Cell(50, 6, "Appraiser Name of Present Review:", 0, 0);
    $pdf->Cell(80, 6, "{$employee['appraiser_name_present_review']}", 0, 1);  // Appraiser Name of Present Review

    $pdf->SetX(98);
    $pdf->Cell(50, 6, "Appraiser Job Title:", 0, 0);
    $pdf->Cell(80, 6, "{$employee['appraiser_job_title']}", 0, 1);  // Appraiser Job Title

    $pdf->Ln(10);  // Add some space after these fields

    // Appraisal Rating and Description (Centered Title and Proper Table Alignment)
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 6, 'Appraisal Rating and Description', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 10);
    
    // Table Header with adjusted widths and table left-shifted
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(30, 6, 'Numerical Rating', 1, 0, 'C'); // Slightly bigger Numerical Rating column
    $pdf->Cell(50, 6, 'Descriptive Rating', 1, 0, 'L'); // Smaller Descriptive Rating column
    $pdf->Cell(110, 6, 'Definition of the Rating', 1, 1, 'L'); // Larger Definition of Rating column
    
    // Table Rows
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(30, 6, '5', 1, 0, 'C');
    $pdf->Cell(50, 6, 'EXCELLENT', 1, 0, 'L');
    $pdf->Cell(110, 6, 'Definitely the best performance', 1, 1, 'L');
    
    $pdf->Cell(30, 6, '4', 1, 0, 'C');
    $pdf->Cell(50, 6, 'ABOVE SATISFACTORY', 1, 0, 'L');
    $pdf->Cell(110, 6, 'Consistently fulfills the job requirements and exceeds expectations', 1, 1, 'L');
    
    $pdf->Cell(30, 6, '3', 1, 0, 'C');
    $pdf->Cell(50, 6, 'SATISFACTORY', 1, 0, 'L');
    $pdf->Cell(110, 6, 'Consistently fulfills the job requirements and follows the standards', 1, 1, 'L');
    
    $pdf->Cell(30, 6, '2', 1, 0, 'C');
    $pdf->Cell(50, 6, 'BELOW SATISFACTORY', 1, 0, 'L');
    $pdf->Cell(110, 6, 'Consistently failure to the job requirements, needs improvement', 1, 1, 'L');
    
    $pdf->Cell(30, 6, '1', 1, 0, 'C');
    $pdf->Cell(50, 6, 'POOR', 1, 0, 'L');
    $pdf->Cell(110, 6, 'Definitely cannot perform what is expected of the job requirements', 1, 1, 'L');
    
    $pdf->Ln(10);

    // Summary of Overall Performance (After the table)
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(0, 6, "SUMMARY OF OVERALL PERFORMANCE:", 0, 0); // Keeps the title on the same line

    // Adjusted to move the score more to the left
    $pdf->SetX(93);  // Move the X position to 50 to shift the score to the left
    $pdf->SetFont('helvetica', 'U', 12); // Underlined and bold font for the rating score
    $pdf->Cell(0, 6, "  $summary_text", 0, 1); // Only the performance score (e.g., 4.0) will be shown here in bold and underlined after the colon
    $pdf->Ln(10);
    $pdf->SetY($pdf->GetY() + 10);
    // Recommendations Section
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(0, 6, "RECOMMENDATION", 0, 1);
    $pdf->SetFont('helvetica', '', 10);

    // Display the recommendations with checkmarks dynamically applied
    foreach ($recommendations as $rec) {
        $pdf->Cell(0, 6, $rec, 0, 1);  // Display each recommendation with its checkmark
    }
    $pdf->Ln(10);
    $pdf->SetY($pdf->GetY() + 30);
    // Signature Section
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(60, 6, "Conducted By: ____________________", 0, 1);
    $pdf->Cell(60, 6, "Department Head", 0, 1);
    $pdf->Ln(6);

    $pdf->Cell(60, 6, "Noted By: ____________________", 0, 1);
    $pdf->Cell(60, 6, "Chief Executive Officer", 0, 1);
    $pdf->Ln(10);

    $pdf->SetY($pdf->GetY() - 24);// Adjust the X position for the "right-aligned" fields
    $pdf->SetX(98);
    $pdf->Cell(60, 6, "Reviewed By: ____________________", 0, 1);

    $pdf->SetY($pdf->GetY() - 2);// Adjust the X position for the "right-aligned" fields
    $pdf->SetX(138);
    $pdf->Cell(60, 6, "HRD", 0, 1);
    $pdf->Ln(6);
    
  

    // Close Database
    $conn->close();

    // Generate the download filename: Surname_FirstName_Evaluation_Report.pdf
    $filename = "{$employee['surname']}_{$employee['first_name']}_Evaluation_Report.pdf";

    // Output PDF for Download with custom filename
    $pdf->Output($filename, 'D');
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
