<?php
// index.php

declare(strict_types=1);

require_once "classes/DBHelper.php";
require_once "classes/User.php";
require_once "classes/Showing.php";
session_start();

// Constants
const DATE_FORMAT = 'Y-m-d';
const DISPLAY_DATE_FORMAT = 'd.m.Y';
const IMAGE_PATH = 'images/';
$_SERVER['ROOT'] = __DIR__ . '/cinema_webapp';

function formatDate(string $date, string $format = DISPLAY_DATE_FORMAT): string {
    return date($format, strtotime($date));
}

function renderMovie($movie): string {
    $title = $movie->getTitle();
    $releaseDate = formatDate($movie->getDate());
    $img = $movie->getPosterLink();
    return "<div class='comingmovie'>
        <div style='background-image: url($img)' class='moviephoto'></div>
        <b>$title</b><br>
        <span class='release-date'>$releaseDate</span>
    </div>";
}

function renderShowing($showing): string {
    $id = $showing->getID();
    $movie = $showing->getMovie();
    $time = $showing->getTime();

    return "<div class='showing'>
        <div class='showing-img' style='background-image: url({$movie->getPosterLink()})'></div>
        <div class='showing-properties'>
            <span class='showing-title'>{$movie->getTitle()}</span>
            <span class='showing-details'>{$movie->getLength('h')} &nbsp {$movie->getCategory()} &nbsp {$movie->getDate()} &nbsp {$showing->getLanguage()}</span>
            <p class='showing-description'>{$movie->getDescription()}</p>
        </div>
        <div class='showing-column'>
            <span class='showing-time'>$time</span>
            <a href='book_seats.php?id=$id' class='showing-button'>Zarezerwuj</a>
        </div>
    </div>";
}

date_default_timezone_set('Europe/Warsaw');
$date = isset($_GET['date']) ? formatDate($_GET['date'], DATE_FORMAT) : date(DATE_FORMAT);
$comingMovies = DBHelper::executeProcedure("get_upcoming_movies")->fetch_all();
$showings = DBHelper::executeQuery("SELECT id FROM showing WHERE date = ? ORDER BY time ASC", [$date])->fetch_all();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="<?= IMAGE_PATH ?>icon.ico" type="image/x-icon">
    <title>Sieć kin Omega</title>
</head>
<body>
<div class="top-panel">
    <div class="top-box" style="justify-content: left;">
        <img src="<?= IMAGE_PATH ?>logo.png" width="50%">
    </div>
    <div class="top-box" style="justify-content: right; width:40%;">
        <?php
        if (isset($user)){
            echo "<h1 class='greeting'>Witaj {$user->getFirstname()}!</h1>";
            if($user->isEmployee()){
                echo "<a href='pracownik/employee_data.php' class='form-button'>Panel pracownika</a>";
            }
            echo "<a href='user_panel.php' class='form-button'>Panel użytkownika</a>";
            echo "<a href='logout.php' class='form-button'>Wyloguj</a>";
        }else{
            echo "<a href='login.php' class='form-button'>Zaloguj</a>";
            echo "<a href='register.php' class='form-button'>Zarejestruj</a>";
        }
        ?>
    </div>
</div>

<div class="main">
    <div class="header">
        Wkrótce <br> <hr class="hr-break" align="left">
    </div>
    <div class="comingsoon">
        <?php 
        foreach ($comingMovies as $movie) {
            echo renderMovie(new Movie($movie[0]));
        }
        ?>
    </div>

    <div class="header" id="showings">
        Repertuar <br> <hr class="hr-break" align="left">
    </div>
    <div class="date-picker">
        <?php 
        $dplus = formatDate(date(DATE_FORMAT, strtotime($date . ' +1 day')));
        $dminus = formatDate(date(DATE_FORMAT, strtotime($date . ' -1 day')));

        if (strtotime($date) > strtotime(date(DATE_FORMAT))) {
            echo "<a href='index.php?date=$dminus#showings' class='date-button'> < </a>";
        } else {
            echo "<div class='date-button-unactive date-button'> < </div>";
        }

        echo formatDate($date);

        echo "<a href='index.php?date=$dplus#showings' class='date-button'> > </a>";
        ?>
    </div>
    <br>
    <hr class="hr-break" style="width:20%;"><br>
    <?php 
    if (count($showings) === 0) {
        echo "<h2>W tym dniu repertuar jest pusty</h2>";
    } else {
        foreach ($showings as $showing) {
            echo renderShowing(new Showing($showing[0]), $date);
        }
    }
    ?>
</div>
</body>
</html>