<?php
require_once __DIR__ . "/DBHelper.php";

class User {
    protected $id;
    protected $login;
    protected $firstname;
    protected $lastname;
    protected $email;
    protected $date_created;

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
        $this->date_created = $userData['date_created'];
    }

    public function setProperties($login, $firstname, $lastname, $email, $date_created) {
        $this->login = $login;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->date_created = $date_created;
    }

    public function save() {
        try {
            if ($this->id) {
                // Update existing user
                DBHelper::executeQuery(
                    "UPDATE user SET login = ?, firstname = ?, lastname = ?, email = ?, date_created = ? WHERE id = ?",
                    [$this->login, $this->firstname, $this->lastname, $this->email, $this->date_created, $this->id]
                );
            } else {
                // Add new user
                DBHelper::executeQuery(
                    "INSERT INTO user (login, firstname, lastname, email, date_created) VALUES (?, ?, ?, ?, ?)",
                    [$this->login, $this->firstname, $this->lastname, $this->email, $this->date_created]
                );
                $this->id = DBHelper::getConnection()->insert_id;
            }
        } catch (Exception $e) {
            throw new Exception("Failed to save user: " . $e->getMessage());
        }
    }

    public function delete() {
        try {
            DBHelper::executeQuery("DELETE FROM user WHERE id = ?", [$this->id]);
        } catch (Exception $e) {
            throw new Exception("Failed to delete user: " . $e->getMessage());
        }
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
        return $this->date_created;
    }
}