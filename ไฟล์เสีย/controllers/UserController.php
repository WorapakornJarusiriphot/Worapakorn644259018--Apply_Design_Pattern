<?php
class UserController {
    private $userModel;
    private $userView;

    public function __construct($userModel, $userView) {
        $this->userModel = $userModel;
        $this->userView = $userView;
    }

    public function showUser($userId) {
        $userData = $this->userModel->getUserData($userId);
        $this->userView->displayUserData($userData);
    }
}
