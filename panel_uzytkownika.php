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
        require_once "classes/User.php";
        require_once "classes/Movie.php";
        require_once "classes/Showing.php";
        require_once "func/main.php";
        require "connect.php";
        session_start();
        if(!isset($_SESSION['user'])){
            header("Location: logowanie.php");
        }
        $user = $_SESSION['user'];
        $user_id = $user->getID();

        if(isset($_GET['usun']) && $_GET['usun']){
            require "connect.php";
            $sql = "DELETE FROM ticket WHERE ticket.user_id = $user_id AND ticket.showing_id IN (SELECT showing.id FROM showing WHERE showing.date < now())";
            if(!$conn->query($sql)){
                setError("Błąd podczas usuwania filmów");
            }
        }
        catchError();
    ?>
</head>
<body>
    <div class="top-panel">
        <div class="top-box" style="justify-content: left;">
            <img src="images/logo.png" width="50%">
        </div>
        <div class="top-box" style="justify-content: right; width:40%;">
            <a href="wyloguj.php" class="form-button">
                Wyloguj
            </a>
            <a href="index.php" class="form-button">
                Strona główna
            </a>
        </div>
    </div>
    
    <div class="panel" style="width:60%;">
        <div class="panel-tab">
            <div class="panel-tab-header">
                Dane użytkownika <br> <hr class="hr-break">
            </div>
            <table class="panel-table">
                <tr>
                    <td class="table-label">Imię</td>
                    <td><?php echo $user->getFirstname() ?> <br></td>
                </tr>
                <tr>
                    <td class="table-label">Nazwisko</td>
                    <td><?php echo $user->getLastname() ?> <br></td>
                </tr>
                <tr>
                    <td class="table-label">Nazwa użytkownika</td>
                    <td><?php echo $user->getLogin() ?> <br></td>
                </tr>
                <tr>
                    <td class="table-label">Adres E-mail</td>
                    <td><?php echo $user->getEmail() ?> <br></td>
                </tr>
                <tr>
                    <td class="table-label">Założono</td>
                    <td><?php echo $user->getDate() ?> <br></td>
                </tr>
            </table><br><br>
            <a href="zmien_haslo.php" class="showing-button">Zmień hasło</a><br><br>
            <div class="panel-tab-header">
                Moje bilety <br> <hr class="hr-break">
            </div>
            <?php 
                $sql = "SELECT ticket.* FROM ticket, showing WHERE showing.id = ticket.showing_id AND ticket.user_id = $user_id ORDER BY showing.date, showing.time DESC;";
                $results = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                echo "<table class='panel-movies' border='1'>";
                echo "<tr>";
                echo "<th>Film</th>";
                echo "<th>Data</th>";
                echo "<th>Godzina</th>";
                echo "<th>Sala</th>";
                echo "<th>Numer siedzenia</th>";
                echo "<th>Język</th>";
                echo "</tr>";
                foreach($results as $r){
                    $showing = new Showing($r['showing_id']);
                    $movie = $showing->getMovie();
                    $id = $showing->getID();
                    echo "<tr>";
                        echo "<td>".$showing->getMovie()->getTitle()."</td>";
                        echo "<td>".formatDate($showing->getDate())."</td>";
                        echo "<td>".$showing->getTime()."</td>";
                        echo "<td>".$showing->getHall()."</td>";
                        echo "<td>".$r['seat_number']."</td>";
                        echo "<td>".$showing->getLanguage()."</td>";
                    echo "</tr>";
                }
                echo "</table>";
            ?>
            <br>
            <a href="panel_uzytkownika.php?usun=true" class="form-button" style="align-self: flex-end">Usuń stare bilety</a>
        </div>
    </div>
</body>
</html>