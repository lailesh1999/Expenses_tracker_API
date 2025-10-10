<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include database and object files
include_once '../config/database.php';
include_once '../objects/expenses.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->getConnection();

// Initialize expense object
$expense = new Expense($db);

// If you want to filter by UserId (optional)
$userId = isset($_GET['UserId']) ? $_GET['UserId'] : null;

// Fetch data with JOIN to get category name
if ($userId) {
    $stmt = $expense->getUserExpenses($userId);
} else {
    // Modified query with JOIN to get category name
    $query = "SELECT e.*, c.CategoryName 
              FROM expenses e 
              LEFT JOIN categories c ON e.CategoryId = c.CategoryId 
              ORDER BY e.ExpenseDate DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
}

// Count results
$num = $stmt->rowCount();

if ($num > 0) {
    $expenses_arr = [];
    $expenses_arr["expensesList"] = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $expense_item = array(
            "ExpenseId"    => $ExpenseId,
            "CategoryId"   => $CategoryId,
            "CategoryName" => $CategoryName, // Add category name here
            "Title"        => $Title,
            "Amount"       => $Amount,
            "ExpenseDate"  => $ExpenseDate,
            "Notes"        => $Notes,
    
        );

        array_push($expenses_arr["expensesList"], $expense_item);
    }

    http_response_code(200);
    echo json_encode($expenses_arr);
} else {
    http_response_code(404);
    echo json_encode(["message" => "No expenses found."]);
}
?>