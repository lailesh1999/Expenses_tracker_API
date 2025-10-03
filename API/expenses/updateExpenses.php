<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database and object files
include_once '../config/database.php';
include_once '../objects/expenses.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->getConnection();

// Initialize expense object
$expense = new Expense($db);

// Get raw data
$data = json_decode(file_get_contents("php://input"));

// Check if required fields are provided
if (!empty($data->ExpenseId) && !empty($data->UserId) && !empty($data->CategoryId) 
    && !empty($data->Title) && !empty($data->Amount) && !empty($data->ExpenseDate)) {
    
    // Assign values to expense object
    $expense->ExpenseId = $data->ExpenseId;
    $expense->UserId = $data->UserId;
    $expense->CategoryId = $data->CategoryId;
    $expense->Title = $data->Title;
    $expense->Amount = $data->Amount;
    $expense->ExpenseDate = $data->ExpenseDate;
    $expense->Notes = isset($data->Notes) ? $data->Notes : null;

    // Try to update the expense
    if ($expense->updateExpense()) {
        http_response_code(200);
        echo json_encode(["message" => "Expense was updated successfully."]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Unable to update expense."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing required fields (ExpenseId, UserId, CategoryId, Title, Amount, ExpenseDate)."]);
}
?>
