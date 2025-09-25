<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database and object files
include_once '../config/database.php';
include_once '../objects/category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->getConnection();

// Initialize category object
$category = new category($db);

// Get raw data
$data = json_decode(file_get_contents("php://input"));

// Check if required fields are provided
if (!empty($data->CategoryId) && !empty($data->CategoryName) && !empty($data->CategoryType)) {
    
    // Assign values to category object
    $category->CategoryId = $data->CategoryId;
    $category->CategoryName = $data->CategoryName;
    $category->CategoryType = $data->CategoryType;

    // Try to update the category
    if ($category->updateCategory()) {
        http_response_code(200);
        echo json_encode(["message" => "Category was updated successfully."]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Unable to update category."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing required fields (CategoryId, CategoryName, CategoryType)."]);
}
?>
