<?php
require_once __DIR__."/../classes/Movie.php";
require_once __DIR__."/../classes/Showing.php";
require_once __DIR__."/../classes/Employee.php";
require_once __DIR__."/../func/main.php";
if(isset($_GET['id_movie_del'])){
    $movie = new Movie($_GET['id_movie_del']);
    try{
        $movie->Delete();
    }catch(Exception $e){
        setError("Przed usunięciem filmu usuń wszystkie powiązane z nim seanse!");
    }
    header("Location: filmy.php");
}elseif(isset($_GET['id_showing_del'])){
    $showing = new Showing($_GET['id_showing_del']);
    try{
        $showing->Delete();
    }catch(Exception $e){
        setError($e->getMessage());
    }
    header("Location: seanse.php");
}elseif(isset($_GET['id_employee_del'])){
    $employee = new Employee($_GET['id_employee_del']);
    try{
        $employee->Delete();
    }catch(Exception $e){
        echo $e->getMessage();
        setError($e->getMessage());
    }
    header("Location: pracownicy.php");
}else{
    header("Location: dane_pracownika.php");
}