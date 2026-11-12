<?php

// Include database configuration
require "config.php";

// Create database connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {

    die("Connection Failed: " . mysqli_connect_error());

}

?>