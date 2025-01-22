<?php
require_once __DIR__ . "/DBHelper.php";

class Employee {
    protected $id;
    protected $login;
    protected $firstname;
    protected $lastname;
    protected $email;
    protected $phone_number;
    protected $address;
    protected $city;
    protected $hire_date;
    protected $isAdmin;

    public function __construct($id = null) {
        if ($id !== null) {
            $this->id = $id;
            $this->loadEmployeeData();
        }
    }

    private function loadEmployeeData() {
        $employeeData = DBHelper::executeQuery("SELECT * FROM employee WHERE id = ?", [$this->id])->fetch_assoc();

        if (!$employeeData) {
            throw new Exception("Employee not found");
        }

        $this->login = $employeeData['login'];
        $this->firstname = $employeeData['firstname'];
        $this->lastname = $employeeData['lastname'];
        $this->email = $employeeData['email'];
        $this->phone_number = $employeeData['phone_number'];
        $this->address = $employeeData['address'];
        $this->city = $employeeData['city'];
        $this->hire_date = $employeeData['hire_date'];
        $this->isAdmin = $employeeData['isAdmin'];
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

    public function getPhoneNumber() {
        return $this->phone_number;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getCity() {
        return $this->city;
    }

    public function getHireDate() {
        return $this->hire_date;
    }

    public function isAdmin() {
        return $this->isAdmin;
    }
}