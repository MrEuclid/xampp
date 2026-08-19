<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'connectDB.php';

$subjectCode = $_GET['subjectCode'] ?? '';
$classCode = $_GET['classCode'] ?? ''; // e.g., "G10", "G12B"

$maxScore = 100; // Default fallback safety net

$query = "SELECT max FROM hsMaxima WHERE subjectCode = ? AND grade = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("ss", $subjectCode, $classCode);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $maxScore = intval($row['max']);
    }
    $stmt->close();
}

echo json_encode(['max' => $maxScore]);
?>