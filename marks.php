<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>Get marks</title>

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
    background-color: #f1f3f4; /* Light grey background */
    color: #3c4043;            /* Dark grey text */
    border: 2px solid transparent;
    border-radius: 20px;       /* Gives that rounded 'pill' shape */
    font-family: system-ui, -apple-system, sans-serif;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease; /* Smooth fade when clicked/hovered */
    user-select: none;         /* Prevents text from highlighting if double-clicked */
}

/* 3. Add a subtle hover effect */
.radio-group label:hover {
    background-color: #e8eaed;
}

/* 4. Style the label when its hidden radio button is CHECKED */
.radio-group input[type="radio"]:checked + label {
    background-color: #e8f0fe; /* Light blue background */
    color: #1a73e8;            /* Bold blue text */
    border: 2px solid #1a73e8; /* Blue outline */
}

/* Optional: Make the section titles look a bit cleaner */
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
  <p>Use this page to record student test results</p> 
</div> <!-- container -->

 <div class="container-fluid">
<form id="dataForm" method = "POST" action = "marks.php"> 
      <div class="row mb-3">
    <!-- d-flex puts the two groups side-by-side, gap-4 adds space between them -->
    <div class="col-12 d-flex flex-wrap justify-content-center align-items-center gap-4">
        
        <!-- flex on the group aligns the "School:" text perfectly with the chips -->
        <div class="radio-group d-flex align-items-center mb-0">
            <strong class="me-3">School:</strong>
            <input type="radio" id="school_1" name="school" value="school_1" checked>
            <label for="school_1" class="mb-0 mt-0">High School</label>
            
            <input type="radio" id="school_2" name="school" value="school_2">
            <label for="school_2" class="mb-0 mt-0">Primary School</label>
        </div>

        <div class="radio-group d-flex align-items-center mb-0" id="year-container">
            <strong class="me-3">School Year:</strong>
            <!-- Radio buttons will be injected here by JavaScript -->
        </div>

    </div> <!-- col -->
</div> <!-- row -->
    <div class = "row">
        <div class = "col-md-3">
        
    <label for="subjectInput">Choose a Subject Code:</label>
    <input type="text" id="subjectInput" list="subjectCode" name = "subjectCode" placeholder="Type to search...">
    
        </div>
        <div class = "col-md-3">
         <label for="classInput">Choose a Class Code:</label>
    <input type="text" id="classInput" list="classCode" name = "classCode" placeholder="Type to search...">

        </div>
        <div class = "col-md-3">
    <label for="testInput">Choose a Test Code:</label>
    <input type="text" id="testInput" list="testCode" name = "testCode" placeholder="Type to search...">
        </div>
 <div class = "col-md-3">
            <button type="submit">Send Data</button>
        </div>
</div>  <!-- row -->
 </form> 
</div> <!-- container -->
  
 
    
    <datalist id="subjectCodes">
        <!-- Options will be injected here by JavaScript -->
    </datalist>

      <datalist id="classCodes">
        <!-- Options will be injected here by JavaScript -->
    </datalist>

      <datalist id="testCodes">
        <!-- Options will be injected here by JavaScript -->
    </datalist>

    <script>
       
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- PART A: Generate the School Years ---
            const yearContainer = document.getElementById('year-container');
            const today = new Date();
            const currentMonth = today.getMonth(); // 0 = Jan, 8 = Sept
            const currentYear = today.getFullYear();

            const startYear = (currentMonth >= 8) ? currentYear : currentYear - 1;
            const currentSchoolYear = `${startYear}-${startYear + 1}`;
            const prevSchoolYear = `${startYear - 1}-${startYear}`;

            // Inject the calculated years as radio buttons
            // Note: We use the 'for' attribute in the label so clicking the text selects the radio button
            yearContainer.innerHTML += `
                <input type="radio" id="year_current" name="schoolYear" value="${currentSchoolYear}" checked>
                <label for="year_current">Current (${currentSchoolYear})</label>
                
                <input type="radio" id="year_prev" name="schoolYear" value="${prevSchoolYear}">
                <label for="year_prev">Previous (${prevSchoolYear})</label>
            `;
            });
            
        
        </script>


</body>
</html>