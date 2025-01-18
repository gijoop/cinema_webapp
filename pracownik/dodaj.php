<?php
require_once __DIR__."/../classes/Movie.php";
require_once __DIR__."/../func/main.php";
if(isset($_POST['submit_movie'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $length = $_POST['length'];
    $date = $_POST['date'];
    $poster = $_FILES['poster'];
    $movie = new createdMovie($title, $description, $category_id, $length, $date, $poster);
    try{
        $movie->addToDb();
    }catch(Exception $e){
        setError($e->getMessage());
    }
    header("Location: filmy.php");
    
}elseif(isset($_POST['submit_showing'])){
    require __DIR__."/../connect.php";
    $movie_id = $_POST['movie_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $hall_id = $_POST['hall_id'];
    $language_id = $_POST['language_id'];
    $sql = "INSERT INTO showing (movie_id, date, time, hall_id, language_id) VALUES ($movie_id, '$date', '$time', $hall_id, $language_id)";
    if(!$conn->query($sql)){
        setError("Wystąpił błąd z bazą danych!");
    }
    header("Location: seanse.php");
}elseif(isset($_POST['submit_employee'])){
    require __DIR__."/../connect.php";
    $login = $_POST['login'];
    $firstname = $_POST['firstname'];
    $secondname = $_POST['secondname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $adress = $_POST['adress'];
    $city = $_POST['city'];
    $hiredate = $_POST['hiredate'];
    $isadmin = $_POST['isadmin'];
    
    $sql = "INSERT INTO employee (login, isAdmin, firstname, lastname, email, phone_number, adress, city, hire_date) VALUES ('$login', $isadmin, '$firstname', '$secondname', '$email', '$phone', '$adress', '$city', '$hiredate')";
    if(!$conn->query($sql)){
        setError("Wystąpił błąd z bazą danych!");
    }
    header("Location: pracownicy.php");
}else{
    header("Location: dane_pracownika.php");
}