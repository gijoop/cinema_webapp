<?php
class Category
{
    protected $id;
    protected $category;

    function __construct($id)
    {
        $this->id = $id;
        $this->loadCategoryData();
        
    }

    private function loadCategoryData(){
        $categoryData = DBHelper::executeQuery("SELECT * FROM category WHERE id = ?", [$this->id])->fetch_assoc();

        if (!$categoryData) {
            throw new Exception("User not found");
        }

        $this->category = $categoryData['name'];
    }

    public function getName(){
        return $this->category;
    }
    public function getID(){
        return $this->id;
    }
}
