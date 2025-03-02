<?php

$servername = "localhost";
$username = "root";
$password = ""; // If you set a password in phpMyAdmin, enter it here
$dbname = "gravity";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

?>