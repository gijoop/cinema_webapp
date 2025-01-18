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
    <?php
        require_once "connect.php";
        require_once "classes/User.php";
        require_once "classes/Employee.php";
        session_start();
    ?>
</head>
<body>
    <div class="container">
        <div class="logo-box">
            <a href="index.php" style="text-align: center;"><img src="images/logo.png" width="40%"></a>
        </div>
        <div class="logging-panel">
            <h1 class="ltitle"> Zmiana hasła </h1><br>
            <form action="zmien_haslo.php" method="POST">
                <input type="password" class="login-input" placeholder="Stare hasło" name="old"> <br>
                <input type="password" class="login-input" placeholder="Nowe hasło" name="new"><br><br>
                <input type="submit" name="submit" class="login-submit" value="Zmień hasło">
            </form>
            <?php 
                if(isset($_POST['submit'])){
                    $old_check = $_POST['old'];
                    $new = $_POST['new'];
                    
                    if(isset($_SESSION['employee'])){
                        $id = $_SESSION['employee']->getID();
                        $sql = sprintf("SELECT * FROM employee WHERE employee.id = '%s'", mysqli_real_escape_string($conn, $id));
                    }elseif(isset($_SESSION['user'])){
                        $id = $_SESSION['user']->getID();
                        $sql = sprintf("SELECT * FROM user WHERE user.id = '%s'", mysqli_real_escape_string($conn, $id));
                    }

                    $result = $conn->query($sql)->fetch_assoc();
                    $old = $result['password'];
                    if(password_verify($old_check, $old)){
                        if(mb_strlen($new) == NULL){
                            echo '<h1 class="error">Nieprawidłowe hasło!</h1>';
                        }elseif(mb_strlen($new) <= 6){
                            echo '<h1 class="error">Hasło powinno być dłuższe niż 6 znaków!</h1>';
                        }else{
                            $new_hashed = password_hash($new, PASSWORD_DEFAULT);
                            if(isset($_SESSION['employee'])){
                                $sql_update = "UPDATE employee SET password = '$new_hashed' WHERE id = $id";
                                $conn->query($sql_update);
                                session_unset();
                                header("Location: pracownik/index.php");
                            }elseif(isset($_SESSION['user'])){
                                $sql_update = "UPDATE user SET password = '$new_hashed' WHERE id = $id";
                                $conn->query($sql_update);
                                session_unset();
                                header("Location: logowanie.php");
                            }
                        }
                    }else{
                        echo '<h1 class="error"> Stare hasło się nie zgadza! </h1>';
                    }
                }
            ?>
            <br>
        </div>
    </div>
</body>
</html>