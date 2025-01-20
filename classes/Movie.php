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

    public function setProperties($title, $description, $length, $category_id, $release_date, $poster = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->length = $length;
        $this->category_id = $category_id;
        $this->release_date = $release_date;
        $this->poster = $poster;
        $this->poster_name = $poster ? $poster['name'] : $this->poster_name;
    }

    private function uploadPoster()
    {
        if ($this->poster) {
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/omega/posters/" . $this->poster_name;
            $fileType = pathinfo($this->poster_name, PATHINFO_EXTENSION);
            $allowedTypes = ['jpg', 'jpeg', 'png'];

            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("Invalid file type. Allowed types: .jpg, .jpeg, .png");
            }

            if (!move_uploaded_file($this->poster['tmp_name'], $targetPath)) {
                throw new Exception("Failed to upload poster file");
            }
        }
    }

    public function save()
    {
        try {
            if ($this->id) {
                // Update existing movie
                $this->uploadPoster();
                DBHelper::executeQuery(
                    "UPDATE movie SET title = ?, description = ?, length = ?, category_id = ?, release_date = ?, poster_name = ? WHERE id = ?",
                    [$this->title, $this->description, $this->length, $this->category_id, $this->release_date, $this->poster_name, $this->id]
                );
            } else {
                // Add new movie
                $this->uploadPoster();
                DBHelper::executeQuery(
                    "INSERT INTO movie (title, description, length, category_id, release_date, poster_name) VALUES (?, ?, ?, ?, ?, ?)",
                    [$this->title, $this->description, $this->length, $this->category_id, $this->release_date, $this->poster_name]
                );
                $this->id = DBHelper::getConnection()->insert_id;
            }
        } catch (Exception $e) {
            throw new Exception("Failed to save movie: " . $e->getMessage());
        }
    }

    public function delete()
    {
        $dbHelper = new DBHelper();

        // Delete the poster file
        $posterPath = $_SERVER['DOCUMENT_ROOT'] . "/omega/posters/" . $this->poster_name;
        if (!unlink($posterPath)) {
            throw new Exception("Failed to delete poster file");
        }

        // Delete the movie record
        $dbHelper->executeQuery("DELETE FROM movie WHERE id = ?", [$this->id]);
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

    public function getDate()
    {
        return $this->release_date;
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

    public function posterLink()
    {
        return "/omega/posters/" . $this->poster_name;
    }
}
