<?php
header('Access-Control-Allow-Origin: *');
header('Access-Content-Type: application/json, text/plain, x-www-form-urlencoded; charset=UTF-8*/*');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-type, origin,  accept, x-requested-with");
require './../config.php';

$token = $_REQUEST['token'];
if (empty($token)) {
    //returning a an error response code if a request is made without a validation token 
    http_response_code($response_code = 403);
} else {
    $json = file_get_contents('php://input');
    $data = json_decode($json);


   
    $phone = $data->phone;


    if (empty($phone)) {
        echo json_encode(array(
            'message' => "Empty phone number"
        ));
    } else {
        $query = "UPDATE driver SET driverstatus='',status='OPEN',clientphone='' WHERE phone = '$phone'";
        $results = $db->query($query);

        if($results){
            echo json_encode(
                array(
                    'success' => 'Client request canceled successfully.Await other requests',
                    )
            );
            exit();
        }

    }
}
