<?php
require_once "classes/DBHelper.php";
require_once "classes/User.php";
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
    <title>Logowanie | Omega</title>
</head>
<body>
    <div class="container">
        <div class="logo-box">
            <a href="index.php" style="text-align: center;"><img src="images/logo.png" width="40%"></a>
        </div>
        <div class="logging-panel">
            <h1 class="ltitle"> Zaloguj się </h1><br>
            <form action="login.php" method="POST">
                <input type="text" class="login-input" placeholder="Login" name="login"> <br>
                <input type="password" class="login-input" placeholder="Hasło" name="password"><br><br>
                <input type="submit" name="submit" class="login-submit" value="Zaloguj się"><br><br>
                <span>Nie masz konta? <a href="register.php" class="register-link"> Zarejestruj się</a></span>
            </form>
            <?php
                if (isset($_POST['submit'])) {
                    $login = htmlentities($_POST['login'], ENT_QUOTES, "UTF-8");
                    $password = $_POST['password'];

                    try {
                        $user = DBHelper::executeQuery("SELECT * FROM user WHERE login = ?", [$login])->fetch_assoc();

                        if ($user) {
                            $hash_password = $user['password'];
                            if (password_verify($password, $hash_password)) {
                                session_unset();
                                $_SESSION['user'] = new User($user['id']);
                                header("Location: index.php");
                                exit();
                            } else {
                                echo '<h1 class="error"> Niepoprawny login lub hasło!</h1>';
                            }
                        } else {
                            echo '<h1 class="error"> Niepoprawny login lub hasło!</h1>';
                        }
                    } catch (Exception $e) {
                        echo '<h1 class="error"> Wystąpił błąd. Spróbuj ponownie później. </h1>';
                    }
                } else {
                    echo '<h1 class="error"> &nbsp </h1>';
                }
            ?>
        </div>
    </div>
</body>
</html>
