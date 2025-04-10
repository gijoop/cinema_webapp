<?php
require_once "classes/DBHelper.php";
require_once "classes/User.php";
require_once "classes/Employee.php";
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
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
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/icon.ico" type="image/x-icon">
    <title>Zmiana hasła | Omega</title>
</head>
<body>
    <div class="container">
        <div class="logo-box">
            <a href="index.php" style="text-align: center;"><img src="images/logo.png" width="40%"></a>
        </div>
        <div class="logging-panel">
            <h1 class="ltitle"> Zmiana hasła </h1><br>
            <form action="change_passwd.php" method="POST">
                <input type="password" class="login-input" placeholder="Stare hasło" name="old"> <br>
                <input type="password" class="login-input" placeholder="Nowe hasło" name="new"><br><br>
                <input type="submit" name="submit" class="login-submit" value="Zmień hasło">
            </form>
            <?php 
            if (isset($_POST['submit'])) {
                $old_check = $_POST['old'];
                $new = $_POST['new'];

                $id = $_SESSION['user']->getID();
                $result = DBHelper::executeQuery("SELECT password FROM user WHERE id = ?", [$id])->fetch_assoc();

                if (!$result || !password_verify($old_check, $result['password'])) {
                    echo '<h1 class="error">Stare hasło się nie zgadza!</h1>';
                    return;
                }

                if (empty($new)) {
                    echo '<h1 class="error">Nieprawidłowe hasło!</h1>';
                    return;
                }

                if (mb_strlen($new) <= 6) {
                    echo '<h1 class="error">Hasło powinno być dłuższe niż 6 znaków!</h1>';
                    return;
                }

                $new_hashed = password_hash($new, PASSWORD_DEFAULT);
                DBHelper::executeQuery("UPDATE user SET password = ? WHERE id = ?", [$new_hashed, $id]);
                session_unset();
                header("Location: login.php");
                exit();
            }
            ?>
            <br>
        </div>
    </div>
</body>
</html>