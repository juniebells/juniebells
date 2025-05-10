<?php 

$sname = "localhost";
$uname = "root";
$password = "";
$database = "login";

$conn = mysqli_connect($sname,$uname, $password, $database );

if (!$conn) {
	echo "Connection Failed";} 

	




