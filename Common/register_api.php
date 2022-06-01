<?php
header('Access-Control-Allow-Origin: *');
header('Access-Content-Type: application/json, text/plain, x-www-form-urlencoded; charset=UTF-8*/*');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-type, origin,  accept, x-requested-with");


require './../config.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  //Bad request error
  http_response_code($response_code = 200);
  echo json_encode(array(
    'message' => "invalid method"
  ));
} else {
  http_response_code($response_code = 200);
  $token = $_REQUEST['token'];
  if (empty($token)) {
    echo json_encode(array(
      'message' => "Unauthorized access"
    ));
  } else {
    $json = file_get_contents('php://input');
    $data = json_decode($json);


    $password_1 =  $data->password;
    $username = $data->username;
    $email = $data->email;
    $phone = $data->phone;
 ;


    if (empty($username) or empty($password_1) or empty($email)) {
      echo json_encode(array(
        'message' => "Fill all fields"
      ));
    }
     else {

      // first check the database to make sure 
      // a user does not already exist with the same username and/or email
      $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
      $result = $db->query($user_check_query);
      if ($result->rowCount() > 0) {
        $user = $result->fetch(PDO::FETCH_OBJ);


        if ($user) { // if user exists
          if ($user->username == $username) {
            echo json_encode(array(
              'message' => "username already exits"
            ));
          }else 
          if ($user->email == $email) {
            echo json_encode(array(
              'message' => "email already exits"
            ));
          }
        }
      } else {
        $password = password_hash($password_1, PASSWORD_DEFAULT, array('cost' => 9)); //encrypt the password before saving in the database
        $query = "INSERT INTO users (username,email,password,phone) VALUES('$username','$email','$password','$phone')";
        $db->query($query) or die($db->error);
        http_response_code($response_code = 200);
       
        $query2 = "SELECT * FROM users WHERE email='$email'";
        $result3 = $db->query($query2);
        $user3 = $result3->fetch(PDO::FETCH_OBJ);
        echo json_encode(
          array(
            'username' => $username,
            'email' => $email,
            'phone' => $phone,
            'id' => $user3->id,
          )
        );
      }
    }
  }
}
