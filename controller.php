<?php

class LoginHandler {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function handleLogin() {
       
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        
        $stmt = $this->conn->prepare("SELECT * FROM `payment_crud` WHERE `username` = ? AND `password` = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        
        if ($result->num_rows > 0) {
            
            session_start();
            $_SESSION['username'] = $username;
            echo json_encode(["success" => true, "username" => $username]);
        } else {
            
            http_response_code(401); 
            echo json_encode(["success" => false, "message" => "Invalid username or password"]);
        }

        
        $stmt->close();
        $this->conn->close();
    }

    public function handleAddPayment() {

        

        session_start();
    
            $amount = $_POST['amount'];
            $username = $_SESSION['username'];
        
            
            $stmt = $this->conn->prepare("INSERT INTO `payment_added` (`amount`, `date`, `username`) VALUES ( ?, CURRENT_TIMESTAMP(), ?)");
            $stmt->bind_param("ss", $amount, $username);
        
           
            
        
            
            if ($stmt->execute()) {
        
                
                echo json_encode(["success" => true]);
                // header("Location: list.php?username=" . urlencode($username));
                exit();
            } else {
                http_response_code(401); 
            echo json_encode(["success" => false, "message" => "Payment is not successfull"]);
            }
        
            
            $stmt->close();
        }
    
}


include "db_connection.php";


$loginHandler = new LoginHandler($conn);


function route($url, $callback) {
    $path = $_SERVER['REQUEST_URI'];
    if ($path === $url) {
        $callback();
    }
}


function handleLogin() {
    //echo json_encode(["success" => true, "username" => "krishna"]);
    global $loginHandler;
    $loginHandler->handleLogin();
}

function handleAddPayment() {
    //echo json_encode(["success" => true, "username" => "krishna"]);
    global $loginHandler;
    $loginHandler->handleAddPayment();
}


route('/payment_crud/controller.php/login', 'handleLogin');

route('/payment_crud/controller.php/addPayment', 'handleAddPayment');



?>
