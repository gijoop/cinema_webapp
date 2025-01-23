<?php
//Returns 3 upcoming movies with closest release date
function comingMovies(){
    require __DIR__."/../connect.php";
    $sql = "SELECT movie.id FROM movie WHERE movie.release_date > NOW() ORDER BY movie.release_date ASC LIMIT 3;";
    $result = $conn->query($sql)->fetch_all();
    $output = [];
    foreach($result as $r){
        array_push($output, new Movie($r[0]));
    }
    return $output;
}

//Returns all showings at given date
function getShowings($date){
    require __DIR__."/../connect.php";
    require_once __DIR__."/../classes/Showing.php";
    $sql = "SELECT showing.id FROM showing WHERE showing.date = '$date' ORDER BY time ASC";
    $result = $conn->query($sql)->fetch_all();
    $output = [];
    foreach($result as $s){
        array_push($output, new Showing($s[0]));
    }
    return $output;
}

//Echoes all categories as select options
function addSelectCategories(){
    require __DIR__."/../connect.php";
    $sql = "SELECT name, id FROM category";
    $results = $conn->query($sql)->fetch_all();
    foreach($results as $c){
        $name = $c[0];
        $id = $c[1];
        echo "<option value='$id'>".$name."</option>";
    }
}
function addSelectMovies(){
    require __DIR__."/../connect.php";
    $sql = "SELECT title, id FROM movie";
    $results = $conn->query($sql)->fetch_all();
    foreach($results as $m){
        $id = $m[1];
        $name = $m[0];
        echo "<option value='$id'>".$name."</option>";
    }
}
function addSelectRooms(){
    require __DIR__."/../connect.php";
    $sql = "SELECT id FROM room";
    $results = $conn->query($sql)->fetch_all();
    foreach($results as $h){
        $id = $h[0];
        echo "<option>".$id."</option>";
    }
}
function addSelectLanguages(){
    require __DIR__."/../connect.php";
    $sql = "SELECT name, id FROM language";
    $results = $conn->query($sql)->fetch_all();
    foreach($results as $l){
        $id = $l[1];
        $name = $l[0];
        echo "<option value='$id'>".$name."</option>";
    }
}