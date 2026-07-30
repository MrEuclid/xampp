<?php
// 1. Database Connection Variables
$host = 'localhost'; // The server where the database lives (your local machine)
$db   = 'pio';       // The name of your database
$user = 'root';      // The default XAMPP database username
$pass = 'pio2002pio';// The password you configured
$charset = 'utf8mb4';// Use standard UTF-8 encoding for text

// 2. Data Source Name (DSN)
// This is the formatted string that tells PDO exactly where and what to connect to.
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// 3. PDO Options
// These settings ensure PDO throws detailed error messages and fetches data cleanly.
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return data as associative arrays (e.g., $row['name'])
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use true prepared statements for maximum security
];

// 4. Establish the Connection
try {
    // We create a new PDO instance and store it in the $pdo variable
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Optional: You can uncomment the line below to test if it works, 
    // but remember to comment it out again before building the rest of the site!
     echo "Connected to the PIO database successfully!"; 
    
} catch (\PDOException $e) {
    // If the connection fails, this catches the error and displays it
    // Example: Wrong password, database doesn't exist, or MySQL isn't running
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>

