<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
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

// Check if categoryId is provided
if (!empty($data->CategoryId)) {
    $categoryId = $data->CategoryId;
    
    // Try to delete the category
    if ($category->deleteCategory($categoryId)) {
        http_response_code(200);
        echo json_encode(array("message" => "Category was deleted successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete category."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Missing CategoryId parameter."));
}
?>