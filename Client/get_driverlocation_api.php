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


    $driverusername =  $data->driverusername;
    $clientphone = $data->clientphone;





    if (empty($driverusername)) {

        echo json_encode(array(
            'message' => "provide driver phone number to monitor location"
        ));
    } else {
        $query = "SELECT * FROM driver WHERE username='$driverusername'  LIMIT 1";
        $results = $db->query($query);

        if ($results->rowCount() == 1) {
            $user = $results->fetch(PDO::FETCH_OBJ);

            //check client status
            if (($user->clientphone) != $clientphone) {
                echo json_encode(
                    array('message' => 'You dont have permission to monitor this driver')
                );
                exit();
            } else {
                echo json_encode(
                    array(
                        'latitude' => $user->latitude,
                        'longitude' => $user->longitude,
                    )
                );
            }
        } else {
            echo json_encode(
                array('message' => 'Driver does not exist or the driver deleted the account')
            );
            exit();
        }
    }
}
