<?php
// --- PHP Form Handler ---
/*
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<div class='container-fluid mt-3'>";
    echo "<div class='alert alert-success'>";
    echo "<h3>Data Received from Form:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    echo "</div>";
    echo "</div>";
}
    */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>Get class, subject and test information</title>

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
    <p>Use this page to select the subject, class and test information</p> 
</div>

<div class="container-fluid mt-4">
    <form id="dataForm" > 
        
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
                <label for="subjectInput">Choose a Subject Code:</label>
                <input type="text" id="subjectInput" list="subjectCodes" name="subjectCode" class="form-control" placeholder="Type to search...">
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
    // Helper function to validate input with soft red highlighting and save-blocking
    function validateScore(input, maxScore) {
        const val = input.value.trim();

        // If blank, it's valid (blank marks won't be saved)
        if (val === "") {
            input.classList.remove('is-invalid', 'bg-danger-subtle');
            checkFormValidity();
            return;
        }

        // Check if it contains decimals or isn't a strict whole number
        const num = Number(val);
        const isWholeNumber = /^\d+$/.test(val);

        if (!isWholeNumber || num < 0 || num > maxScore) {
            // Invalid: highlight red
            input.classList.add('is-invalid', 'bg-danger-subtle');
        } else {
            // Valid: remove highlighting
            input.classList.remove('is-invalid', 'bg-danger-subtle');
        }

        checkFormValidity();
    }

    // Function to disable/enable the save button if any errors exist on the page
    function checkFormValidity() {
        const saveBtn = document.getElementById('saveMarksBtn');
        if (!saveBtn) return;

        const invalidInputs = document.querySelectorAll('.mark-input.is-invalid');
        if (invalidInputs.length > 0) {
            saveBtn.disabled = true;
            saveBtn.title = "Please fix highlighted errors before saving.";
        } else {
            saveBtn.disabled = false;
            saveBtn.title = "";
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        
        // --- PART A: Generate the School Years ---
        const yearContainer = document.getElementById('year-container');
        const today = new Date();
        const currentMonth = today.getMonth(); 
        const currentYear = today.getFullYear();

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
        
        fetchDataLists();

        // --- PART D: Form Interception & Grid Generation with Maxima & Preload Support ---
        const dataForm = document.getElementById('dataForm');
        const gradingContainer = document.getElementById('grading-container');

        dataForm.addEventListener('submit', function(e) {
            e.preventDefault(); 

            const selectedSchool = document.querySelector('input[name="school"]:checked').value;
            const selectedYear = document.querySelector('input[name="schoolYear"]:checked').value;
            const classCode = document.getElementById('classInput').value;
            const subjectCode = document.getElementById('subjectInput').value;
            const testCode = document.getElementById('testInput').value;

            if (!classCode || !subjectCode || !testCode) {
                alert("Please select a Subject, Class, and Test Code before proceeding.");
                return;
            }

            // We pass subjectCode and testCode so studentList.php can pull existing pre-loaded marks
            const params = { 
                school: selectedSchool, 
                schoolYear: selectedYear, 
                classCode: classCode,
                subjectCode: subjectCode,
                testCode: testCode
            };
            const queryString = new URLSearchParams(params).toString();

            gradingContainer.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Loading student roster and existing marks...</p>
                </div>`;

            // Fetch Maxima and Student List (with scores) in parallel
            Promise.all([
                fetch(`ajax/getMax.php?subjectCode=${subjectCode}&classCode=${classCode}`).then(res => res.json()),
                fetch(`ajax/studentList.php?${queryString}`).then(res => res.json())
            ])
            .then(([maxData, studentData]) => {
                const maxScore = maxData.max ?? 100;

                if (studentData.length === 0 || studentData.error) {
                    gradingContainer.innerHTML = `<div class="alert alert-warning">No students found for class ${classCode}.</div>`;
                    return;
                }

                let tableHTML = `
                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Enter / Edit Marks</h4>
                            <span><strong>Class:</strong> ${classCode} | <strong>Subject:</strong> ${subjectCode} | <strong>Test:</strong> ${testCode} | <strong>Max Score:</strong> ${maxScore}</span>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 15%;">ID</th>
                                        <th style="width: 50%;">Name</th>
                                        <th style="width: 35%;">Score (Max: ${maxScore})</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;

                studentData.forEach(student => {
                    // Pre-load existing score if available in database
                    const existingScore = (student.score !== null && student.score !== undefined) ? student.score : '';

                    tableHTML += `
                        <tr>
                            <td class="align-middle">${student.studentID}</td>
                            <td class="align-middle">${student.name}</td>
                            <td>
                                <input type="number" 
                                       class="form-control mark-input" 
                                       data-student-id="${student.studentID}" 
                                       step="any" 
                                       value="${existingScore}"
                                       oninput="validateScore(this, ${maxScore})"
                                       placeholder="Blank = skip">
                            </td>
                        </tr>
                    `;
                });

                tableHTML += `
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-end p-3">
                            <button id="saveMarksBtn" class="btn btn-success px-5">Save All Marks</button>
                        </div>
                    </div>
                `;

                gradingContainer.innerHTML = tableHTML;
            })
            .catch(error => {
                console.error('Error loading grading grid:', error);
                gradingContainer.innerHTML = `<div class="alert alert-danger">Error loading data. Check console.</div>`;
            });
        });

        // --- PART E: Collect and Save Marks ---
        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'saveMarksBtn') {
                const saveBtn = e.target;
                
                const selectedSchool = document.querySelector('input[name="school"]:checked').value;
                const selectedYear = document.querySelector('input[name="schoolYear"]:checked').value;
                const classCode = document.getElementById('classInput').value;
                const subjectCode = document.getElementById('subjectInput').value;
                const testCode = document.getElementById('testInput').value;

                const marksData = [];
                const scoreInputs = document.querySelectorAll('.mark-input');
                
                scoreInputs.forEach(input => {
                    const score = input.value.trim();
                    if (score !== "") {
                        marksData.push({
                            studentID: input.getAttribute('data-student-id'),
                            score: parseInt(score, 10)
                        });
                    }
                });

                if (marksData.length === 0) {
                    alert("No marks have been entered to save.");
                    return;
                }

                const payload = {
                    school: selectedSchool,
                    year: selectedYear,
                    classCode: classCode,
                    subjectCode: subjectCode,
                    testCode: testCode,
                    marks: marksData
                };

                saveBtn.disabled = true;
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';

                fetch('ajax/saveMarks.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Success! ${data.savedCount} marks were saved/updated.`);
                        saveBtn.disabled = false;
                        saveBtn.textContent = 'Save All Marks';
                    } else {
                        alert("Error saving marks: " + (data.error || "Unknown error"));
                        saveBtn.disabled = false;
                        saveBtn.textContent = 'Save All Marks';
                    }
                })
                .catch(error => {
                    console.error('Error saving:', error);
                    alert("A network error occurred while saving.");
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'Save All Marks';
                });
            }
        });

    });
</script>

</body>
</html>