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


    $phone =  $data->phone;




    if (empty($phone)) {

        echo json_encode(array(
            'message' => "Fill all fields"
        ));
    } else {
        $query = "SELECT * FROM driver WHERE phone='$phone' AND clientphone IS NOT NULL LIMIT 1";
        $results = $db->query($query);

        if ($results->rowCount() == 1) {
            $user = $results->fetch(PDO::FETCH_OBJ);

            if (($user->status) != 'BOOKED') {
                echo json_encode(
                    array('message' => 'No requests at the moment')
                );
                exit();
            } else {
                echo json_encode(
                    array(
                        'phone' => $user->clientphone,
                        'cartype' => $user->clientcartype,
                        'startpoint' => $user->clientstartpoint,
                        'destination' => $user->clientdestination
                    )
                );
            }
        } else {
            echo json_encode(
                array('message' => 'You are not a registered driver or no requests have been made')
            );
            exit();
        }
    }
}
