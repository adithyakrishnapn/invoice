<?php
// database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db";

// create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
