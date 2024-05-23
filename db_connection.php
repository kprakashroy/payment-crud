<?php

$serveranme = "localhost";
$username = "root";
$password = "";
$dbname = "payment";

$conn = mysqli_connect($serveranme,$username,$password,$dbname);


if(!$conn){
    die("connection failed");
}

// echo "conected successfully";

?>