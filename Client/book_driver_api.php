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

  //$username = $_REQUEST['username'];
  $cartype =  $data->cartype;
  $phone =  $data->phone;
  $driverid = $data->driverid;
  $startpoint = $data->startpoint;
  $destination = $data->destination;



  if (empty($cartype) or empty($phone)) {
    //error response code if cartype and phone field is empty
    echo json_encode(array(
      'message' => "Fill all fields"
    ));
  } else {
    $query = "SELECT * FROM driver WHERE id='$driverid'  LIMIT 1";
    $results = $db->query($query);
   

    if ($results->rowCount() == 1) {
      $user = $results->fetch(PDO::FETCH_OBJ);
     
      if(($user->status) === 'BOOKED'){
        echo json_encode(
            array('message' => 'Driver is already booked')
          );
          exit(); 
      }else{
          $book = "UPDATE driver SET clientphone='$phone', clientcartype ='$cartype',
          clientdestination='$destination',clientstartpoint='$startpoint',status='BOOKED' WHERE id ='$driverid'";
          $result = $db->query($book);
          if($result){
            echo json_encode(
                array(
                    'success' => 'Driver booked. Await confirmation',
                    'driverphone' => $user->phone,
                    )
              );
              exit();
          }
      }
    }
    //displaying an error message if there is no record in the database
    else {
      echo json_encode(
        array('message' => 'Invalid driver id')
      );
      exit();
    }
  }
}
