<?php
require_once __DIR__."/../classes/DBHelper.php";
require_once __DIR__."/../classes/Employee.php";
session_start();

if (isset($_SESSION['employee'])) {
    header("Location: employee_data.php");
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
    <title>Logowanie | Omega</title>
</head>
<body>
    <div class="container">
        <div class="logo-box">
            <a href="../index.php" style="text-align: center;"><img src="../images/logo.png" width="40%"></a>
        </div>
        <div class="logging-panel">
            <h1 class="ltitle"> Panel pracownika </h1><br>
            <form action="index.php" method="POST">
                <input type="text" class="login-input" placeholder="Login" name="login" required> <br>
                <input type="password" class="login-input" placeholder="Hasło" name="password" required><br><br>
                <input type="submit" name="submit" class="login-submit" value="Zaloguj się">
            </form>
            <?php
            if (!isset($_POST['submit'])) {
                return;
            }

            $login = htmlentities($_POST['login'], ENT_QUOTES, "UTF-8");
            $password = $_POST['password'];
            $result = DBHelper::executeQuery("SELECT id, password FROM employee WHERE login = ?", [$login])->fetch_assoc();

            if (!$result) {
                echo '<h1 class="error"> Niepoprawny login lub hasło!</h1>';
                return;
            }

            $hash_password = $result['password'];
        
            if (!password_verify($password, $hash_password)) {
                echo '<h1 class="error"> Niepoprawny login lub hasło!</h1>';
                return;
            }
        
            session_unset();
            $_SESSION['employee'] = new Employee($result['id']);
            header("Location: employee_data.php");
            exit();
            ?>
        </div>
    </div>
</body>
</html>