<?php
interface UserEntity {
    public function __construct($connection);
    public function fetchData($id);
    public function getName();
}

