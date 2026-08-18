<?php
// 1. Set the header to output JSON
header('Content-Type: application/json; charset=utf-8');

// 2. Capture the year parameter sent by the frontend (e.g., "2026-2027")
$rawYear = $_GET['year'] ?? '';

// 3. Extract the Start Year and End Year
if (strlen($rawYear) >= 9) {
    $startYear = intval(substr($rawYear, 0, 4)); // Grabs the first 4 chars
    $endYear = intval(substr($rawYear, -4));     // Grabs the last 4 chars
} else {
    // Fallback just in case the parameter is missing
    $currentMonth = (int) date('m');
    $currentYear = (int) date('Y');
    // If Sept (9) or later, start year is current year
    $startYear = ($currentMonth >= 9) ? $currentYear : $currentYear - 1; 
    $endYear = $startYear + 1;
}

// 4. Build the array of test codes
$tests = [];

// A. The late months of the START year (Oct, Nov, Dec)
$tests[] = ['code' => $startYear . '-10'];
$tests[] = ['code' => $startYear . '-11'];
$tests[] = ['code' => $startYear . '-12'];

// B. Semester 1 Test uses the END year (e.g., SEM1-2027)
$tests[] = ['code' => 'SEM1-' . $endYear];

// C. The early months of the END year (Jan through Aug)
for ($month = 1; $month <= 8; $month++) {
    // str_pad ensures single digits get a leading zero (e.g., '1' becomes '01')
    $monthString = str_pad($month, 2, '0', STR_PAD_LEFT);
    $tests[] = ['code' => $endYear . '-' . $monthString];
}

// D. Semester 2 Test uses the END year
$tests[] = ['code' => 'SEM2-' . $endYear];

// 5. Output the JSON array
echo json_encode($tests);
?>