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
    <?php 
        require_once __DIR__."/../classes/Employee.php";
        require_once __DIR__."/../func/main.php";
        session_start();

        if(!isset($_SESSION['employee'])){
            header("Location: index.php");
        }
        $employee = $_SESSION['employee'];
    ?>
</head>
<body>
    <div class="top-panel">
        <div class="top-box" style="justify-content: left;">
            <img src="../images/logo.png" width="50%">
        </div>
        <div class="top-box" style="justify-content: right; width:40%;">
            <a href="../wyloguj.php" class="form-button">
                Wyloguj
            </a>
            <a href="../index.php" class="form-button">
                Strona główna
            </a>
        </div>
    </div>
    
    <div class="panel">
        <?php 
            if($employee->isAdmin()){
                require_once 'menu_admin.php';
            }else{
                require_once 'menu.php'; 
            }
        ?>
        <div class="panel-tab">
            <div class="panel-tab-header">
            Dane pracownika <br> <hr class="hr-break">
            </div>

            <table class="panel-table">
            <tr>
                <td class="table-label">Imię</td>
                <td><?php echo $employee->getFirstname() ?> <br></td>
            </tr>
            <tr>
                <td class="table-label">Nazwisko</td>
                <td><?php echo $employee->getLastname() ?> <br></td>
            </tr>
            <tr>
                <td class="table-label">Login/nickname</td>
                <td><?php echo $employee->getLogin() ?> <br></td>
            </tr>
            <tr>
                <td class="table-label">Adres E-mail</td>
                <td><?php echo $employee->getEmail() ?> <br></td>
            </tr>
            <tr>
                <td class="table-label">Numer telefonu</td>
                <td><?php echo $employee->getNumber() ?> <br></td>
            </tr>
            <tr>
                <td class="table-label">Adres zamieszkania</td>
                <td><?php echo $employee->getAdress() ?> <br></td>
            </tr>
            <tr>
                <td class="table-label">Miasto</td>
                <td><?php echo $employee->getCity() ?> <br></td>
            </tr>
            <tr>
                <td class="table-label">Data zatrudnienia</td>
                <td><?php echo formatDate($employee->getHireDate(), "d.m.Y") ?> <br></td>
            </tr>
            </table><br><br>
            <a href="/omega/zmien_haslo.php" class="showing-button">Zmień hasło</a>
        </div>
    </div>
</body>
</html>