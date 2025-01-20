<?php
// index.php

declare(strict_types=1);

require_once "classes/DBHelper.php";
require_once "classes/Employee.php";
require_once "func/functions.php";
require_once "func/main.php";
session_start();

//Constants
const DATE_FORMAT = 'd.m.Y';
const IMAGE_PATH = 'images/';
$_SERVER['ROOT'] = __DIR__ . '/cinema_webapp';

// Functions
function getUserGreeting(): array {
    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        return [
            'greeting' => "Witaj " . $user->getFirstname() . "!",
            'links' => [
                'Panel użytkownika' => 'user_panel.php',
                'Wyloguj' => 'logout.php',
            ],
        ];
    } elseif (isset($_SESSION['employee'])) {
        $employee = $_SESSION['employee'];
        return [
            'greeting' => "Witaj " . $employee->getFirstname() . "!",
            'links' => [
                'Panel pracownika' => 'pracownik/dane_pracownika.php',
                'Wyloguj' => 'logout.php',
            ],
        ];
    }
    return [
        'links' => [
            'Zaloguj' => 'login.php',
            'Zarejestruj' => 'register.php',
        ],
    ];
}

function getValidDate(?string $requestedDate): string {
    $currentDate = date(DATE_FORMAT);
    if (!$requestedDate || strtotime($requestedDate) < strtotime($currentDate)) {
        return $currentDate;
    }
    return $requestedDate;
}

function renderMovie($movie): string {
    $title = $movie->getTitle();
    $releaseDate = formatDate($movie->getDate());
    $img = $movie->posterLink();
    return "<div class='comingmovie'>
        <div style='background-image: url($img)' class='moviephoto'></div>
        <b>$title</b><br>
        <span class='release-date'>$releaseDate</span>
    </div>";
}

function renderShowing($showing, string $currentDate): string {
    $id = $showing->getID();
    $movie = $showing->getMovie();
    $time = $showing->getTime();

    if ($currentDate == date('Y-m-d') && strtotime($time) < strtotime(date('H:i'))) {
        return ''; // Skip past showings
    }

    return "<div class='showing'>
        <div class='showing-img' style='background-image: url({$movie->posterLink()})'></div>
        <div class='showing-properties'>
            <span class='showing-title'>{$movie->getTitle()}</span>
            <span class='showing-details'>{$movie->getLength('h')} &nbsp {$movie->getCategory()} &nbsp {$movie->getReleaseYear()} &nbsp {$showing->getLanguage()}</span>
            <p class='showing-description'>{$movie->getDescription()}</p>
        </div>
        <div class='showing-column'>
            <span class='showing-time'>$time</span>
            <a href='zarezerwuj.php?id=$id' class='showing-button'>Zarezerwuj</a>
        </div>
    </div>";
}

// Main Logic
date_default_timezone_set('Europe/Warsaw');
$date = getValidDate($_GET['date'] ?? null);
$userData = getUserGreeting();
$comingMovies = comingMovies();
$showings = getShowings(formatDate($date, 'Y-m-d'));

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
        echo isset($userData['greeting']) ? "<h1 class='greeting'>{$userData['greeting']}</h1>" : '';
        foreach ($userData['links'] as $text => $url) {
            echo "<a href='{$url}' class='form-button'>{$text}</a>";
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
            echo renderMovie($movie);
        }
        ?>
    </div>

    <div class="header" id="showings">
        Repertuar <br> <hr class="hr-break" align="left">
    </div>
    <div class="date-picker">
        <?php 
        $dplus = date(DATE_FORMAT, strtotime($date . '+1 day'));
        $dminus = date(DATE_FORMAT, strtotime($date . '-1 day'));

        echo (strtotime($date) > strtotime(date(DATE_FORMAT))) 
            ? "<a href='index.php?date=$dminus#showings' class='date-button'> < </a>" 
            : "<div class='date-button-unactive date-button'> < </div>";

        echo $date;

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
            echo renderShowing($showing, formatDate($date, 'Y-m-d'));
        }
    }
    ?>
</div>
</body>
</html>
