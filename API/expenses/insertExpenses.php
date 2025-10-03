<?php
// Required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database and expense object files
include_once '../config/database.php';
include_once '../objects/expenses.php';

// Instantiate database and expense object
$database = new Database();
$db = $database->getConnection();
$expense = new Expense($db);

// Get posted data
$data = json_decode(file_get_contents("php://input"));

// Ensure required data is not empty
if (!empty($data->UserId) && !empty($data->CategoryId) && !empty($data->Title) 
    && !empty($data->Amount) && !empty($data->ExpenseDate)) {

    // Set expense property values
    $expense->UserId = $data->UserId;
    $expense->CategoryId = $data->CategoryId;
    $expense->Title = $data->Title;
    $expense->Amount = $data->Amount;
    $expense->ExpenseDate = $data->ExpenseDate;
    $expense->Notes = isset($data->Notes) ? $data->Notes : null;

    // Insert expense
    if ($expense->insertData()) {
        http_response_code(200); 
        echo json_encode(array("message" => "Expense inserted successfully."));
    } else {
        http_response_code(500); 
        echo json_encode(array("message" => "Error: Unable to insert expense."));
    }
} else {
    // Bad request
    http_response_code(400);
    echo json_encode(array("message" => "Unable to insert expense. Required fields are missing."));
}
?>
