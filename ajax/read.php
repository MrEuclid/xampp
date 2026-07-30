<?php
// Set header to output JSON
header('Content-Type: application/json');

// Include your existing database connection file
require_once 'connectDB.php';

$response = array();

// Check if an ID was sent via POST
if (isset($_POST['id'])) {
    $studentId = trim($_POST['id']);

    try {
        // Assuming $pdo is the connection variable inside connectDB.php
        // Adjust the table name ('students') and column names to match your database schema
        $stmt = $pdo->prepare("SELECT studentID, Family_name, first_name,gender,Date_birth FROM students WHERE id = :id");
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
