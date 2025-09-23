<?php
// Required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database and user object files
include_once '../config/database.php';
include_once '../objects/category.php';

// Instantiate database and user objects
$database = new Database();
$db = $database->getConnection();
$category = new Category($db);

// Get posted data
$data = json_decode(file_get_contents("php://input"));

// Ensure data is not empty
if (!empty($data->UserId) && !empty($data->CategoryName) && !empty($data->CategoryType)) {
    // Set user property values
    $category->UserId = $data->UserId;
    $category->CategoryName = $data->CategoryName;
    $category->CategoryType = $data->CategoryType;
        // Create the user
        if(!($category->checkDuplicate())){
        if ($category->insertData()) {
            http_response_code(200); // Created
            echo json_encode(array("message" => "Inserted Sucessfully"));
        } else {
            http_response_code(200); // Service Unavailable
            echo json_encode(array("message" => "Error unable to insert data."));
        }
    }else{
        http_response_code(200); // Service Unavailable
            echo json_encode(array("message" => "Duplicate records found"));
    }
} else {
    // Set response code - 400 Bad Request
    http_response_code(400);
    echo json_encode(array("message" => "Unable to insert Data"));
}


?>