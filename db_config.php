<?php
$host = 'localhost';
$db_user = 'root'; // Your DB username
$db_pass = '';     // Your DB password
$db_name = 'pj_furniture_db';

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>