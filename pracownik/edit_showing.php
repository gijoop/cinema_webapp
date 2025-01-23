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

if (!isset($_GET['id'])) {
    header("Location: seanse.php");
    exit();
}

$showing_id = $_GET['id'];
$showing = new Showing($showing_id);

if (isset($_POST['submit_edit_showing'])) {
    $movie_id = $_POST['movie_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $room_id = $_POST['room_id'];
    $language_id = $_POST['language_id'];

    try {
        DBHelper::executeQuery(
            "UPDATE showing SET movie_id = ?, date = ?, time = ?, room_id = ?, language_id = ? WHERE id = ?",
            [$movie_id, $date, $time, $room_id, $language_id, $showing_id]
        );
        header("Location: showings.php");
        exit();
    } catch (Exception $e) {
        $error_message = "Wystąpił błąd z bazą danych!";
    }
}

function addSelectOptions($table, $name, $value, $selectedValue) {
    $options = DBHelper::executeQuery("SELECT $name, $value FROM $table")->fetch_all(MYSQLI_ASSOC);
    foreach ($options as $option) {
        $selected = $option[$value] == $selectedValue ? "selected" : "";
        echo "<option value='{$option[$value]}' $selected>{$option[$name]}</option>";
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
                Edytuj seans <br> <hr class="hr-break">
            </div>
            <form action="edit_showing.php?id=<?php echo $showing_id; ?>" method="POST">
                <select name="movie_id" placeholder="Film" class="panel-input">
                    <?php addSelectOptions('movie', 'title', 'id', $showing->getMovie()->getID()); ?>
                </select>
                <input type="date" name="date" placeholder="Data" class="panel-input" value="<?php echo $showing->getDate(); ?>" required> 
                <input type="time" name="time" placeholder="Godzina" class="panel-input" value="<?php echo $showing->getTime(); ?>" required>
                <select name="room_id" placeholder="Sala" class="panel-input">
                    <?php addSelectOptions('room', 'id', 'id', $showing->getRoom()); ?>
                </select>
                <select name="language_id" placeholder="Język" class="panel-input">
                    <?php addSelectOptions('language', 'name', 'id', $showing->getLanguageID()); ?>
                </select><br><br>
                <input type="submit" name="submit_edit_showing" class="panel-input-submit" value="Zapisz zmiany"><br><br>
            </form>
        </div>
    </div>
</body>
</html>