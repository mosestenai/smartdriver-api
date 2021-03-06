<?php
header('Access-Control-Allow-Origin: *');
header('Access-Content-Type: application/json');
header("Access-Control-Allow-Headers: Content-type, origin");
//header("Access-Control-Allow-Headers: Content-type");
require './../config.php';

$token = $_REQUEST['token'];
if (empty($token)) {
  //returning a an error response code if a request is made without a validation token 
  http_response_code($response_code = 403);
} else {

$json = file_get_contents('php://input');
$data = json_decode($json);

$hint = $data->hint;

if(empty($hint)){
    //fetching all records in the database drivers table
    $query = "SELECT * FROM driver WHERE status='OPEN' ";
    $result = $db->query($query);
    if ($result->rowCount() > 0) {
        if ($result = $db->query($query)) {
            $posts_arr = array();
            while ($user = $result->fetch(PDO::FETCH_OBJ)) {
                $post_item = array(
                        'phone' => $user->phone,
                        'licensecategory' => $user->licensecategory,
                        'email' => $user->email,
                        'location' => $user->location,
                        'licensepicurl' => $user->licensepicurl,
                        'price' => $user->price,
                        'years' => $user->years,
                        'username' => $user->username,
                        'rating' => $user->rating,
                        'trips' => $user->trips,
                        'profilepicurl' =>$user->profilepicurl,
                        'id' => $user->id,
                );

                array_push($posts_arr, $post_item);
            }
            echo json_encode($posts_arr);
        }
    } else {
        echo json_encode(array(
            'message' => "No driver records at the moment "
        ));
    }
}else{
    //fetching all records in the database drivers table
    $query = "SELECT * FROM driver WHERE status='OPEN' AND location LIKE '%$hint%'";
    $result = $db->query($query);
    if ($result->rowCount() > 0) {
        if ($result = $db->query($query)) {
            $posts_arr = array();
            while ($user = $result->fetch(PDO::FETCH_OBJ)) {
                $post_item = array(
                        'phone' => $user->phone,
                        'licensecategory' => $user->licensecategory,
                        'email' => $user->email,
                        'location' => $user->location,
                        'licensepicurl' => $user->licensepicurl,
                        'years' => $user->years,
                        'price' => $user->price,
                        'rating' => $user->rating,
                        'trips' => $user->trips,
                        'username' => $user->username,
                        'profilepicurl' =>$user->profilepicurl,
                        'id' => $user->id,
                );

                array_push($posts_arr, $post_item);
            }
            echo json_encode($posts_arr);
        }
    } else {
        echo json_encode(array(
            'message' => "No driver matching the location provided"
        ));
    }
}


}