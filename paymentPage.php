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
      background-color: #f8f9fa;
      margin: 0;
    }
    .form-container {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 420px;
    }
    .form-container h2 {
      font-size: 1.875rem;
      margin-bottom: 1.5rem;
      text-align: center;
      font-weight: 600;
      color: #333;
    }
    .form-label {
      font-size: 1rem;
      font-weight: 500;
      color: #555;
    }
    .form-control {
      height: calc(1.5em + 0.75rem + 2px);
      padding: 0.375rem 0.75rem;
      font-size: 1rem;
      line-height: 1.5;
      color: #495057;
      background-color: #fff;
      background-clip: padding-box;
      border: 1px solid #ced4da;
      border-radius: 0.375rem;
      box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .form-control:focus {
      color: #495057;
      background-color: #fff;
      border-color: #80bdff;
      outline: 0;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .btn-primary {
      background-color: #007bff;
      border: none;
      padding: 0.75rem;
      font-size: 1rem;
      font-weight: 500;
      border-radius: 0.375rem;
      transition: background-color 0.15s ease-in-out;
      width: 100%;
      margin-top: 1rem;
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
          <h2 class="text-center mb-4">Enter Amount</h2>
          <form id="addPayment" method="post">
            <div class="mb-3">
              <label for="integerInput" class="form-label">Amount</label>
              <input type="number" class="form-control" id="integerInput" name="amount" placeholder="Enter your Amount" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
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

      
      
      fetch('controller.php/addPayment', {
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

