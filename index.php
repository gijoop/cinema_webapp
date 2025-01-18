<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/icon.ico" type="image/x-icon">
    <title>Sieć kin Omega</title>
    <?php
        require_once "classes/Movie.php";
        require_once "classes/User.php";
        require_once "classes/Employee.php";
        require "func/main.php";
        require "func/functions.php";
        session_start();
    ?>
</head>
<body>
    <div class="top-panel">
        <div class="top-box" style="justify-content: left;">
            <img src="images/logo.png" width="50%">
        </div>
        <div class="top-box" style="justify-content: right; width:40%;">
            <?php
            if(isset($_SESSION['user'])){
                $user = $_SESSION['user'];
                $firstname = $user->getFirstname();
                echo '
                <h1 class="greeting">'."Witaj $firstname!".'</h1>
                <a href="panel_uzytkownika.php" class="form-button">
                    Panel użytkownika
                </a>
                <a href="wyloguj.php" class="form-button">
                    Wyloguj
                </a>
                ';
            }elseif(isset($_SESSION['employee'])){
                $employee = $_SESSION['employee'];
                $firstname = $employee->getFirstname();
                echo '
                <h1 class="greeting">'."Witaj $firstname!".'</h1>
                <a href="pracownik/dane_pracownika.php" class="form-button">
                    Panel pracownika
                </a>
                <a href="wyloguj.php" class="form-button">
                    Wyloguj
                </a>
                ';
            }else{
                echo '
                <a href="logowanie.php" class="form-button">
                    Zaloguj
                </a>
                <a href="rejestracja.php" class="form-button">
                    Zarejestruj
                </a>
                ';
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
                $coming_movies = comingMovies();
                foreach($coming_movies as $m){
                    $title = $m->getTitle();
                    $release_date = formatDate($m->getDate());
                    $img = $m->posterLink();
                    echo "
                        <div class='comingmovie'> 
                        <div style='background-image: url($img)' class='moviephoto'></div>
                        <b>$title</b> <br>
                        <span class='release-date'>$release_date</span>
                        </div>
                    ";
                }
            ?>
        </div>
        <div class="header" id="showings">
            Repertuar <br> <hr class="hr-break" align="left">
        </div>
        <div class="date-picker">
            <?php 
                if(isset($_GET['date'])){
                    $get_date = date($_GET['date']);
                    if(strtotime($get_date) < strtotime(date("d.m.Y"))){
                        $date = date("d.m.Y");
                    }else{
                        $date = $get_date;
                    }
                }else{
                    $date = date("d.m.Y");
                }
                $dplus = date("d.m.Y", strtotime($date."+ 1 day"));
                $dminus = date("d.m.Y", strtotime($date."- 1 day"));
                if(strtotime($date) > strtotime(date("d.m.Y"))){
                    echo "<a href='index.php?date=$dminus#showings' class='date-button'> < </a>";
                }else{
                    echo "<div class='date-button-unactive date-button'> < </div>";
                }
                echo $date;
                echo "<a href='index.php?date=$dplus#showings' class='date-button'> > </a>";
            ?>
        </div>
        <br>
        <hr class="hr-break" style="width:20%;"><br>
        <?php 
            $date = formatDate($date, 'Y-m-d');
            $showings = getShowings($date);
            if(count($showings) == 0){
                echo "<h2>W tym dniu repertuar jest pusty</h2>";
            }
            foreach($showings as $s){
                $id = $s->getID();
                $movie = $s->getMovie();
                $title = $movie->getTitle();
                $length = $movie->getLength("h");
                $category = $movie->getCategory();
                $description = $movie->getDescription();
                $release_year = formatDate($movie->getDate(), 'Y')."r.";
                $language = $s->getLanguage();
                $image = $movie->posterLink();
                $time = $s->getTime();
                if($date == date('Y-m-d') and strtotime($time) < strtotime(date('H:i'))){
                    continue;
                }
                echo "
                <div class='showing'>
                <div class='showing-img' style='background-image: url($image)'></div>
                <div class='showing-properties'>
                    <span class='showing-title'>$title</span>
                    <span class='showing-details'>$length &nbsp &nbsp $category &nbsp &nbsp $release_year &nbsp &nbsp $language</span>
                    <p class='showing-description'>$description</p>
                </div>
                <div class='showing-column'>
                    <span class='showing-time'>$time</span>
                    <a href='zarezerwuj.php?id=$id' class='showing-button'>Zarezerwuj</a>
                </div>
                </div>
                ";
            }
        ?>
    </div>
</body>
</html>