<?php
// 1. Set the header to ensure the browser and AJAX know this is JSON data
header('Content-Type: application/json; charset=utf-8');

// Include your database connection script
require_once 'connectDB.php';

// 2. Capture the parameters sent by the frontend AJAX
/*
$targetSchool = $_GET['school'] ?? '';
$rawYear = $_GET['schoolYear'] ?? '';
$classCode = $_GET['classCode'] ?? '';
*/

$targetSchool = 'PIOHS'
$rawYear = 2026;
$classCode = 'ENG' ;

// 3. Transform the year data (using the last 4 characters)
$targetYear = intval(substr($rawYear, -4));

// Map the school value to match the database

if ($targetSchool === 'school_1') {
    $targetSchool = 'PIOHS';
} elseif ($targetSchool === 'school_2') {
    $targetSchool = 'Primary'; 
}



// 4. The Query
// IMPORTANT: Update 'students', 'student_id', 'first_name', etc., to match your actual database schema
$query = "SELECT student_id, first_name, last_name 
          FROM students 
          WHERE school = ? 
          AND year = ? 
          AND class_code = ?
          ORDER BY first_name ASC";        

// Prepare the statement
if ($stmt = $conn->prepare($query)) {
    
    // Bind the parameters: 's' (string for school), 'i' (int for year), 's' (string for classCode)
    $stmt->bind_param("sis", $targetSchool, $targetYear, $classCode);
    $stmt->execute();
    
    // Get the result set
    $result = $stmt->get_result();

    // Initialize array to hold the student records
    $students = []; 
    
    // Loop through results and append to the array
    while ($row = $result->fetch_assoc()) { 
        $students[] = $row; 
    } 

    // Output the JSON (return empty array if no students found so JS doesn't break)
    if (!empty($students)) {
        echo json_encode($students);
    } else {
        echo json_encode([]);
    }

    $stmt->close();

} else {
    // Catch MySQLi preparation errors
    echo json_encode(['error' => 'Database error: ' . $conn->error]);
}
?>