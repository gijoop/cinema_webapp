<?php
require_once __DIR__ . "/DBHelper.php";

class User {
    protected $id;
    protected $login;
    protected $firstname;
    protected $lastname;
    protected $email;
    protected $creation_date;
    protected $isEmployee;

    public function __construct($id = null) {
        if ($id !== null) {
            $this->id = $id;
            $this->loadUserData();
        }
    }

    private function loadUserData() {
        $userData = DBHelper::executeQuery("SELECT * FROM user WHERE id = ?", [$this->id])->fetch_assoc();

        if (!$userData) {
            throw new Exception("User not found");
        }

        $this->login = $userData['login'];
        $this->firstname = $userData['firstname'];
        $this->lastname = $userData['lastname'];
        $this->email = $userData['email'];
        $this->creation_date = $userData['creation_date'];
        $this->isEmployee = $userData['role'] == 'EMPLOYEE' ? true : false;
    }

    public function getID() {
        return $this->id;
    }

    public function getLogin() {
        return $this->login;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getDateCreated() {
        return $this->creation_date;
    }

    public function isEmployee() {
        return $this->isEmployee;
    }
}