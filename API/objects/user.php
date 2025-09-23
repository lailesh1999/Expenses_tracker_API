<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $password;
    public $email;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }
      public function usernameExists() {
        $query = "SELECT userid FROM " . $this->table_name . " WHERE username = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->username = htmlspecialchars(strip_tags($this->username));
        $stmt->bindParam(1, $this->username);
        $stmt->execute();
        $num = $stmt->rowCount();

        return $num > 0;
    }
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, password=:password, email=:email";

        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // Hash the password for security
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        // Bind parameters
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

     public function login() {
        // Prepare the query to select a user by their email
        $query = "SELECT userid,username, password FROM " . $this->table_name . " WHERE username = ? LIMIT 1";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Sanitize and bind the email parameter
        $this->username = htmlspecialchars(strip_tags($this->username));
        $stmt->bindParam(1, $this->username);
 
        // Execute the query
        $stmt->execute();

        // Fetch the row if it exists
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // A user was found, so set the properties
            $this->id = $row['userid'];
            $this->password = $row['password']; // This is the hashed password from the database
            $this->username = $row['username'];
            return true;
        }

        return false;
    }


}
?>