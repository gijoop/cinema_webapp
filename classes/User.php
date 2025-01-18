<?php
class User{
    protected $id;
    protected $login;
    protected $firstname;
    protected $lastname;
    protected $email;
    protected $date_created;
 
    function __construct($id)
    {
        require __DIR__."/../connect.php";
        $sql = "SELECT * FROM user WHERE user.id = $id";
        $result = $conn->query($sql)->fetch_assoc();
        $this->id = $result['id'];
        $this->login = $result['login'];
        $this->firstname = $result['firstname'];
        $this->lastname = $result['lastname'];
        $this->email = $result['email'];
        $this->date_created = $result['date_created'];
    }

    public function getID(){
        return $this->id;
    }
    public function getLogin(){
        return $this->login;
    }
    public function getFirstname(){
        return $this->firstname;
    }
    public function getLastname(){
        return $this->lastname;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getDate(){
        return $this->date_created;
    }
}