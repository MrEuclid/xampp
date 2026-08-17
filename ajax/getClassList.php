<?php
// 1. Set the header to ensure the browser and AJAX know this is JSON data
header('Content-Type: application/json; charset=utf-8');

// 2. Include your database connection script
require_once 'connectDB.php';
$targetGrade = 'G12A' ;
$targetGrade = $_POST['classID'] ;
// 3. Prepare the SQL query
$query = "SELECT 
	students.studentID, 
    CONCAT(Family_name, ' ',First_name) as Name,
    Grade
FROM 
	`students` 
JOIN 
	id_year_grade
ON
	students.studentID = id_year_grade.studentID
WHERE 
	Year = 2026
AND
	Grade = ?
ORDER BY 
	Name";
          
// 4. Prepare the statement using the MySQLi connection ($conn)
if ($stmt = $conn->prepare($query)) {
    
    // 5. Bind the parameter ('i' stands for integer) and execute
    $stmt->bind_param("s", $targetGrade);
    $stmt->execute();
    
    // 6. Get the result set
    $result = $stmt->get_result();
    
   // 7. Initialize an empty array to hold all the records
    $studentRecords = []; 
    // Loop through the result set one row at a time 
  while ($row = $result->fetch_assoc()) { 
    // Append each individual row to our main array 
  $studentRecords[] = $row; } 

    // 8. Output the JSON
    if ($studentRecords) {
        echo json_encode($studentRecords);
    } else {
        echo json_encode(['error' => 'No records found.']);
    }

    $stmt->close();

} else {
    // Catch MySQLi preparation errors
    echo json_encode(['error' => 'Database error: ' . $conn->error]);
}
?>