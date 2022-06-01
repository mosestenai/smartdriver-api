
<?php
header('Access-Control-Allow-Origin: *');
header('Access-Content-Type: application/json, text/plain, x-www-form-urlencoded; charset=UTF-8*/*');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-type, origin,  accept, x-requested-with");



require 'config.php';
http_response_code($response_code = 200);
$token = $_REQUEST['token'];
if (empty($token)) {
    http_response_code($response_code = 200);
    echo json_encode(array(
        'message' => "Unauthorized access"
    ));
} else {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $id = $_REQUEST['id'];
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $tempPath = $_FILES["file"]["tmp_name"];
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    //check if image is actual image or fake image 
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if ($check !== false) {
    } else {
        echo json_encode(array(
            'message' => "Could not not get image size"
        ));
        $uploadOk = 0;
        exit();
    }


    //check file size

    if ($_FILES["file"]["size"] > 700000) {
        echo json_encode(array(
            'message' => "The file is too big"
        ));
        exit();
        $uploadOk = 0;
    }

    //ALLOW CERTAIN FILE FORMATS
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo json_encode(array(
            'message' => "Invalid format"
        ));
        exit();
        $uploadOk = 0;
    }

    //check if $upload ok is set to 0 by an error
    if ($uploadOk == 0) {
        echo json_encode(array(
            'message' => "There was an error"
        ));
        exit();
        //if everything is ok
    } else {
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

        $url = "https://smartdriver.herokuapp.com/" . $target_file;

        $sql = "UPDATE driver SET profilepicurl='$url' WHERE id = $id";
        $result = $db->query($sql);
        if($result->rowCount() > 0){
            echo json_encode(array(
                'success' => "Upload successful"
            ));
        }else{
            echo json_encode(array(
                'message' => "There was an error uploading your license.Contact admin"
            ));
        }
    }
}
