<?php
require ("config.php");

$sql = "CREATE TABLE driver (id SERIAL, email TEXT,phone TEXT, licensecategory TEXT, years TEXT,location TEXT, price TEXT,
status TEXT,clientphone TEXT,clientcartype TEXT,longitude TEXT, latitude TEXT,licensepicurl TEXT,driverstatus TEXT)";
$result = $db->query($sql) or die($db->error) ;

if($result){
    echo "created successfully";
}

?>