<?php
// 1. Set the header to output JSON
header('Content-Type: application/json; charset=utf-8');

// Include your database connection script
require_once 'connectDB.php';

// 2. Read the raw POST data (since we sent JSON instead of standard form data)
$json_data = file_get_contents('php://input');
$request = json_decode($json_data, true);

if (!$request) {
    echo json_encode(['error' => 'Invalid JSON payload received.']);
    exit;
}

// 3. Extract the context variables
$rawSchool = $request['school'] ?? '';
$rawYear = $request['year'] ?? '';
$classCode = $request['classCode'] ?? '';
$subjectCode = $request['subjectCode'] ?? '';
$testCode = $request['testCode'] ?? '';
$marks = $request['marks'] ?? []; // This is our array of {student_id, score}

if (empty($marks)) {
    echo json_encode(['error' => 'No marks were provided.']);
    exit;
}

// 4. Transform School and Year to match database format
$targetYear = intval(substr($rawYear, -4));
$targetSchool = $rawSchool;

if ($targetSchool === 'school_1') {
    $targetSchool = 'PIOHS';
} elseif ($targetSchool === 'school_2') {
    $targetSchool = 'Primary'; 
}

// 5. Begin Database Transaction
$conn->begin_transaction();

try {
    // IMPORTANT: Update 'test_scores' and its columns to match your actual database table
    $query = "INSERT INTO test_scores 
              (studentID, classCode, subjectCode, testCode, score) 
              VALUES (?,  ?, ?, ?, ?) 
              ON DUPLICATE KEY UPDATE score = VALUES(score)";
    
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        throw new Exception("Query preparation failed: " . $conn->error);
    }

    $savedCount = 0;

    // 6. Loop through the array and execute the query for each student
    foreach ($marks as $mark) {
        $studentId = $mark['student_id'];
        $score = $mark['score'];
        
        // Bind parameters: 
        // s = string (school), i = integer (year), s = string (class), s = string (subject)
        // s = string (test), s = string (student_id), d = double/decimal (score)
        $stmt->bind_param("sissssd", 
            $studentId, 
            $classCode, 
            $subjectCode, 
            $testCode, 
            $score
        );
        
        $stmt->execute();
        $savedCount++;
    }

    // 7. If everything succeeded, commit the transaction
    $conn->commit();
    
    // Return success response
    echo json_encode([
        'success' => true, 
        'savedCount' => $savedCount
    ]);
    
} catch (Exception $e) {
    // If anything failed, roll back the transaction so no partial data is saved
    $conn->rollback();
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
}
?>