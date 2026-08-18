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
    });
</script>

</body>
</html>