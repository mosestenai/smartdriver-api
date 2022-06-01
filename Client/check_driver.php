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


  $driverphone =  $data->driverphone;
  $clientphone = $data->clientphone;
 



  if (empty($driverphone) or empty($clientphone)) {
   
    echo json_encode(array(
      'message' => "Fill all fields"
    ));
  } else {
    $query = "SELECT * FROM driver WHERE phone='$driverphone'  LIMIT 1";
    $results = $db->query($query);
   
    if ($results->rowCount() == 1) {
      $user = $results->fetch(PDO::FETCH_OBJ);

      //check client status
      if(($user->clientphone) != $clientphone){
        echo json_encode(
            array('message' => 'Your request was rejected by the driver or you did not make any request')
          );
          exit(); 
      }else{
          if(($user->driverstatus) != 'Approved'){
            echo json_encode(
                array('message' => 'Your request has not yet been accepted by the driver')
              );
              exit(); 
          }else{
            echo json_encode(
                array(
                    'success' => 'Your request has been accepted by the driver',
                    'phone' =>$user->phone,
                    )
              );
              exit(); 
          }
      }
     
      
    }
    else {
      echo json_encode(
        array('message' => 'Driver does not exist or the driver deleted the account')
      );
      exit();
    }
  }
}
