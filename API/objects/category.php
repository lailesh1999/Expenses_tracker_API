<?php
Class Category{

    private $conn;
    private $table_name = "categories";
    public $UserId;
    public $CategoryName;
    public $CategoryType;

     public function __construct($db) {
        $this->conn = $db;
    }

    public function insertData(){
        $query = "INSERT INTO " . $this->table_name . " SET UserId=:UserId, CategoryName=:CategoryName, CategoryType=:CategoryType";
        $stmt = $this->conn->prepare($query);
         // Sanitize data
        $this->UserId = htmlspecialchars(strip_tags($this->UserId));
        $this->CategoryName = htmlspecialchars(strip_tags($this->CategoryName));
        $this->CategoryType = htmlspecialchars(strip_tags($this->CategoryType));

        // Bind parameters
        $stmt->bindParam(":UserId", $this->UserId);
        $stmt->bindParam(":CategoryName", $this->CategoryName);
        $stmt->bindParam(":CategoryType", $this->CategoryType);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function checkDuplicate() {
    // Check if record already exists
    $checkQuery = "SELECT COUNT(*) as cnt 
                   FROM " . $this->table_name . " 
                   WHERE UserId = :UserId AND CategoryName = :CategoryName AND CategoryType = :CategoryType";
    $checkStmt = $this->conn->prepare($checkQuery);

    $checkStmt->bindParam(":UserId", $this->UserId);
    $checkStmt->bindParam(":CategoryName", $this->CategoryName);
    $checkStmt->bindParam(":CategoryType", $this->CategoryType);
    $checkStmt->execute();

    $row = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($row['cnt'] > 0) {
        return true;
    }

    return false;

  }

  public function getAllCategoriesByUser($userId) {
    $query = "SELECT * FROM " . $this->table_name . " WHERE UserId = :UserId";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":UserId", $userId);
    $stmt->execute();
    return $stmt;
}
    public function deleteCategory($categoryId) {
    $query = "DELETE FROM " . $this->table_name . " WHERE CategoryId = :CategoryId";
    $stmt = $this->conn->prepare($query);
    
    // Sanitize the category ID
    $categoryId = htmlspecialchars(strip_tags($categoryId));
    
    // Bind parameter
    $stmt->bindParam(":CategoryId", $categoryId);
    
    // Execute the query
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

 // ✅ Update category by ID
    public function updateCategory() {
        $query = "UPDATE " . $this->table_name . " 
                  SET CategoryName = :CategoryName, CategoryType = :CategoryType 
                  WHERE CategoryId = :CategoryId";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->CategoryId = htmlspecialchars(strip_tags($this->CategoryId));
        $this->CategoryName = htmlspecialchars(strip_tags($this->CategoryName));
        $this->CategoryType = htmlspecialchars(strip_tags($this->CategoryType));

        // Bind
        $stmt->bindParam(":CategoryId", $this->CategoryId);
        $stmt->bindParam(":CategoryName", $this->CategoryName);
        $stmt->bindParam(":CategoryType", $this->CategoryType);

        if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            return true; // ✅ Successfully updated
        } else {
            return false; // ❌ No rows matched (wrong CategoryId or same data)
        }
    }
    return false; // ❌ SQL execution failed
    }


    public function getCategoryNamesByUser($userId) {
    $query = "SELECT CategoryId, CategoryName 
              FROM " . $this->table_name . " 
              WHERE UserId = :UserId";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":UserId", $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}



}



?>