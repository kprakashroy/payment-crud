<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      background-color: #e9ecef;
    }
    .login-form {
      width: 100%;
      max-width: 400px;
      padding: 30px;
      margin: 0 auto;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .login-form h2 {
      font-size: 1.75rem;
      margin-bottom: 1.5rem;
    }
    .form-control {
      margin-bottom: 20px;
    }
    .btn-login {
      width: 48%;
      background-color: #007bff;
      border: none;
    }
    .btn-login:hover {
      background-color: #0056b3;
    }
    .btn-register {
      width: 48%;
      background-color: #6c757d;
      border: none;
    }
    .btn-register:hover {
      background-color: #5a6268;
    }
    .button-group {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="login-form">
    <h2 class="text-center mb-4">Login</h2>
    <form id = "loginForm"  method="post">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" class="form-control" id="username" placeholder="Enter Username" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password" required>
      </div>
      <div class="button-group">
        <button type="submit" class="btn btn-login">Login</button>
        <button type="button" class="btn btn-register" onclick="window.location.href='/register.php'">Register</button>
      </div>
    </form>
  </div>




  <script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
      event.preventDefault(); 

      
      const formData = new FormData(this);
      console.log(formData)

      const username = document.getElementById('username').value;
      console.log(username,'----')
      
      fetch('loginhandle.php/login', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json()) 
      .then(data => {
       
        if (data.success) {

            console.log(data.username);
          
          window.location.href = 'addPayment.php?username=' + encodeURIComponent(data.username);
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
