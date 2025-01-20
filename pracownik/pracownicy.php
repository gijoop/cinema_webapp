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
    <script>
        function checkDelete(id){
            if(confirm("Czy na pewno chcesz usunąć pracownika?")){
                location.replace("usun.php?id_employee_del=" + id);
            }
        }
    </script>
    <?php 
        require_once __DIR__."/../classes/Employee.php";
        require_once __DIR__."/../func/functions.php";
        require_once __DIR__."/../func/main.php";
        session_start();

        if(!isset($_SESSION['employee']) or !$_SESSION['employee']->isAdmin()){
            header("Location: index.php");
        }
        $employee = $_SESSION['employee'];
        catchError();
    ?>
</head>
<body>
    <div class="top-panel">
        <div class="top-box" style="justify-content: left;">
            <img src="../images/logo.png" width="50%">
        </div>
        <div class="top-box" style="justify-content: right; width:40%;">
            <a href="../logout.php" class="form-button">
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
                Zarządzanie pracownikami <br> <hr class="hr-break">
            </div>
            <form action="dodaj.php" method="POST">
                <input type="text" name="login" placeholder="Login" class="panel-input"> 
                <input type="text" name="firstname" placeholder="Imię" class="panel-input"> 
                <input type="text" name="secondname" placeholder="Nazwisko" class="panel-input"> <br><br>
                <input type="email" name="email" placeholder="E-mail" class="panel-input"> 
                <input type="text" name="phone" placeholder="Numer telefonu" class="panel-input"> <br><br>
                <input type="text" name="adress" placeholder="Adres" class="panel-input"> 
                <input type="text" name="city" placeholder="Miasto" class="panel-input"> <br><br>
                <label>Data zatrudnienia  </label>
                <input type="date" name="hiredate" placeholder="Data zatrudnienia" class="panel-input"> <br>
                <label>Uprawnienia administratora  </label>
                <select name="isadmin" class="panel-input">
                    <option value="0">Nie</option>
                   <option value="1">Tak</option>
                </select><br><br>
                <input type="submit" name="submit_employee" class="panel-input-submit" value="Dodaj pracownika"><br><br>
            </form>
            <?php 
                require __DIR__."/../connect.php";
                $sql = "SELECT id FROM employee ORDER BY hire_date DESC";
                $results = $conn->query($sql)->fetch_all();
                echo "<table class='panel-movies' border='1'>";
                echo "<tr>";
                echo "<th>Login</th>";
                echo "<th>Imię</th>";
                echo "<th>Nazwisko</th>";
                echo "<th>E-mail</th>";
                echo "<th>Numer telefonu</th>";
                echo "<th>Adres</th>";
                echo "<th>Miasto</th>";
                echo "<th>Data zatrudnienia</th>";
                echo "<th>Uprawnienia admina</th>";
                echo "<th>Akcje</th>";
                echo "</tr>";
                foreach($results as $r){
                    $em = new Employee($r[0]);
                    $id = $em->getID();
                    echo "<tr>";
                        echo "<td>".$em->getLogin()."</td>";
                        echo "<td>".$em->getFirstname()."</td>";
                        echo "<td>".$em->getLastname()."</td>";
                        echo "<td>".$em->getEmail()."</td>";
                        echo "<td>".$em->getNumber()."</td>";
                        echo "<td>".$em->getAdress()."</td>";
                        echo "<td>".$em->getCity()."</td>";
                        echo "<td>".$em->getHireDate()."</td>";
                        echo "<td>".$em->isAdmin()."</td>";
                        echo "<td>
                                <a href='edytuj_pracownika.php?id=$id' style='color: lightgreen; cursor:pointer'>Edytuj</a>
                                <span style='color: red; cursor:pointer' onClick='checkDelete($id)'>Usuń</span>
                            </td>";
                    echo "</tr>";
                }
                echo "</table>";
            ?>
        </div>
    </div>
</body>
</html>