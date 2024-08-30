<?php
$host = 'localhost';
$username = 'root';
$password = ''; // If no password set for the root user
$database = 'crudoperations';

$con = new mysqli($host, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>

