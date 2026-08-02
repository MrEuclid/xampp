<?php
// 1. Define database connection variables for local XAMPP server
$host = "localhost";      // The server where the database is hosted
$username = "root";       // Default XAMPP MySQL username
$password = "pio2002pio";           // Default XAMPP MySQL password (leave empty)
$dbname = "pio";          // The name of your database

// 2. Create the MySQLi connection
$conn = new mysqli($host, $username, $password, $dbname);
echo "Database connected!";
// 3. Check if the connection was successful
if ($conn->connect_error) {
    // If this is strictly used for an AJAX/JSON backend, it is best to output a JSON error
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    
    // Stop the script from running any further
    exit(); 
}

// 4. Set the character set to utf8mb4 to safely handle all characters (including Khmer text)
$conn->set_charset("utf8mb4");

// Note: You do not need to echo anything on a successful connection. 
// If it works, this file simply finishes running silently, 
// and the script that included it (like read.php) takes over.
?>