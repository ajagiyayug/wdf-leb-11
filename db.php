<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "student_db";

// MySQLi connection setup
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>