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
    <?php
        require_once __DIR__."/../connect.php";
        require_once __DIR__."/../classes/Employee.php";
        session_start();
    ?>
</head>
<body>
    <div class="container">
        <div class="logo-box">
            <a href="../index.php" style="text-align: center;"><img src="../images/logo.png" width="40%"></a>
        </div>
        <div class="logging-panel">
            <h1 class="ltitle"> Panel pracownika </h1><br>
            <form action="index.php" method="POST">
                <input type="text" class="login-input" placeholder="Login" name="login"> <br>
                <input type="password" class="login-input" placeholder="Hasło" name="password"><br><br>
                <input type="submit" name="submit" class="login-submit" value="Zaloguj się">
            </form>
            <?php 
                if(isset($_SESSION['employee']))
                {
                    header("Location: dane_pracownika.php");
                }
                if(isset($_POST['submit'])){
                    $login = htmlentities($_POST['login'], ENT_QUOTES, "UTF-8");
                    $password = $_POST['password'];

                    $query = sprintf("SELECT * FROM employee WHERE employee.login = '%s'", mysqli_real_escape_string($conn, $login));
                    $result = $conn->query($query);
                    
                    if($result->num_rows == 1){
                        $result_assoc = $result->fetch_assoc();
                        $employee_id = $result_assoc['id'];
                        $hash_password = $result_assoc['password'];
                        if(password_verify($password, $hash_password)){
                            session_unset();
                            $_SESSION['employee'] = new Employee($employee_id);
                            header("Location: dane_pracownika.php");
                        }else{
                            echo '<h1 class="error"> Niepoprawny login lub hasło! </h1>';
                        }
                    }else{
                        echo '<h1 class="error"> Niepoprawny login lub hasło! </h1>';
                    }
                }else{
                    echo '<h1 class="error"> &nbsp </h1>';
                }
            ?>
        </div>
    </div>
</body>
</html>