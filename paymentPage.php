<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Integer Input Form</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      background-color: #f0f2f5;
    }
    .form-container {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .form-container h2 {
      font-size: 1.75rem;
      margin-bottom: 1.5rem;
    }
    .btn-primary {
      background-color: #007bff;
      border: none;
    }
    .btn-primary:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="form-container">
          <h2 class="text-center mb-4">Enter  Amount</h2>
          <form id = "addPayment" method="post">
            <div class="mb-3">
              <label for="integerInput" class="form-label">Amount</label>
              <input type="number" class="form-control" id="integerInput" name="amount" placeholder="Enter an integer" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('addPayment').addEventListener('submit', function(event) {
      event.preventDefault(); 

      
      const formData = new FormData(this);
      console.log(formData)

      
      
      fetch('loginhandle.php/addPayment', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json()) 
      .then(data => {
       
        if (data.success) {

            
          
          window.location.href = 'list.php';
        } else {
          
          alert(data.message);
        }
      })
      .catch(error => console.error('Error:', error));
     });
  </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

