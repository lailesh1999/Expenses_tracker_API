<?php
// Include database and user object files
include_once '../config/database.php';
include_once '../objects/user.php';

// Instantiate database and user objects
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Get posted data
$data = json_decode(file_get_contents("php://input"));


// Ensure data is not empty
if (!empty($data->username) && !empty($data->password)) {
    // Set user property values
    $user->username = $data->username;
    $user->password = $data->password;

     if ($user->login()) {
        http_response_code(200); 
        echo json_encode(array("message" => "login sucessfully ",
                                "userid" =>  $user->id,
                                "username" => $user->username
                                ));
    } else {
            http_response_code(200); 
            echo json_encode(array("message" => "Invalid username and password"));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to login . Data is incomplete."));
}



?>