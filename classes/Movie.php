<?php
require_once __DIR__ . "/DBHelper.php";

class Movie
{
    protected $id;
    protected $title;
    protected $description;
    protected $length;
    protected $category_id;
    protected $category_name;
    protected $release_date;
    protected $poster_name;
    protected $poster;

    public function __construct($id = null)
    {
        if ($id !== null) {
            $this->id = $id;
            $this->loadMovieData();
        }
    }

    private function loadMovieData()
    {
        $movieData = DBHelper::executeQuery("SELECT * FROM movie WHERE id = ?", [$this->id])->fetch_assoc();

        if (!$movieData) {
            throw new Exception("Movie not found");
        }

        $categoryData = DBHelper::executeQuery("SELECT name FROM category WHERE id = ?", [$movieData['category_id']])->fetch_assoc();

        $this->title = $movieData['title'];
        $this->description = $movieData['description'];
        $this->length = $movieData['length'];
        $this->category_id = $movieData['category_id'];
        $this->category_name = $categoryData['name'] ?? 'Unknown';
        $this->release_date = $movieData['release_date'];
        $this->poster_name = $movieData['poster_name'];
    }

    public function getID()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCategory()
    {
        return $this->category_name;
    }

    public function getCategoryID()
    {
        return $this->category_id;
    }

    public function getDate()
    {
        return $this->release_date;
    }

    public function getPosterName()
    {
        return $this->poster_name;
    }

    public function getLength($unit = null)
    {
        if ($unit === 'm') {
            return $this->length . "m";
        } elseif ($unit === 'h') {
            $hours = floor($this->length / 60);
            $minutes = $this->length % 60;
            return $hours . " godz. " . $minutes . " min.";
        }
        return $this->length;
    }

    public function getPosterLink()
    {
        return "/omega/posters/" . $this->poster_name;
    }
}
