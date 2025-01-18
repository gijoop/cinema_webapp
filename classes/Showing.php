<?php
class Showing{
    protected $id;
    protected $movie;
    protected $date;
    protected $time;
    protected $hall_id;
    protected $language_id;

    function __construct($id)
    {
        require __DIR__."/../connect.php";
        require_once __DIR__."/../classes/Movie.php";
        $sql = "SELECT * FROM showing WHERE showing.id = $id";
        $result = $conn->query($sql)->fetch_assoc();
        $this->movie = new Movie($result['movie_id']);
        $this->id = $result['id'];
        $this->date = $result['date'];
        $this->time = $result['time'];
        $this->hall_id = $result['hall_id'];
        $this->language_id = $result['language_id'];
    }

    public function getID(){
        return $this->id;
    }
    public function getMovie(){
        return $this->movie;
    }
    public function getDate(){
        return $this->date;
    }
    public function getTime(){
        $t = $this->time;
        return date('H:i', strtotime($t));
    }
    public function getHall(){
        return $this->hall_id;
    }
    public function getSeatsNum(){
        require __DIR__."/../connect.php";
        $sql = "SELECT seats FROM hall WHERE hall.id = $this->hall_id";
        return $conn->query($sql)->fetch_assoc()['seats'];   
    }
    public function getLanguage(){
        require __DIR__."/../connect.php";
        $sql = "SELECT name FROM language WHERE id = $this->language_id";
        $result = $conn->query($sql)->fetch_assoc();
        return $result['name'];
    }
    public function getLanguageID(){
        return $this->language_id;
    }

    public function Delete(){
        require __DIR__."/../connect.php";
        $sql = "DELETE FROM showing WHERE showing.id = $this->id";
        if(!$conn->query($sql)){
            throw new Exception("Wystąpił nieoczekiwany błąd. Proszę spróbować później");
        }
    }
}