<?php
// Required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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
if (!empty($data->username) && !empty($data->password) && !empty($data->email)) {
    // Set user property values
    $user->username = $data->username;
    $user->password = $data->password;
    $user->email = $data->email;

     if ($user->usernameExists()) {
        http_response_code(409); // Conflict
        echo json_encode(array("message" => "Username is already present."));
    } else {
        // Create the user
        if ($user->create()) {
            http_response_code(201); // Created
            echo json_encode(array("message" => "User was created."));
        } else {
            http_response_code(503); // Service Unavailable
            echo json_encode(array("message" => "Unable to create user."));
        }
    }
} else {
    // Set response code - 400 Bad Request
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
}
?>