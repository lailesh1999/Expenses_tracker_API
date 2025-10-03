<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
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

// Check if ExpenseId is provided
if (!empty($data->ExpenseId)) {
    $expenseId = $data->ExpenseId;

    // Try to delete
    if ($expense->deleteExpense($expenseId)) {
        http_response_code(200);
        echo json_encode(["message" => "Expense deleted successfully."]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Unable to delete expense."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing required field: ExpenseId."]);
}
?>
