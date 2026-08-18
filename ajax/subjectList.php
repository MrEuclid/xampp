<?php
// 1. Set the header to ensure the browser and AJAX know this is JSON data
header('Content-Type: application/json; charset=utf-8');

//  Include your database connection script
require_once 'connectDB.php';

$query = "SELECT code FROM hssubjects ORDER BY code ASC"; // Adjust the query as needed

         
//  Prepare the statement using the MySQLi connection ($conn)
if ($stmt = $conn->prepare($query)) {
    
// Execute
    $stmt->execute();
    
    // Get the result set
    $result = $stmt->get_result();

  //  Initialize an empty array to hold all the records
    $studentRecords = []; 
    // Loop through the result set one row at a time 
  while ($row = $result->fetch_assoc()) { 
    // Append each individual row to our main array 
  $subjectss[] = $row; } 

    //  Output the JSON
    if ($subjectss) {
        echo json_encode($subjectss);
    } else {
        echo json_encode(['error' => 'No records found.']);
    }

    $stmt->close();

} else {
    // Catch MySQLi preparation errors
    echo json_encode(['error' => 'Database error: ' . $conn->error]);
}
?>