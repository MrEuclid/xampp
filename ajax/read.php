<?php
// Set header to output JSON
header('Content-Type: application/json');

// Include your existing database connection file
require_once 'connectDB.php';

$response = array();

// Check if an ID was sent via POST
if (isset($_POST['id'])) {
    $studentId <?php
// 1. Set the header to ensure the browser and AJAX know this is JSON data
header('Content-Type: application/json; charset=utf-8');

// 2. Include your database connection script
require_once 'connectDB.php';

// The specific student ID requested
$targetStudentID = 4936;

// 3. Prepare the SQL query using the exact columns from your table structure
$query = "SELECT 
            studentID, 
            Family_name, 
            First_name, 
            Khmer_family_name, 
            Khmer_first_name, 
            Gender, 
            Date_birth, 
            Gone 
          FROM students 
          WHERE studentID = ?";

// 4. Prepare the statement using your mysqli connection ($conn)
if ($stmt = $conn->prepare($query)) {
    
    // 5. Bind the parameter ('i' stands for integer) and execute
    $stmt->bind_param("i", $targetStudentID);
    $stmt->execute();
    
    // 6. Get the result set from the executed statement
    $result = $stmt->get_result();
    
    // 7. Fetch the single record as an associative array
    $studentRecord = $result->fetch_assoc();

    // 8. Check if data was found and output the JSON
    if ($studentRecord) {
        // Output the data as a clean JSON object
        echo json_encode($studentRecord);
    } else {
        // Output an error if ID 4936 doesn't exist
        echo json_encode(['error' => 'No student found with ID 4936.']);
    }

    // Clean up the statement
    $stmt->close();

} else {
    // Catch and format any SQL preparation errors as JSON
    echo json_encode(['error' => 'Database error: ' . $conn->error]);
}
?> trim($_POST['id']);

    try {
        // Assuming $pdo is the connection variable inside connectDB.php
        // Adjust the table name ('students') and column names to match your database schema
        $stmt = $pdo->prepare("SELECT id, name, class, email FROM students WHERE id = :id");
        $stmt->execute(['id' => $studentId]);
        
        // Fetch matching records into an array
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Send back the array as JSON
        echo json_encode($records);

    } catch (PDOException $e) {
        // Return an empty array or error structure if query fails
        echo json_encode(array('error' => $e->getMessage()));
    }
} else {
    // Return empty array if no ID was provided
    echo json_encode(array());
}
?>