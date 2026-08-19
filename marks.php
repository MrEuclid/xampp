<?php
// --- PHP Form Handler ---
// If the form was submitted, display the captured variables cleanly.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<div class='container-fluid mt-3'>";
    echo "<div class='alert alert-success'>";
    echo "<h3>Data Received from Form:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    echo "</div>";
    echo "</div>";
    
    // Uncomment the line below if you specifically need the massive phpinfo() output
    // phpinfo(INFO_VARIABLES);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>Get class,subject and test information</title>

    <style>
        /* 1. Hide the default radio buttons */
        .radio-group input[type="radio"] {
            display: none; 
        }

        /* 2. Style the labels to look like unselected chips */
        .radio-group label {
            display: inline-block;
            padding: 8px 16px;
            margin-right: 8px;
            margin-top: 8px;
            background-color: #f1f3f4; 
            color: #3c4043;            
            border: 2px solid transparent;
            border-radius: 20px;       
            font-family: system-ui, -apple-system, sans-serif;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease; 
            user-select: none;         
        }

        /* 3. Add a subtle hover effect */
        .radio-group label:hover {
            background-color: #e8eaed;
        }

        /* 4. Style the label when its hidden radio button is CHECKED */
        .radio-group input[type="radio"]:checked + label {
            background-color: #e8f0fe; 
            color: #1a73e8;            
            border: 2px solid #1a73e8; 
        }

        /* Make the section titles look cleaner */
        .radio-group strong {
            display: block;
            margin-bottom: 5px;
            font-family: system-ui, -apple-system, sans-serif;
            color: #202124;
        }
    </style>
</head>
<body>

<div class="container-fluid p-5 bg-primary text-white text-center">
    <h1>Get student marks</h1>
    <p>Use this page to select the subject,class and test information</p> 
</div>

<div class="container-fluid mt-4">
    <form id="dataForm" method="POST" action="marks.php"> 
        
        <div class="row mb-3">
            <div class="col-12 d-flex flex-wrap justify-content-center align-items-center gap-4">
                
                <div class="radio-group d-flex align-items-center mb-0">
                    <strong class="me-3">School:</strong>
                    <input type="radio" id="school_1" name="school" value="PIOHS" checked>
                    <label for="school_1" class="mb-0 mt-0">High School</label>
                    
                    <input type="radio" id="school_2" name="school" value="SMC">
                    <label for="school_2" class="mb-0 mt-0">Primary School</label>
                </div>

                <div class="radio-group d-flex align-items-center mb-0" id="year-container">
                    <strong class="me-3">School Year:</strong>
                </div>

            </div> 
        </div> 

        <div class="row mt-4 justify-content-center">
            <div class="col-md-3">
                <label for="subjectInput">Choose a Subject Code:</label><input type="text" id="subjectInput" list="subjectCodes" name="subjectCode" class="form-control" placeholder="Type to search...">
            </div>
            
            <div class="col-md-3">
                <label for="classInput">Choose a Class Code:</label>
                <input type="text" id="classInput" list="classCodes" name="classCode" class="form-control" placeholder="Type to search...">
            </div>
            
            <div class="col-md-3">
                <label for="testInput">Choose a Test Code:</label>
                <input type="text" id="testInput" list="testCodes" name="testCode" class="form-control" placeholder="Type to search...">
            </div>
            
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Send Data</button>
            </div>
        </div> 
    </form> 
    <!-- This is where the interactive grading table will be injected -->
<div id="grading-container" class="container-fluid mt-5 mb-5"></div>
</div> 
    
<datalist id="subjectCodes"></datalist>
<datalist id="classCodes"></datalist>
<datalist id="testCodes"></datalist>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- PART A: Generate the School Years ---
        const yearContainer = document.getElementById('year-container');
        const today = new Date();
        const currentMonth = today.getMonth(); // 0 = Jan, 8 = Sept
        const currentYear = today.getFullYear();
console.log(today);
        // If Sept or later, school year started this year. Otherwise, last year.
        const startYear = (currentMonth >= 8) ? currentYear : currentYear - 1;
        const currentSchoolYear = `${startYear}-${startYear + 1}`;
        const prevSchoolYear = `${startYear - 1}-${startYear}`;

        yearContainer.innerHTML += `
            <input type="radio" id="year_current" name="schoolYear" value="${currentSchoolYear}" checked>
            <label for="year_current" class="mb-0 mt-0">Current (${currentSchoolYear})</label>
            
            <input type="radio" id="year_prev" name="schoolYear" value="${prevSchoolYear}">
            <label for="year_prev" class="mb-0 mt-0">Previous (${prevSchoolYear})</label>
        `;

        // --- PART B: Fetch Data Logic ---
        function fetchDataLists() {
            const selectedSchool = document.querySelector('input[name="school"]:checked');
            const selectedYear = document.querySelector('input[name="schoolYear"]:checked');

            if (selectedSchool && selectedYear) {
                const params = { 
                    school: selectedSchool.value, 
                    year: selectedYear.value 
                };
                const queryString = new URLSearchParams(params).toString();
                
                // 1. Fetch Subjects
                fetch(`ajax/subjectList.php?${queryString}`)
                    .then(response => response.json())
                    .then(data => {
                        const subjectCodesList = document.getElementById('subjectCodes');
                        subjectCodesList.innerHTML = '';
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.code; 
                            subjectCodesList.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching subjects:', error));

                // 2. Fetch Classes
                fetch(`ajax/classList.php?${queryString}`)
                    .then(response => response.json())
                    .then(data => {
                        const classCodesList = document.getElementById('classCodes');
                        classCodesList.innerHTML = '';
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.code; 
                            classCodesList.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching classes:', error));

                // 3. Fetch Test Codes
                fetch(`ajax/testCode.php?${queryString}`)
                    .then(response => response.json())
                    .then(data => {
                        const testCodesList = document.getElementById('testCodes');
                        testCodesList.innerHTML = '';
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.code; 
                            testCodesList.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching tests:', error));
            }
        }

        // --- PART C: Attach Listeners & Run Initial Fetch ---
        const allRadioButtons = document.querySelectorAll('input[type="radio"]');
        allRadioButtons.forEach(radio => {
            radio.addEventListener('change', fetchDataLists);
        });
        
        // Trigger the fetch immediately to populate defaults on page load
        fetchDataLists();
        // --- PART D: Form Interception & Grid Generation ---
const dataForm = document.getElementById('dataForm');
const gradingContainer = document.getElementById('grading-container');

dataForm.addEventListener('submit', function(e) {
    // 1. Stop the standard page refresh
    e.preventDefault(); 

    // 2. Gather the current values from the form inputs
    const selectedSchool = document.querySelector('input[name="school"]:checked').value;
    const selectedYear = document.querySelector('input[name="schoolYear"]:checked').value;
    const classCode = document.getElementById('classInput').value;
    const subjectCode = document.getElementById('subjectInput').value;
    const testCode = document.getElementById('testInput').value;

    // Basic validation to ensure they picked everything
    if (!classCode || !subjectCode || !testCode) {
        alert("Please select a Subject, Class, and Test Code before proceeding.");
        return;
    }

    // 3. Build the query string for the endpoint
    const params = { 
        school: selectedSchool, 
        schoolYear: selectedYear, 
        classCode: classCode 
    };
    const queryString = new URLSearchParams(params).toString();

    // 4. Show a loading spinner while fetching
    gradingContainer.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Loading student roster...</p>
        </div>`;

    // 5. Fetch the students and build the UI
    fetch(`ajax/studentList.php?${queryString}`)
        .then(response => response.json())
        .then(data => {
            // Handle empty results safely
            if (data.length === 0 || data.error) {
                gradingContainer.innerHTML = `<div class="alert alert-warning">No students found for class ${classCode}.</div>`;
                return;
            }

            // Start building the HTML for the table
            let tableHTML = `
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Enter Marks</h4>
                        <span><strong>Class:</strong> ${classCode} | <strong>Subject:</strong> ${subjectCode} | <strong>Test:</strong> ${testCode}</span>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 15%;">ID</th>
                                    <th style="width: 50%;">Name</th>
                                    <th style="width: 35%;">Score</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            // Loop through each student and create a row
            data.forEach(student => {
                tableHTML += `
                    <tr>
                        <td class="align-middle">${student.studentID}</td>
                        <td class="align-middle">${student.name}</td>
                        <td>
                            <!-- We store the studentID in a data attribute to make saving easier -->
                            <input type="number" 
                                   class="form-control mark-input" 
                                   data-student-id="${student.studentID}" 
                                   min="0" 
                                   max="100" 
                                   step="0.1" 
                                   placeholder="Score">
                        </td>
                    </tr>
                `;
            });

            // Close the table and add the final Save button
            tableHTML += `
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-end p-3">
                        <button id="saveMarksBtn" class="btn btn-success px-5">Save All Marks</button>
                    </div>
                </div>
            `;

            // Inject the completed HTML into the DOM
            gradingContainer.innerHTML = tableHTML;
        })
        .catch(error => {
            console.error('Error fetching students:', error);
            gradingContainer.innerHTML = `<div class="alert alert-danger">Error loading student list. Check console.</div>`;
        });
});
    });
</script>

</body>
</html>