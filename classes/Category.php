<?php
class Category
{
    protected $id;
    protected $category;

    function __construct(string $name){
        require __DIR__ . '/../connect.php';
        $category_sql = "SELECT category.id FROM category WHERE category.name = '$name'";
        $category_id = $conn->query($category_sql)->fetch_assoc()['id'];
        $this->id = $category_id;
        $this->category = $name;
    }

    public function getName(){
        return $this->category;
    }
    public function getID(){
        return $this->id;
    }
}
