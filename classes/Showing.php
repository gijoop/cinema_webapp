<?php
require_once __DIR__."/../classes/DBHelper.php";
require_once __DIR__."/../classes/Movie.php";

class Showing {
    protected $id;
    protected $movie;
    protected $date;
    protected $time;
    protected $hall_id;
    protected $language_id;

    function __construct($id) {
        $result = DBHelper::executeQuery("SELECT * FROM showing WHERE id = ?", [$id])->fetch_assoc();
        $this->id = $result['id'];
        $this->movie = new Movie($result['movie_id']);
        $this->date = $result['date'];
        $this->time = $result['time'];
        $this->hall_id = $result['hall_id'];
        $this->language_id = $result['language_id'];
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

    public function getHall() {
        return $this->hall_id;
    }

    public function getSeatsNum() {
        $result = DBHelper::executeQuery("SELECT seats FROM hall WHERE id = ?", [$this->hall_id])->fetch_assoc();
        return $result['seats'];
    }

    public function getLanguage() {
        $result = DBHelper::executeQuery("SELECT name FROM language WHERE id = ?", [$this->language_id])->fetch_assoc();
        return $result['name'];
    }

    public function getLanguageID() {
        return $this->language_id;
    }

    public function delete() {
        if (!DBHelper::executeQuery("DELETE FROM showing WHERE id = ?", [$this->id])) {
            throw new Exception("Wystąpił nieoczekiwany błąd. Proszę spróbować później");
        }
    }
}