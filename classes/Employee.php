<?php
require_once 'User.php';

class Employee extends User
{
    protected $isAdmin;
    protected $phone_number;
    protected $adress;
    protected $city;
    protected $hire_date;
    protected $em_id;

    function __construct($id)
    {
        require __DIR__ . "/../connect.php";
        $sql = "SELECT * FROM employee WHERE employee.id = $id";
        $result = $conn->query($sql)->fetch_assoc();
        $this->em_id = $result['id'];
        $this->login = $result['login'];
        $this->isAdmin = $result['isAdmin'];
        $this->firstname = $result['firstname'];
        $this->lastname = $result['lastname'];
        $this->email = $result['email'];
        $this->phone_number = $result['phone_number'];
        $this->adress = $result['adress'];
        $this->city = $result['city'];
        $this->hire_date = $result['hire_date'];
    }
    public function getID(){
        return $this->em_id;
    }
    public function getNumber()
    {
        return $this->phone_number;
    }
    public function getAdress()
    {
        return $this->adress;
    }
    public function getCity()
    {
        return $this->city;
    }
    public function getHireDate()
    {
        return $this->hire_date;
    }
    public function isAdmin(){
        return $this->isAdmin;
    }

    public function Delete(){
        require __DIR__."/../connect.php";
        $sql = "DELETE FROM employee WHERE id = $this->em_id";
        if(!$conn->query($sql)){
            throw new Exception("Wystąpił nieoczekiwany błąd. Proszę spróbować później");
        }
    }
}
