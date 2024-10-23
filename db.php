<?php

$hostname = "localhost";
$dbUser = "root";
$dbPass = "therealLGTVQ+1";
$dbName = "Website_Users";

$conn = mysqli_connect($hostname, $dbUser, $dbPass, $dbName);
	
if(!$conn){
	die("Something went wrong");
}
	
?>
