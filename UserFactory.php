<?php
require_once 'Admin.php';
require_once 'User.php';

class UserFactory {
    public static function createUser($type, $connection) {
        switch ($type) {
            case 'admin':
                return new Admin($connection);
            case 'user':
                return new User($connection);
            default:
                throw new Exception("Invalid user type: $type");
        }
    }
}
