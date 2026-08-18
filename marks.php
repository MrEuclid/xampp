<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>Get marks</title>
</head>
<body>

  <div class="container-fluid p-5 bg-primary text-white text-center">
  <h1>Get student marks</h1>
  <p>Use this page to record student test results</p> 
</div>

 <div class="container-fluid">
<form id="dataForm"> 
    <div class = "row">
        <div class = "col-md-3">
            <label for="subjectCode">Subject Code:</label>
            <input type="text" name="subjectCode" required> 
        </div>
        <div class = "col-md-3">
            <label for="classCode">Class Code:</label>
            <input type="text" name="classCode" required> 
        </div>
        <div class = "col-md-3">
            <label for="testCode">Test Code:</label><input type="text" name="testCode" required> 
        </div>
 <div class = "col-md-3">
            <button type="submit">Send Data</button>
        </div>

 </form> 

</div>   
    <label for="subject-input">Choose a Subject Code:</label>
    <input type="text" id="subject-input" list="subject-codes" placeholder="Type to search...">
    
    <datalist id="subject-codes">
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