<?php
require_once __DIR__."/../classes/DBHelper.php";
require_once __DIR__."/../classes/Movie.php";

class Showing {
    protected $id;
    protected $movie;
    protected $date;
    protected $time;
    protected $room_id;
    protected $language_id;

    function __construct($id) {
        $this->id = $id;
        $this->loadShowingData();
    }

    private function loadShowingData() {
        $showingData = DBHelper::executeQuery("SELECT * FROM showing WHERE id = ?", [$this->id])->fetch_assoc();

        if (!$showingData) {
            throw new Exception("Showing not found");
        }

        $this->movie = new Movie($showingData['movie_id']);
        $this->date = $showingData['date'];
        $this->time = $showingData['time'];
        $this->room_id = $showingData['room_id'];
        $this->language_id = $showingData['language_id'];
    }

    public function getID() {
        return $this->id;
    }

    public function getMovie() {
        return $this->movie;
    }

    public function getDate() {
        return $this->date;
    }

    public function getTime() {
        return date('H:i', strtotime($this->time));
    }

    public function getRoom() {
        return $this->room_id;
    }

    public function getSeatsNum() {
        $result = DBHelper::executeQuery("SELECT num_seats FROM room WHERE id = ?", [$this->room_id])->fetch_assoc();
        return $result['num_seats'];
    }

    public function getLanguage() {
        $result = DBHelper::executeQuery("SELECT name FROM language WHERE id = ?", [$this->language_id])->fetch_assoc();
        return $result['name'];
    }

    public function getLanguageID() {
        return $this->language_id;
    }
}