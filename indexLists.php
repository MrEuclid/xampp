<?php
// 1. Database Connection Setup (Integrated from getLists.php)
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// Ensure this path is correct relative to this new index.php file
require_once 'ajax/connectDB.php'; 

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    $charset = "utf8mb4";
    $conn->set_charset($charset);
   
} catch (mysqli_sql_exception $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}

// 2. Fetch Student IDs for the Datalist
try {
    // Querying the id_year_grade table to populate the datalist for the search
    $result = $conn->query("SELECT DISTINCT Grade FROM id_year_grade WHERE Year = 2026 AND School = 'PIOHS' ");
} catch (mysqli_sql_exception $e) {
    die("Query failed: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIO Portal - Class lists</title>
    
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include jQuery via CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Configure Custom School Colors -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pioGreen: {
                            600: '#15803d',
                            700: '#166534',
                            900: '#14532d',
                        },
                        pioYellow: {
                            400: '#facc15',
                            500: '#eab308',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-pioGreen-700 shadow-md">
        <div class="max-w-6xl mx-auto px-4 py-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-pioYellow-400 rounded-full flex items-center justify-center text-pioGreen-900 font-bold text-xl shadow-inner">
                    PIO
                </div>
                <h1 class="text-2xl font-bold text-pioYellow-400 tracking-wide">PIO Portal</h1>
            </div>
            <nav>
                <span class="text-white text-sm bg-pioGreen-900 px-3 py-1 rounded-full border border-pioGreen-600">Student Access</span>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-6xl mx-auto px-4 py-10 w-full">
        
        <!-- Welcome Banner -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-10 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-3">Class lists 2026</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">
                Select or class below to fetch records using AJAX and display them dynamically.
            </p>
        </div>

        <!-- SEARCH SECTION -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-10">
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <div class="w-full flex-grow">
                    <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Class ID</label>
                    
                    <!-- Integrated Datalist Input -->
                    <input type="text" 
                           id="class_id" 
                           list="id-list" 
                           placeholder="Start typing class eg G11A" 
                           autocomplete="off"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pioGreen-600 focus:outline-none">
                    
                    <!-- Dynamic Datalist Generation with MySQLi -->
                    <datalist id="id-list">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($row['Grade']) ?>">
                        <?php endwhile; ?>
                    </datalist>

                </div>
                <div class="w-full sm:w-auto self-end">
                    <button id="search_btn" 
                            class="w-full sm:w-auto bg-pioGreen-700 hover:bg-pioGreen-800 text-white font-semibold px-6 py-2 rounded-lg transition-colors shadow-sm">
                        Search Record
                    </button>
                </div>
            </div>
        </div>

        <!-- RESULTS TABLE SECTION -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-10">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800">Search Results</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-xs uppercase tracking-wider">
                            <th class="py-3 px-6 border-b">Student ID</th>
                            <th class="py-3 px-6 border-b">Name</th>
                            <th class="py-3 px-6 border-b">Gender</th>
                        </tr>
                    </thead>
                    <tbody id="student_table_body" class="text-gray-700 text-sm">
                        <tr>
                            <td colspan="3" class="text-center py-6 text-gray-400">No records searched yet. Enter an ID above.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-400 py-6 text-center text-sm mt-auto">
        <p>&copy; 2026 People Improvement Organization (PIO). Web Development Class Project.</p>
        <p class="mt-1">Powered by XAMPP, PHP, and MySQL.</p>
    </footer>

    <!-- AJAX Script -->
    <script>
        $(document).ready(function() {
            $('#search_btn').on('click', function() {
                var classID = $('#class_id').val().trim();

                if (classID === '') {
                    alert('Please enter a class ID.');
                    return;
                }

                // Send AJAX POST request to ajax/getClassList.php
                $.ajax({
                    url: 'ajax/getClassList.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { classID: classID }, 
                    success: function(response) {
                        var tbody = $('#student_table_body');
                        tbody.empty(); // Clear previous results
console.log("AJAX Response:", response); // Log the response for debugging
                        // Check if the response exists and does NOT contain an error property
                        if (response && !response.error) {
                            
                            // Build the row directly from the single object properties
                            var row = '<tr class="hover:bg-gray-50 border-b border-gray-100">' +
                                '<td class="py-3 px-6">' + response.studentID + '</td>' +
                                '<td class="py-3 px-6">' + response.Name + '</td>' +
                                '<td class="py-3 px-6">' + response.Grade + '</td>' +
                                '</tr>';
            
                            tbody.append(row);
                            
                        } else {
                            // If response.error exists, display the not found message
                            tbody.append('<tr><td colspan="3" class="text-center py-6 text-red-500">No student found with that ID.</td></tr>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + error);
                        alert('An error occurred while fetching data from the server.');
                    }
                });
            });
        });
    </script>

</body>
</html>

<?php
// Free up memory and close the connection
if (isset($result)) {
    $result->free();
}
if (isset($conn)) {
    $conn->close();
}
?>
