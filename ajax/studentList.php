<?php
// 1. Set the header to ensure the browser and AJAX know this is JSON data
header('Content-Type: application/json; charset=utf-8');

// Include your database connection script
require_once 'connectDB.php';

// Captured parameters
$targetSchool = $_GET['school'] ?? '';
$rawYear = $_GET['schoolYear'] ?? '';
$classCode = $_GET['classCode'] ?? '';
$subjectCode = $_GET['subjectCode'] ?? '';
$testCode = $_GET['testCode'] ?? '';

$targetYear = intval(substr($rawYear, -4));

// The corrected query: using `ig.Year` (capitalized) and `ig.Grade`
$query = "SELECT s.studentID, 
                 CONCAT(s.First_name, ' ', s.Family_name) AS name, 
                 m.score 
          FROM students s
          JOIN id_year_grade ig ON s.studentID = ig.studentID
          LEFT JOIN hsmarks m ON s.studentID = m.studentID 
               AND m.classCode = ? 
               AND m.subjectCode = ? 
               AND m.testCode = ?
          WHERE ig.school = ? 
          AND ig.Year = ? 
          AND ig.Grade = ?
          AND s.Gone = 'N'
          ORDER BY s.Family_name, s.First_name ASC";

if ($stmt = $conn->prepare($query)) {
    // Bind parameters: classCode, subjectCode, testCode, school, year, grade
    $stmt->bind_param("ssssis", $classCode, $subjectCode, $testCode, $targetSchool, $targetYear, $classCode);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = []; 
    while ($row = $result->fetch_assoc()) { 
        $students[] = $row; 
    } 

    echo json_encode($students);
    $stmt->close();
} else {
    echo json_encode(['error' => 'Database error: ' . $conn->error]);
}
?>