<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
session_start();
$servername = "localhost";

$username = "root"; 

$password = "root"; 
$db = 'shivam_db';


$con= mysqli_connect($servername,$username,$password,$db);

?> 