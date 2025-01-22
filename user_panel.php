<?php 
require_once "classes/DBHelper.php";
require_once "classes/User.php";
require_once "classes/Movie.php";
require_once "classes/Showing.php";
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

function renderUserInfo($user) {
    return "
    <table class='panel-table'>
        <tr>
            <td class='table-label'>Imię</td>
            <td>{$user->getFirstname()} <br></td>
        </tr>
        <tr>
            <td class='table-label'>Nazwisko</td>
            <td>{$user->getLastname()} <br></td>
        </tr>
        <tr>
            <td class='table-label'>Nazwa użytkownika</td>
            <td>{$user->getLogin()} <br></td>
        </tr>
        <tr>
            <td class='table-label'>Adres E-mail</td>
            <td>{$user->getEmail()} <br></td>
        </tr>
        <tr>
            <td class='table-label'>Założono</td>
            <td>{$user->getDateCreated()} <br></td>
        </tr>
    </table>";
}

function renderUserTickets($userId) {
    $tickets = DBHelper::executeQuery(
        "SELECT showing.date showing_date, 
        showing.time showing_time, 
        showing.hall_id hall_id, 
        language.name language,
        movie.title title,
        ticket.seat_number seat_number
        FROM ticket 
        JOIN showing ON showing.id = ticket.showing_id 
        JOIN movie ON movie.id = showing.movie_id 
        JOIN language ON language.id = showing.language_id
        WHERE ticket.user_id = ? 
        ORDER BY showing.date DESC, showing.time DESC", 
        [$userId]
    )->fetch_all(MYSQLI_ASSOC);

    if (empty($tickets)) {
        return "<p>Brak biletów</p>";
    }

    $output = "<table class='panel-movies' border='1'>
        <tr>
            <th>Film</th>
            <th>Data</th>
            <th>Godzina</th>
            <th>Sala</th>
            <th>Numer siedzenia</th>
            <th>Język</th>
        </tr>";

    foreach ($tickets as $ticket) {
        $output .= "<tr>
            <td>{$ticket['title']}</td>
            <td>{$ticket['showing_date']}</td>
            <td>{$ticket['showing_time']}</td>
            <td>{$ticket['hall_id']}</td>
            <td>{$ticket['seat_number']}</td>
            <td>{$ticket['language']}</td>
        </tr>";
    }

    $output .= "</table>";
    return $output;
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
    <link rel="shortcut icon" href="images/icon.ico" type="image/x-icon">
    <title>Sieć kin Omega</title>
</head>
<body>
    <div class="top-panel">
        <div class="top-box" style="justify-content: left;">
            <img src="images/logo.png" width="50%">
        </div>
        <div class="top-box" style="justify-content: right; width:40%;">
            <a href="logout.php" class="form-button">Wyloguj</a>
            <a href="index.php" class="form-button">Strona główna</a>
        </div>
    </div>
    
    <div class="panel" style="width:60%;">
        <div class="panel-tab">
            <div class="panel-tab-header">
                Dane użytkownika <br> <hr class="hr-break">
            </div>
            <?php echo renderUserInfo($user); ?>
            <br><br>
            <a href="change_passwd.php" class="showing-button">Zmień hasło</a><br><br>
            <div class="panel-tab-header">
                Moje bilety <br> <hr class="hr-break">
            </div>
            <?php echo renderUserTickets($user->getID()); ?>
            <br>
        </div>
    </div>
</body>
</html>