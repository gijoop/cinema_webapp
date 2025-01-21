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

    public function setProperties($login, $firstname, $lastname, $email, $phone_number, $address, $city, $hire_date, $isAdmin) {
        $this->login = $login;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->phone_number = $phone_number;
        $this->address = $address;
        $this->city = $city;
        $this->hire_date = $hire_date;
        $this->isAdmin = $isAdmin;
    }

    public function save() {
        try {
            if ($this->id) {
                // Update existing employee
                DBHelper::executeQuery(
                    "UPDATE employee SET login = ?, firstname = ?, lastname = ?, email = ?, phone_number = ?, address = ?, city = ?, hire_date = ?, isAdmin = ? WHERE id = ?",
                    [$this->login, $this->firstname, $this->lastname, $this->email, $this->phone_number, $this->address, $this->city, $this->hire_date, $this->isAdmin, $this->id]
                );
            } else {
                // Add new employee
                DBHelper::executeQuery(
                    "INSERT INTO employee (login, firstname, lastname, email, phone_number, address, city, hire_date, isAdmin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    [$this->login, $this->firstname, $this->lastname, $this->email, $this->phone_number, $this->address, $this->city, $this->hire_date, $this->isAdmin]
                );
                $this->id = DBHelper::getConnection()->insert_id;
            }
        } catch (Exception $e) {
            throw new Exception("Failed to save employee: " . $e->getMessage());
        }
    }

    public function delete() {
        try {
            DBHelper::executeQuery("DELETE FROM employee WHERE id = ?", [$this->id]);
        } catch (Exception $e) {
            throw new Exception("Failed to delete employee: " . $e->getMessage());
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