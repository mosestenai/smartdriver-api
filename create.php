<?php
require ("config.php");

$sql = "CREATE TABLE users (id SERIAL, username TEXT,email TEXT, password TEXT, phone TEXT,longitude TEXT, latitude TEXT)";
$result = $db->query($sql) or die($db->error) ;

if($result){
    echo "created successfully";
}

?>