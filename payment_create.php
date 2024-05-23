<?php
include "db_connection.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $stmt = $conn->prepare("SELECT * FROM `payment_crud` WHERE `username` = ? AND `password` = ?");
    $stmt->bind_param("ss", $username, $password);

   
    $stmt->execute();
    $result = $stmt->get_result();

    
    if ($result->num_rows > 0) {

        $_SESSION['username'] = $username;
        
        header("Location: addPayment.php?username=" . urlencode($username));
        exit();
    } else {
        echo "Invalid username or password.";
    }

    
    $stmt->close();
}


$conn->close();
?>

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
      background-color: #f8f9fa;
    }
    .login-form {
      width: 100%;
      max-width: 400px;
      padding: 15px;
      margin: 0 auto;
    }
  </style>
</head>
<body>
  <div class="login-form">
    <h2 class="text-center mb-4">Login</h2>
    <form id="loginForm" method="post">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" class="form-control" id="username" placeholder="Enter Username" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
    <button type="button" class="btn btn-primary w-100 mt-3" onclick="window.location.href='/register.php'">Register</button>
  </div>


  <script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
      event.preventDefault(); 

      
      const formData = new FormData(this);

      
      fetch('payment_crud/login.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json()) 
      .then(data => {
       
        if (data.success) {
          
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
