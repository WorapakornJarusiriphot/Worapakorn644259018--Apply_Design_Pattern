<?php
session_start();
require_once 'config/db.php';
require_once 'User.php';

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
        $db = new Database();
        $conn = $db->getConnection();
        $username = $_POST['email'];
        $password = $_POST['password'];
        $UserSignIn = new User($conn ,$username , $password);
        if (!$UserSignIn->validateInputs($username, $password)) {
            header("location: signin.php");
            return;
        }

        $UserSignIn->checkPermission($username, $password);
    }
}

$db = new Database();
$conn = $db->getConnection();
$signInDB = new SignInDB($conn);
?>
