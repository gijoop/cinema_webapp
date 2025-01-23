<?php 
require_once __DIR__."/../classes/User.php";
require_once __DIR__."/../classes/Showing.php";
require_once __DIR__."/../classes/Movie.php";
require_once __DIR__."/../classes/DBHelper.php";

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$user = $_SESSION['user'];

if(!$user->isEmployee()){
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['submit_showing'])) {
    $movie_id = $_POST['movie_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $room_id = $_POST['room_id'];
    $language_id = $_POST['language_id'];

    DBHelper::executeQuery(
        "INSERT INTO showing (movie_id, date, time, room_id, language_id) VALUES (?, ?, ?, ?, ?)",
        [$movie_id, $date, $time, $room_id, $language_id]
    );
    header("Location: showings.php");
    exit();

}

if (isset($_POST['delete_showing'])) {
    $showing_id = $_POST['showing_id'];

    DBHelper::executeQuery("DELETE FROM ticket WHERE showing_id = ?", [$showing_id]);
    DBHelper::executeQuery("DELETE FROM showing WHERE id = ?", [$showing_id]);
    header("Location: showings.php");
    exit();
}

function addSelectOptions($table, $name, $value) {
    $options = DBHelper::executeQuery("SELECT $name, $value FROM $table")->fetch_all(MYSQLI_ASSOC);
    foreach ($options as $option) {
        echo "<option value='{$option[$value]}'>{$option[$name]}</option>";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../images/icon.ico" type="image/x-icon">
    <title>Sieć kin Omega</title>
    <script>
        function checkDelete(id) {
            if (confirm("Czy na pewno chcesz usunąć seans? Usuwając seans usuniesz również wszystkie rezerwacje z nim związane!")) {
                document.getElementById('delete_showing_id').value = id;
                document.getElementById('delete_form').submit();
            }
        }
    </script>
</head>
<body>
    <div class="top-panel">
        <div class="top-box" style="justify-content: left;">
            <img src="../images/logo.png" width="50%">
        </div>
        <div class="top-box" style="justify-content: right; width:40%;">
            <a href="../logout.php" class="form-button">Wyloguj</a>
            <a href="../index.php" class="form-button">Strona główna</a>
        </div>
    </div>
    
    <div class="panel">
        <?php 
        require_once 'menu.php'; 
        ?>
        <div class="panel-tab">
            <div class="panel-tab-header">
                Zarządzanie seansami <br> <hr class="hr-break">
            </div>
            <form action="showings.php" method="POST">
                <select name="movie_id" placeholder="Film" class="panel-input">
                    <?php addSelectOptions('movie', 'title', 'id'); ?>
                </select>
                <input type="date" name="date" placeholder="Data" class="panel-input" required> 
                <input type="time" name="time" placeholder="Godzina" class="panel-input" required>
                <select name="room_id" placeholder="Sala" class="panel-input">
                    <?php addSelectOptions('room', 'id', 'id'); ?>
                </select>
                <select name="language_id" placeholder="Język" class="panel-input">
                    <?php addSelectOptions('language', 'name', 'id'); ?>
                </select><br><br>
                <input type="submit" name="submit_showing" class="panel-input-submit" value="Dodaj seans"><br><br>
            </form>
            <form id="delete_form" action="showings.php" method="POST" style="display: none;">
                <input type="hidden" name="showing_id" id="delete_showing_id">
                <input type="hidden" name="delete_showing" value="1">
            </form>
            <?php 
            $showings = DBHelper::executeQuery("SELECT id FROM showing ORDER BY date DESC")->fetch_all(MYSQLI_ASSOC);
            echo "<table class='panel-movies' border='1'>";
            echo "<tr>";
            echo "<th>Film</th>";
            echo "<th>Data</th>";
            echo "<th>Godzina</th>";
            echo "<th>Sala</th>";
            echo "<th>Język</th>";
            echo "<th>Akcje</th>";
            echo "</tr>";
            foreach ($showings as $showingData) {
                $showing = new Showing($showingData['id']);
                $id = $showing->getID();
                echo "<tr>";
                    echo "<td>".$showing->getMovie()->getTitle()."</td>";
                    echo "<td>".$showing->getDate()."</td>";
                    echo "<td>".$showing->getTime()."</td>";
                    echo "<td>".$showing->getRoom()."</td>";
                    echo "<td>".$showing->getLanguage()."</td>";
                    echo "<td>
                            <a href='edit_showing.php?id=$id' style='color: lightgreen; cursor:pointer'>Edytuj</a>
                            <span style='color: red; cursor:pointer' onClick='checkDelete($id)'>Usuń</span>
                        </td>";
                echo "</tr>";
            }
            echo "</table>";
            ?>
        </div>
    </div>
</body>
</html>