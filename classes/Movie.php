<?php 

class Movie{
    protected $title;
    protected $description;
    protected $length;
    protected $category;
    protected $release_date;
    protected $image;
    protected $poster_name;
    protected $id;
    
    public function __construct($id)
    {
        require __DIR__."/../connect.php";
        $sql = "SELECT * FROM movie WHERE movie.id = $id";
        $result = $conn->query($sql)->fetch_assoc();
        $category_id = $result['category_id'];
        $category_sql = "SELECT * FROM category WHERE category.id = $category_id";
        $category = $conn->query($category_sql)->fetch_assoc();

        $this->id = $result['id'];
        $this->title = $result['title'];
        $this->description = $result['description'];
        $this->length = $result['length'];
        $this->category = $category['name'];
        $this->release_date= $result['release_date'];
        $this->poster_name= $result['poster_name'];
    }
    public function getID(){
        return $this->id;
    }
    public function getTitle(){
        return $this->title;
    }
    public function getDescription(){
        return $this->description;
    }
    public function getCategory(){
        return $this->category;
    }
    public function getDate(){
        return $this->release_date;
    }
    public function Delete(){
        require __DIR__."/../connect.php";
        $sql = "DELETE FROM movie WHERE movie.id = $this->id";
        $poster_path = $_SERVER['DOCUMENT_ROOT']."/omega/posters/".$this->poster_name;
        if(!unlink($poster_path)){
            throw new Exception("Wystąpił problem z usuwaniem plakatu");
        }
        if(!$conn->query($sql)){
            throw new Exception("Wystąpił nieoczekiwany błąd. Proszę spróbować później");
        }
    }
    //Returns movie length in given unit
    public function getLength($unit = NULL){
        if($unit == 'm'){
            return $this->length."m";
        }elseif($unit == 'h'){
            $hrs = floor($this->length / 60);
            $mns = $this->length - ($hrs * 60);
            return $hrs."godz. ".$mns."min.";
        }elseif($unit == NULL){
            return $this->length;
        }else{
            throw new Exception("Nieobsługiwana jednostka");
        }
    }
    //Returns ready to paste poster link
    public function posterLink(){
        return "/omega/posters/".$this->poster_name;
    }
}

//Class used while adding new movie or editing
class createdMovie extends Movie{
    protected $poster;

    public function __construct($title, $description, $category_id, $length, $release_date, $poster)
    {
        $this->title = $title;
        $this->description = $description;
        $this->length = $length;
        $this->category_id = $category_id;
        $this->release_date= $release_date;
        $this->poster = $poster;
        $this->poster_name = $poster['name'];
    }
    public function uploadPoster(){
        $file_name = $this->poster_name;
        $target_path = $_SERVER['DOCUMENT_ROOT']."/omega/posters/".$file_name;
        $tmp_path = $this->poster['tmp_name'];
        $fileType = pathinfo($file_name, PATHINFO_EXTENSION);
        $allowedTypes = ['jpg', 'png', 'jpeg'];
        if(!in_array($fileType, $allowedTypes)){
            throw new Exception("Nieprawidłowe rozszerzenie pliku lub nie wybrano zdjęcia! Dozwolone typy to: .jpg, .jpeg, .png");
        } 
        if(!move_uploaded_file($tmp_path, $target_path)){
            throw new Exception("Błąd podczas przesyłania pliku");
        }
    }
    public function addToDb(){
        require __DIR__."/../connect.php";
        $sql = "INSERT INTO movie (title, description, length, category_id, release_date, poster_name) VALUES ('$this->title', '$this->description', $this->length, $this->category_id, '$this->release_date', '$this->poster_name')";
        try{
            $this->uploadPoster();
        }
        catch(Exception $e){
            throw new Exception($e->getMessage());
        }
        if(!$conn->query($sql)){
            throw new Exception("Problem z połączeniem z bazą danych!");
        }
    }
    public function update($id){
        require __DIR__."/../connect.php";
        $sql = "UPDATE movie SET title='$this->title', description='$this->description', length=$this->length, category_id=$this->category_id, release_date='$this->release_date', poster_name='$this->poster_name' WHERE id = $id";
        try{
            $this->uploadPoster();
            $conn->query($sql);
        }
        catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    public function updateWithoutPoster($id){
        require __DIR__."/../connect.php";
        $sql = "UPDATE movie SET title='$this->title', description='$this->description', length=$this->length, category_id=$this->category_id, release_date='$this->release_date' WHERE id = $id";
        try{
            $conn->query($sql);
        }
        catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
}