<?php
require_once "classes/DBHelper.php";
session_start();
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
    <title>Rejestracja | Omega</title>
</head>
<body>
    <div class="container">
        <div class="logo-box">
            <a href="index.php" style="text-align: center;"><img src="images/logo.png" width="40%"></a>
        </div>
        <div class="logging-panel">
            <?php
            if (!isset($_SESSION['register_success'])) {
                echo '
                <h1 class="ltitle"> Zarejestruj się </h1><br>
                <form action="register.php" method="POST">
                    <input type="text" class="login-input" placeholder="Login" name="login"> <br>
                    <input type="text" class="login-input" placeholder="E-mail" name="email"> <br>
                    <input type="text" class="login-input" placeholder="Imię" name="firstname"> <br>
                    <input type="text" class="login-input" placeholder="Nazwisko" name="lastname"> <br>
                    <input type="password" class="login-input" placeholder="Hasło" name="password"><br><br>
                    <input type="submit" class="login-submit" value="Zarejestruj się" name="submit">
                </form>';
            } else {
                echo '
                <text style="font-size:26px;">Rejestracja zakończona pomyślnie!</text><br><br><br><br>
                <a href="login.php"><div class="login-submit">Zaloguj się</div></a>';
                unset($_SESSION['register_success']);
            }

            if (isset($_POST['submit'])) {
                $login = htmlentities($_POST['login'], ENT_QUOTES, "UTF-8");
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $firstname = mb_convert_case(mb_strtolower($_POST['firstname'], 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                $lastname = mb_convert_case(mb_strtolower($_POST['lastname'], 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                $password = $_POST['password'];

                $validated = true;

                // Validation
                if (!$login) {
                    echo '<h1 class="error">Nie podano loginu</h1>';
                    $validated = false;
                } elseif (mb_strlen($login) < 4 || mb_strlen($login) > 20) {
                    echo '<h1 class="error">Login powinien mieć od 4 do 20 znaków</h1>';
                    $validated = false;
                } elseif (!ctype_alnum($login)) {
                    echo '<h1 class="error">Login powinien się składać tylko z liter a-Z bez polskich znaków i cyfr 0-9</h1>';
                    $validated = false;
                } elseif (DBHelper::executeQuery("SELECT id FROM user WHERE login = ?", [$login])->num_rows > 0) {
                    echo '<h1 class="error">Podany login już istnieje</h1>';
                    $validated = false;
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo '<h1 class="error">Nieprawidłowy adres e-mail</h1>';
                    $validated = false;
                } elseif (!$firstname || !preg_match('/^[a-zA-ZąĄęóÓłŁśŚżŻźŹćĆńŃ]+$/u', $firstname)) {
                    echo '<h1 class="error">Nieprawidłowe imię</h1>';
                    $validated = false;
                } elseif (!$lastname || !preg_match('/^[a-zA-ZąĄęóÓłŁśŚżŻźŹćĆńŃ]+$/u', $lastname)) {
                    echo '<h1 class="error">Nieprawidłowe nazwisko</h1>';
                    $validated = false;
                } elseif (!$password || mb_strlen($password) <= 6) {
                    echo '<h1 class="error">Hasło powinno być dłuższe niż 6 znaków!</h1>';
                    $validated = false;
                }

                // Registration
                if ($validated) {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    DBHelper::executeQuery(
                        "INSERT INTO user (login, password, firstname, lastname, email, role) VALUES (?, ?, ?, ?, ?, 'CUSTOMER')",
                        [$login, $password_hash, $firstname, $lastname, $email]
                    );
                    $_SESSION['register_success'] = true;
                    header("Location: register.php");
                    exit();
                }
            } else {
                echo '<h1 class="error">&nbsp</h1>';
            }
            ?>
        </div>
    </div>
</body>
</html>
