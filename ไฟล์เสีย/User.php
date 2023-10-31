<?php
session_start();
require_once 'config/db.php';

class User {
    private $username;
    private $password;
    private $conn;

    public function __construct($conn, $username, $password) {
        $this->conn = $conn;     
        $this->username = $username;
        $this->password = $password;
    }
    
    public function checkPermission($username, $password) {
        try {
            $check_data = $this->conn->prepare("SELECT * FROM users WHERE email=:email");
            $check_data->bindParam(":email", $username);
            $check_data->execute();
            $row = $check_data->fetch(PDO::FETCH_ASSOC);

            if ($check_data->rowCount() > 0) {
                if ($row['email'] == $username) {
                    if (password_verify($password, $row['password'])) {
                        if ($row['urole'] == 'admin') {
                            $_SESSION['admin_login'] = $row['id'];
                            header("location: AdminSignin.php");
                        } else {
                            $_SESSION['user_login'] = $row['id'];
                            header("location: UserSignin.php");
                        }
                    } else {
                        $_SESSION['error'] = "Password is not correct";
                        $this->conn = null;
                        header("location: index.php");
                    }
                } else {
                    $_SESSION['error'] = "Username is not correct";
                    $this->conn = null;
                    header("location: index.php");
                }
            } else {
                $_SESSION['warning'] = "This user is not found. <a href='index.php'>Click here</a> to register";
                $this->conn = null;
                header("location: signup.php");
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "There is some error: " . $e->getMessage();
            header("location: index.php");
        }
    }

    public function validateInputs($username, $password) {
        if (empty($username)) {
            $_SESSION['error'] = 'Email is required';
            return false;
        } else if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email format is not correct';
            return false;
        } else if (empty($password)) {
            $_SESSION['error'] = 'Password is required';
            return false;
        } else if (strlen($password) > 20 || strlen($password) < 3) {
            $_SESSION['error'] = 'Password length must be between 4 to 20 characters';
            return false;
        }
        return true;
    }

    // Getter และ Setter สำหรับตัวแปร username และ password
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
}
?>
