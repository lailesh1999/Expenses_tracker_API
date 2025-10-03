<?php
class Expense {

    private $conn;
    private $table_name = "expenses";

    public $ExpenseId;
    public $UserId;
    public $CategoryId;
    public $Title;
    public $Amount;
    public $ExpenseDate;
    public $Notes;
    public $CreatedAt;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Insert new expense
    public function insertData() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET UserId=:UserId, CategoryId=:CategoryId, Title=:Title, 
                      Amount=:Amount, ExpenseDate=:ExpenseDate, Notes=:Notes";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->UserId = htmlspecialchars(strip_tags($this->UserId));
        $this->CategoryId = htmlspecialchars(strip_tags($this->CategoryId));
        $this->Title = htmlspecialchars(strip_tags($this->Title));
        $this->Amount = htmlspecialchars(strip_tags($this->Amount));
        $this->ExpenseDate = htmlspecialchars(strip_tags($this->ExpenseDate));
        $this->Notes = htmlspecialchars(strip_tags($this->Notes));

        // Bind parameters
        $stmt->bindParam(":UserId", $this->UserId);
        $stmt->bindParam(":CategoryId", $this->CategoryId);
        $stmt->bindParam(":Title", $this->Title);
        $stmt->bindParam(":Amount", $this->Amount);
        $stmt->bindParam(":ExpenseDate", $this->ExpenseDate);
        $stmt->bindParam(":Notes", $this->Notes);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Fetch all expenses for a user
    public function getUserExpenses($userId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE UserId = :UserId ORDER BY ExpenseDate DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":UserId", $userId);
        $stmt->execute();
        return $stmt;
    }

    // Fetch single expense by ID
    public function getExpenseById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE ExpenseId = :ExpenseId LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":ExpenseId", $id);
        $stmt->execute();
        return $stmt;
    }

    // Delete expense
    public function deleteExpense($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE ExpenseId = :ExpenseId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":ExpenseId", $id);
        return $stmt->execute();
    }

    public function updateExpense() {
    $query = "UPDATE " . $this->table_name . "
              SET UserId = :UserId,
                  CategoryId = :CategoryId,
                  Title = :Title,
                  Amount = :Amount,
                  ExpenseDate = :ExpenseDate,
                  Notes = :Notes
              WHERE ExpenseId = :ExpenseId";

    $stmt = $this->conn->prepare($query);

    // Sanitize
    $this->UserId = htmlspecialchars(strip_tags($this->UserId));
    $this->CategoryId = htmlspecialchars(strip_tags($this->CategoryId));
    $this->Title = htmlspecialchars(strip_tags($this->Title));
    $this->Amount = htmlspecialchars(strip_tags($this->Amount));
    $this->ExpenseDate = htmlspecialchars(strip_tags($this->ExpenseDate));
    $this->Notes = htmlspecialchars(strip_tags($this->Notes));
    $this->ExpenseId = htmlspecialchars(strip_tags($this->ExpenseId));

    // Bind values
    $stmt->bindParam(":UserId", $this->UserId);
    $stmt->bindParam(":CategoryId", $this->CategoryId);
    $stmt->bindParam(":Title", $this->Title);
    $stmt->bindParam(":Amount", $this->Amount);
    $stmt->bindParam(":ExpenseDate", $this->ExpenseDate);
    $stmt->bindParam(":Notes", $this->Notes);
    $stmt->bindParam(":ExpenseId", $this->ExpenseId);

    if ($stmt->execute()) {
        return true;
    }
    return false;
}

}
?>
