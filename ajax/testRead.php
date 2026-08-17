<?php
// 1. Set the header to ensure the browser and AJAX know this is JSON data
header('Content-Type: application/json; charset=utf-8');

// 2. Include your database connection script
require_once 'connectDB.php';

// The specific student ID requested
// $targetStudentID = 4937;
// Use POST value 
 $targetStudentID = $_POST['studentID'] ; 


// 3. Prepare the SQL query
$query = "SELECT 
            studentID, 
           
            CONCAT(Family_name, ' ', First_name) AS Name,
            Gender
        
          FROM students 
          WHERE studentID = ?";
          
// 4. Prepare the statement using the MySQLi connection ($conn)
if ($stmt = $conn->prepare($query)) {
    
    // 5. Bind the parameter ('i' stands for integer) and execute
    $stmt->bind_param("i", $targetStudentID);
    $stmt->execute();
    
    // 6. Get the result set
    $result = $stmt->get_result();
    
    // 7. Fetch the single record as an associative array
    $studentRecord = $result->fetch_assoc();

    // 8. Output the JSON
    if ($studentRecord) {
        echo json_encode($studentRecord);
    } else {
        echo json_encode(['error' => 'No student found with ID 4936.']);
    }

    $stmt->close();

} else {
    // Catch MySQLi preparation errors
    echo json_encode(['error' => 'Database error: ' . $conn->error]);
}
?>