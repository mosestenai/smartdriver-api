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

        // CREATE TABLE driver (id SERIAL, email TEXT,phone TEXT, licensecategory TEXT, years TEXT,location TEXT, price TEXT,
        // status TEXT,clientphone TEXT,clientcartype TEXT,longitude TEXT, latitude TEXT,licensepicurl TEXT,driverstatus TEXT)";


        $email =  $data->email;
        $phone = $data->phone;
        $licensecategory = $data->licensecategory;
        $years = $data->years;
        $location = $data->location;
        $price = $data->price;
        $status = 'OPEN';
        $username = $data->username;


        if (empty($email) or empty($years) or empty($price)) {
            echo json_encode(array(
                'message' => "Fill all fields"
            ));
        } else {

            // first check the database to make sure 
            // a user does not already exist with the same username and/or email
            $user_check_query = "SELECT * FROM driver WHERE  email='$email' LIMIT 1";
            $result = $db->query($user_check_query);
            if ($result->rowCount() > 0) {

                echo json_encode(array(
                    'message' => "driver already exits"
                ));
            } else {
                $query = "INSERT INTO driver (email,phone,licensecategory,years,location,price,status,username,trips) 
        VALUES('$email','$phone','$licensecategory','$years','$location','$price','$status','$username','0')";
                $db->query($query) or die($db->error);
                http_response_code($response_code = 200);

                $query2 = "SELECT * FROM driver WHERE email='$email'";
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
