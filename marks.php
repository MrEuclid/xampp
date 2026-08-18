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
<form id="dataForm"> 
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
            
            fetch('ajax/hsList.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const dataList = document.getElementById('subject-codes');
                    
                    // Loop through the array of objects
                    data.forEach(item => {
                        const option = document.createElement('option');
                        
                        // Grab the 'code' property from your JSON object
                        option.value = item.code; 
                        
                        dataList.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('There was a problem loading the subject codes:', error);
                });
                
        });
    </script>

</body>
</html>