<?php
header('Access-Control-Allow-Origin: *');
header('Access-Content-Type: application/json, text/plain, x-www-form-urlencoded; charset=UTF-8*/*');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-type, origin,  accept, x-requested-with");
require 'config.php';

$json = file_get_contents('php://input');
$data = json_decode($json);


$password_1 =  $data->password;
$email = $data->email;
$currentpassword = $data->currentpassword;


if (empty($password_1) or empty($email) or empty($currentpassword)) {
  echo json_encode(array(
    'message' => "Unauthorised access"
  ));
} else {
  $query = "SELECT * FROM users WHERE  email='$email' LIMIT 1";
  $results = $db->query($query);
  if ($results->rowCount() == 1) {
    $user = $results->fetch(PDO::FETCH_OBJ);
    if (password_verify($currentpassword, $user->password) == 1) {

      $password = password_hash($password_1, PASSWORD_DEFAULT, array('cost' => 9)); //encrypt the password before saving in the database

      $sql = "UPDATE users SET password= '$password' WHERE email='$email'";
      $result =  $db->query($sql);

      if ($result) {
        echo json_encode(array(
          'success' => "Password updated successfully"
        ));
      } else {
        echo json_encode(array(
          'message' => "There was an internal error contact admin"
        ));
      }
    } else {
      echo json_encode(array(
        'message' => "Current password provided is incorrect.try again"
      ));
    }
  }else{
    echo json_encode(array(
      'message' => "Invalid email address"
    ));
  }
}
