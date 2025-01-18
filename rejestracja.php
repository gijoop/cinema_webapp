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
    <?php
        require_once "connect.php";
        session_start();
    ?>
</head>
<body>
    <div class="container">
        <div class="logo-box">
            <a href="index.php" style="text-align: center;"><img src="images/logo.png" width="40%"></a>
        </div>
        <div class="logging-panel">
            <?php
            if(!isset($_SESSION['register_success'])){
                echo 
                '<h1 class="ltitle"> Zarejestruj się </h1><br>
                <form action="rejestracja.php" method="POST">
                    <input type="text" class="login-input" placeholder="Login" name="login"> <br>
                    <input type="text" class="login-input" placeholder="E-mail" name="email"> <br>
                    <input type="text" class="login-input" placeholder="Imię" name="firstname"> <br>
                    <input type="text" class="login-input" placeholder="Nazwisko" name="lastname"> <br>
                    <input type="password" class="login-input" placeholder="Hasło" name="password"><br><br>
                    <input type="submit" class="login-submit" value="Zarejestruj się" name="submit">
                </form>';
            } else{
                echo 
                '<text style="font-size:26px;">Rejestracja zakończona pomyślnie!</text><br><br><br><br>
                <a href="logowanie.php"><div class="login-submit">Zaloguj się</div></a>
                ';
                unset($_SESSION['register_success']);
            }
            
            if(isset($_POST['submit'])){
                $login = $_POST['login'];
                $email = $_POST['email'];
                $firstname = mb_convert_case(mb_strtolower($_POST['firstname'], 'UTF-8'), MB_CASE_TITLE, 'UTF-8'); //Zmienia string na małe litery, po czym powiększa pierwszy znak
                $lastname = mb_convert_case(mb_strtolower($_POST['lastname'], 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                $password = $_POST['password'];
                $date = date("Y-m-d");

                #region Data validation
                $validated = true;
                $vEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
                $vFirstname = filter_var($firstname, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $nameReg = '/^[a-zA-ZąĄęóÓłŁśŚżŻźŹćĆńŃ]+$/';
                $check_login = $conn->query("SELECT * FROM user WHERE user.login = '$login';");
                if($login == NULL){
                    $validated = false;
                    echo '<h1 class="error">Nie podano loginu</h1>';
                } elseif(mb_strlen($login) < 4 or mb_strlen($login) > 20){
                    $validated = false;
                    echo '<h1 class="error">Login powinien mieć od 4 do 20 znaków</h1>';
                } elseif(!ctype_alnum($login)){
                    $validated = false;
                    echo '<h1 class="error">Login powinien się składać tylko z <br> liter a-Z bez polskich znaków i cyfr 0-9</h1>';
                } elseif(mysqli_num_rows($check_login) > 0) {
                    $validated = false;
                    echo '<h1 class="error">Podany login już istnieje</h1>';
                } elseif(!filter_var($vEmail, FILTER_VALIDATE_EMAIL) or $email != $vEmail){
                    $validated = false;
                    echo '<h1 class="error">Nieprawidłowy adres e-mail</h1>';
                } elseif($firstname == NULL OR !preg_match($nameReg, $firstname)){
                    $validated = false;
                    echo '<h1 class="error">Nieprawidłowe imię</h1>';
                } elseif($lastname == NULL OR !preg_match($nameReg, $lastname)){
                    $validated = false;
                    echo '<h1 class="error">Nieprawidłowe nazwisko</h1>';
                } elseif(mb_strlen($password) == NULL){
                    $validated = false;
                    echo '<h1 class="error">Nieprawidłowe hasło!</h1>';
                } elseif(mb_strlen($password) <= 6){
                    $validated = false;
                    echo '<h1 class="error">Hasło powinno być dłuższe niż 6 znaków!</h1>';
                }
                #endregion

                if($validated){
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $query = "INSERT INTO user (login, password, firstname, lastname, email, date_created) VALUES ('$login', '$password_hash', '$firstname', '$lastname', '$email', '$date')";
                    mysqli_query($conn, $query);
                    $_SESSION['register_success'] = true;
                    header("Location: rejestracja.php");
                }              
            } else{
                echo '<h1 class="error">&nbsp</h1>';
            }
            ?>
        </div>
    </div>
</body>
</html>