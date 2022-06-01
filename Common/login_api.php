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
  $password =  $data->password;
  $username =  $data->username;


  if (empty($username) or empty($password)) {
    //error response code if username and password field is empty
    echo json_encode(array(
      'message' => "Empty username or password"
    ));
  } else {
    $query = "SELECT * FROM users WHERE username='$username' or email='$username' LIMIT 1";
 
    $results = $db->query($query);
    //logging in the user if the credentials are found in the database
    if ($results->rowCount() == 1) {
      $user = $results->fetch(PDO::FETCH_OBJ);
      if (password_verify($password, $user->password) == 1) {
        $expires = date("U") + 1800;
        http_response_code($response_code = 200);
        echo json_encode(
          array(
            'username' => $user->username,
            'email' => $user->email,
            'phone' => $user->phone,
            'id' => $user->id,
          )
        );
        exit();
      } else {
        echo json_encode(
          array('message' => 'Incorrect password')
        );
        exit();
      }
    }
    //displaying an error message if there password of username wrongly entered 
    else {
      echo json_encode(
        array('message' => 'Invalid credentials')
      );
      exit();
    }
  }
}
