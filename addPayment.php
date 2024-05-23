<?php
include "db_connection.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $amount = $_POST['amount'];
    $username = $_SESSION['username'];

    
    $stmt = $conn->prepare("INSERT INTO `payment_added` (`amount`, `date`, `username`) VALUES ( ?, CURRENT_TIMESTAMP(), ?)");
    $stmt->bind_param("ss", $amount, $username);

   
    

    
    if ($stmt->execute()) {

        
        
        header("Location: list.php?username=" . urlencode($username));
        exit();
    } else {
        echo "Amount not added.";
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
  <title>Integer Input Form</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <h2 class="text-center mt-5 mb-3">Enter an Integer</h2>
        <form action = "" method = "post">
          <div class="mb-3">
            <label for="integerInput" class="form-label">Integer</label>
            <input type="number" class="form-control" id="integerInput" name="amount" placeholder="Enter an integer" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
