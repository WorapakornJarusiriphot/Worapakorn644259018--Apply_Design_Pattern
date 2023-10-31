<?php
session_start();

class Logout {
    public function __construct() {
        $this->endSession();
        $this->redirectToIndex();
    }

    private function endSession() {
        unset($_SESSION['user_login']);
        unset($_SESSION['admin_login']);
    }

    private function redirectToIndex() {
        header('location:index.php');
    }
}

$logout = new Logout();
?>
