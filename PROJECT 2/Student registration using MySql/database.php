<?php
$dbhost = 'localhost';
$dbusername = 'root';
$dbpassword = '';
$dbname = 'student_management_system';

$conn = new mysqli($dbhost, $dbusername, $dbpassword, $dbname);

if ($conn->connect_errno) {
    die('Failed to connect to MySQL: ' . $conn->connect_error);
}
?>