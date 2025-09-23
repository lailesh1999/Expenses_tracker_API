<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include database and object files
include_once '../config/database.php';
include_once '../objects/category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->getConnection();

// Initialize category object
$category = new Category($db);

// Get userId from query parameter
$userId = isset($_GET['userId']) ? intval($_GET['userId']) : 0;

if ($userId > 0) {
    $stmt = $category->getAllCategoriesByUser($userId);
    $num = $stmt->rowCount();

    if ($num > 0) {
        $categories_arr = array();
        $categories_arr["categories"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $category_item = array(
                "CategoryId"   => $CategoryId,   // make sure matches your DB
                "UserId"       => $UserId,
                "CategoryName" => $CategoryName,
                "CategoryType" => $CategoryType
            );

            array_push($categories_arr["categories"], $category_item);
        }

        http_response_code(200);
        echo json_encode($categories_arr);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "No categories found for this user."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Missing or invalid userId parameter."));
}
?>
