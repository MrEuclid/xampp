<?php
// 1. Set the header to ensure the browser and AJAX know this is JSON data
header('Content-Type: application/json; charset=utf-8');

// Include your database connection script
require_once 'connectDB.php';

// 2. Capture the parameters sent by the frontend AJAX
$targetSchool = $_GET['school'] ?? '';
$rawYear = $_GET['year'] ?? '';

// 3. Transform the data to match your database (UPDATED)
// Using -4 grabs the LAST 4 characters (e.g., "2027" from "2026-2027")
$targetYear = intval(substr($rawYear, -4));

// Map the school value if needed
if ($targetSchool === 'school_1') {
    $targetSchool = 'PIOHS';
} elseif ($targetSchool === 'school_2') {
    $targetSchool = 'Primary'; 
}

// 4. Query using "Grade AS code"
$query = "SELECT DISTINCT Grade AS code 
          FROM id_year_grade 
          WHERE School = ?
          AND Year = ?
          ORDER BY Grade ASC";        

// Prepare the statement
if ($stmt = $conn->prepare($query)) {
    
    // Bind the parameter ('s' for string, 'i' for integer) and execute
    $stmt->bind_param("si", $targetSchool, $targetYear);
    $stmt->execute();
    
    // Get the result set
    $result = $stmt->get_result();

    // Initialize array to hold records
    $records = []; 
    
    // Loop through results
    while ($row = $result->fetch_assoc()) { 
        $records[] = $row; 
    } 

    // Output the JSON
    if (!empty($records)) {
        echo json_encode($records);
    } else {
        echo json_encode([]);
    }

    $stmt->close();

} else {
    // Catch MySQLi preparation errors
    echo json_encode(['error' => 'Database error: ' . $conn->error]);
}
?>