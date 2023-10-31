<?php
session_start();
require_once 'config/db.php';

class SignUpDB {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
        if (isset($_POST['signup'])) {
            $this->handleSignUp();
        } else {
            $_SESSION['error'] = "There is no data";
            header("location: signup.php");
        }
    }

    private function handleSignUp() {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $cpassword = $_POST['c_password'];
        $urole = 'user';

        if (!$this->validateInputs($firstname, $lastname, $email, $password, $cpassword)) {
            header("location: signup.php");
            return;
        }

        $this->registerUser($firstname, $lastname, $email, $password, $urole);
    }

    private function validateInputs($firstname, $lastname, $email, $password, $cpassword) {
        if (empty($firstname)) {
            $_SESSION['error'] = 'FirstName is required';
            return false;
        } else if (empty($lastname)) {
            $_SESSION['error'] = 'LastName is required';
            return false;
        } else if (empty($email)) {
            $_SESSION['error'] = 'Email is required';
            return false;
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email format is not correct';
            return false;
        } else if (empty($password)) {
            $_SESSION['error'] = 'Password is required';
            return false;
        } else if (strlen($password) > 20 || strlen($password) < 5) {
            $_SESSION['error'] = 'Password length must be between 6 to 20 characters';
            return false;
        } else if ($password != $cpassword) {
            $_SESSION['error'] = 'Password not matched';
            return false;
        }
        return true;
    }

    private function registerUser($firstname, $lastname, $email, $password, $urole) {
        try {
            $check_email = $this->conn->prepare("SELECT email FROM users WHERE email=:email");
            $check_email->bindParam(":email", $email);
            $check_email->execute();
            $row = $check_email->fetch(PDO::FETCH_ASSOC);

            if ($row['email'] == $email) {
                $_SESSION['warning'] = "This email is already in System <a href='index.php'>Click here</a> to signin";
                $this->conn = null;
                header("location: signup.php");
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $this->conn->prepare("INSERT INTO users (firstname,lastname,email,password,urole) VALUES (:firstname,:lastname,:email,:pwd,:urole)");
                $stmt->bindParam(":firstname", $firstname);
                $stmt->bindParam(":lastname", $lastname);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":pwd", $passwordHash);
                $stmt->bindParam(":urole", $urole);
                $stmt->execute();
                $_SESSION['success'] = "Registration successfully done! <a href='index.php' class='alert-link'>Click here</a> to signin";
                $this->conn = null;
                header("location: signup.php");
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "There is some error: " . $e->getMessage();
            header("location: signup.php");
        }
    }
}

$db = new Database();
$connn = $db->getConnection();
$signUpDB = new SignUpDB($connn);
?>
