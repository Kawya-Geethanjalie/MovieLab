<?php

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "movielab";

// $host = 'localhost';
// $dbname = 'movielab';
// $username = 'root';
// $password = '';

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>