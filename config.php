<?php error_reporting (E_ALL ^ E_NOTICE);?><?php

session_start();
// initializing variables
$username = "";
$email    = "";
$errors = array(); 
$minpassword = 7;
$host=$_ENV["DB_HOST"] ;
$user=$_ENV["DB_USER"];
$password=$_ENV["DB_PASSWORD"];
$dbname=$_ENV["DB_NAME"];
$port=$_ENV["DB_PORT"];

try{
$db = new PDO("pgsql:host=$host;dbname=$dbname;port=$port",$user,$password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $error)
{
$error->getMessage();
}

?>