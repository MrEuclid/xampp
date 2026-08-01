<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIO Portal</title>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIO Portal - Student Lookup</title>
    
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
            <h2 class="text-3xl font-bold text-gray-800 mb-3">Student Record Lookup</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">
                Enter a Student ID below to fetch records asynchronously using AJAX and display them dynamically.
            </p>
        </div>

        <!-- SEARCH SECTION -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-10">
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <div class="w-full flex-grow">
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">Student ID</label>
                    <input type="text" id="student_id" placeholder="e.g., 101" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pioGreen-600 focus:outline-none">
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
                            <th class="py-3 px-6 border-b">ID</th>
                            <th class="py-3 px-6 border-b">Name</th>
                            <th class="py-3 px-6 border-b">Gender</th>
                            <th class="py-3 px-6 border-b">Grade</th>
                        </tr>
                    </thead>
                    <tbody id="student_table_body" class="text-gray-700 text-sm">
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-400">No records searched yet. Enter an ID above.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CRUD Navigation Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="create.php" class="group block bg-white rounded-2xl p-6 border-t-4 border-pioYellow-400 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-pioYellow-500 transition-colors">✏️ Add New Student</h3>
                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded">CREATE</span>
                </div>
                <p class="text-gray-500 text-sm mb-4">Register a new student into the system by filling out the enrollment form.</p>
                <div class="text-pioYellow-500 font-semibold text-sm flex items-center gap-1">
                    Open Form <span>&rarr;</span>
                </div>
            </a>

            <a href="update.php" class="group block bg-white rounded-2xl p-6 border-t-4 border-blue-500 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-blue-600 transition-colors">🔄 Update Record</h3>
                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded">UPDATE</span>
                </div>
                <p class="text-gray-500 text-sm mb-4">Edit existing student information, correct mistakes, or update contact details.</p>
                <div class="text-blue-600 font-semibold text-sm flex items-center gap-1">
                    Edit Records <span>&rarr;</span>
                </div>
            </a>
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
                var studentId = $('#student_id').val().trim();

                if (studentId === '') {
                    alert('Please enter a student ID.');
                    return;
                }

                // Send AJAX POST request to ajax/read.php
                $.ajax({
                    url: 'ajax/read.php',
                    type: 'POST',
                    dataType: 'json',
                 //   data: { id: studentId },
                    success: function(response) {
                        var tbody = $('#student_table_body');
                        tbody.empty(); // Clear previous results

                        // Check if the response contains records
                        if (response && response.length > 0) {
                            $.each(response, function(index, student) {
                                var row = '<tr class="hover:bg-gray-50 border-b border-gray-100">' +
                                    '<td class="py-3 px-6 font-medium text-gray-900">' + student.id + '</td>' +
                                    '<td class="py-3 px-6">' + student.studentID + '</td>' +
                                    '<td class="py-3 px-6">' + student.name + '</td>' +
                                    '<td class="py-3 px-6">' + student.gender + '</td>' +
                                    '<td class="py-3 px-6">' + student.grade + '</td>' +
                                    '</tr>';
                                tbody.append(row);
                            });
                        } else {
                            tbody.append('<tr><td colspan="4" class="text-center py-6 text-red-500">No student found with that ID.</td></tr>');
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
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Configure Custom School Colors -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pioGreen: {
                            600: '#15803d', // Tailwind green-700 equivalent
                            700: '#166534', // Tailwind green-800 equivalent
                            900: '#14532d', // Tailwind green-900 equivalent
                        },
                        pioYellow: {
                            400: '#facc15', // Tailwind yellow-400
                            500: '#eab308', // Tailwind yellow-500
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
                <!-- Simple SVG Logo Placeholder -->
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
            <h2 class="text-3xl font-bold text-gray-800 mb-3">Welcome to the Student Database</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">
                Use this portal to manage school records. This project demonstrates basic Create, Read, Update, and Delete (CRUD) operations using PHP and MySQL.
            </p>
        </div>

        <!-- CRUD Navigation Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- READ Card -->
            <a href="read.php" class="group block bg-white rounded-2xl p-6 border-t-4 border-pioGreen-600 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-pioGreen-700 transition-colors">📂 View Records</h3>
                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded">READ</span>
                </div>
                <p class="text-gray-500 text-sm mb-4">Browse the current database of enrolled students and view their details.</p>
                <div class="text-pioGreen-700 font-semibold text-sm flex items-center gap-1">
                    Open Database <span>&rarr;</span>
                </div>
            </a>

            <!-- CREATE Card -->
            <a href="create.php" class="group block bg-white rounded-2xl p-6 border-t-4 border-pioYellow-400 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-pioYellow-500 transition-colors">✏️ Add New Student</h3>
                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded">CREATE</span>
                </div>
                <p class="text-gray-500 text-sm mb-4">Register a new student into the system by filling out the enrollment form.</p>
                <div class="text-pioYellow-500 font-semibold text-sm flex items-center gap-1">
                    Open Form <span>&rarr;</span>
                </div>
            </a>

            <!-- UPDATE Card -->
            <a href="update.php" class="group block bg-white rounded-2xl p-6 border-t-4 border-blue-500 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-blue-600 transition-colors">🔄 Update Record</h3>
                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded">UPDATE</span>
                </div>
                <p class="text-gray-500 text-sm mb-4">Edit existing student information, correct mistakes, or update contact details.</p>
                <div class="text-blue-600 font-semibold text-sm flex items-center gap-1">
                    Edit Records <span>&rarr;</span>
                </div>
            </a>

            <!-- DELETE Card -->
            <a href="delete.php" class="group block bg-white rounded-2xl p-6 border-t-4 border-red-500 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-red-600 transition-colors">🗑️ Delete Record</h3>
                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded">DELETE</span>
                </div>
                <p class="text-gray-500 text-sm mb-4">Safely remove a student's data from the active database system.</p>
                <div class="text-red-600 font-semibold text-sm flex items-center gap-1">
                    Manage Removals <span>&rarr;</span>
                </div>
            </a>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-400 py-6 text-center text-sm mt-auto">
        <p>&copy; 2026 People Improvement Organization (PIO). Web Development Class Project.</p>
        <p class="mt-1">Powered by XAMPP, PHP, and MySQL.</p>
    </footer>

</body>
</html>