<?php
session_start();
require_once 'db.php';

class SignInDB {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
        if (isset($_POST['signin'])) {
            $this->handleSignIn();
        } else {
            $_SESSION['error'] = "There is no data";
            header("location: signin.php");
        }
    }

    private function handleSignIn() {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (!$this->validateInputs($email, $password)) {
            header("location: signin.php");
            return;
        }

        $this->checkUserInDatabase($email, $password);
    }

    private function validateInputs($email, $password) {
        if (empty($email)) {
            $_SESSION['error'] = 'Email is required';
            return false;
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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

    private function checkUserInDatabase($email, $password) {
        try {
            $check_data = $this->conn->prepare("SELECT * FROM users WHERE email=:email");
            $check_data->bindParam(":email", $email);
            $check_data->execute();
            $row = $check_data->fetch(PDO::FETCH_ASSOC);

            if ($check_data->rowCount() > 0) {
                if ($row['email'] == $email) {
                    if (password_verify($password, $row['password'])) {
                        if ($row['urole'] == 'admin') {
                            $_SESSION['admin_login'] = $row['id'];
                            header("location: admin.php");
                        } else {
                            $_SESSION['user_login'] = $row['id'];
                            header("location: user.php");
                        }
                    } else {
                        $_SESSION['error'] = "Password is not correct";
                        $this->conn = null;
                        header("location: signin.php");
                    }
                } else {
                    $_SESSION['error'] = "Username is not correct";
                    $this->conn = null;
                    header("location: signin.php");
                }
            } else {
                $_SESSION['warning'] = "This user is not found. <a href='index.php'>Click here</a> to register";
                $this->conn = null;
                header("location: index.php");
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "There is some error: " . $e->getMessage();
            header("location: signin.php");
        }
    }
}

$db = Database::getInstance();
$conn = $db->getConnection();
$signInDB = new SignInDB($conn);
?>
