<?php
$host = 'localhost';
$db = 'student_management_db';
$user = 'root';
$password = '1234';

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
