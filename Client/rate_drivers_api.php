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


    $username =  $data->username;
    $rating = $data->rating;




    if (empty($username) or empty($rating)) {

        echo json_encode(array(
            'message' => "Fill all fields"
        ));
    } else {
        $query = "SELECT * FROM driver WHERE username='$username'  LIMIT 1";
        $results = $db->query($query);

        if ($results->rowCount() == 1) {
            $user = $results->fetch(PDO::FETCH_OBJ);
            $ratingfromdb = $user->rating;
            $previoustrip = $user->trips;
            $finaltrips = $previoustrip + 1;

            $finalrating = round($ratingfromdb + $rating);

            $update = "UPDATE driver SET rating='$finalrating',trips='$finaltrips' WHERE username='$username'";
            $result = $db->query($update);
            if ($result->rowCount() > 0) {
                echo json_encode(array(
                    'success' => "Driver rated sucessfuly"
                ));
            } else {
                echo json_encode(array(
                    'message' => "There was an internal error contact admin"
                ));
            }
        } else {
            echo json_encode(
                array('message' => 'Driver does not exist or the driver deleted the account')
            );
            exit();
        }
    }
}
