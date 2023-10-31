<?php
class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUserData($userId) {
        $stmt = $this->conn->query("SELECT * FROM users WHERE id=$userId");
        return $stmt->fetch();
    }
}
