<?php 
require_once "../classes/User.php";
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
        <div class="panel-menu">
            <a href="employee_data.php" class="panel-menu-button">Pracownik</a>
            <a href="showings.php" class="panel-menu-button">Seanse</a>
            <a href="movies.php" class="panel-menu-button">Filmy</a>
        </div>
        <div class="panel-tab">
            <div class="panel-tab-header">
                Dane pracownika <br> <hr class="hr-break">
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
                    <td class="table-label">Login/nickname</td>
                    <td><?php echo $user->getLogin() ?> <br></td>
                </tr>
                <tr>
                    <td class="table-label">Adres E-mail</td>
                    <td><?php echo $user->getEmail() ?> <br></td>
                </tr>
                <tr>
                    <td class="table-label">Data utworzenia</td>
                    <td><?php echo $user->getDateCreated() ?> <br></td>
                </tr>
            </table><br><br><br><br>
            <a href="../change_passwd.php" class="showing-button">Zmień hasło</a>
        </div>
    </div>
</body>
</html>