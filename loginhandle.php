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
    global $loginHandler;
    $loginHandler->handleLogin();
}


route('/payment_crud/login.php', 'handleLogin');

?>
