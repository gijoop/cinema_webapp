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
            if(confirm("Czy na pewno chcesz usunąć seans?")){
                location.replace("usun.php?id_showing_del=" + id);
            }
        }
    </script>
    <?php 
        require_once __DIR__."/../classes/Employee.php";
        require_once __DIR__."/../classes/Showing.php";
        require_once __DIR__."/../func/functions.php";
        require_once __DIR__."/../func/main.php";
        session_start();

        if(!isset($_SESSION['employee'])){
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
                Zarządzanie seansami <br> <hr class="hr-break">
            </div>
            <form action="dodaj.php" method="POST">
                <select name="movie_id" placeholder="Film" class="panel-input">
                    <?php addSelectMovies() ?>
                </select>
                <input type="date" name="date" placeholder="Data" class="panel-input"> 
                <input type="time" name="time" placeholder="Godzina" class="panel-input">
                <select name="hall_id" placeholder="Kategoria" class="panel-input">
                    <?php addSelectHalls() ?>
                </select>
                <select name="language_id" placeholder="Kategoria" class="panel-input">
                    <?php addSelectLanguages() ?>
                </select><br><br>
                <input type="submit" name="submit_showing" class="panel-input-submit" value="Dodaj seans"><br><br>
            </form>
            <?php 
                require __DIR__."/../connect.php";
                $sql = "SELECT id FROM showing ORDER BY date DESC";
                $results = $conn->query($sql)->fetch_all();
                echo "<table class='panel-movies' border='1'>";
                echo "<tr>";
                echo "<th>Film</th>";
                echo "<th>Data</th>";
                echo "<th>Godzina</th>";
                echo "<th>Sala</th>";
                echo "<th>Język</th>";
                echo "<th>Akcje</th>";
                echo "</tr>";
                foreach($results as $r){
                    $showing = new Showing($r[0]);
                    $id = $showing->getID();
                    echo "<tr>";
                        echo "<td>".$showing->getMovie()->getTitle()."</td>";
                        echo "<td>".formatDate($showing->getDate())."</td>";
                        echo "<td>".$showing->getTime()."</td>";
                        echo "<td>".$showing->getHall()."</td>";
                        echo "<td>".$showing->getLanguage()."</td>";
                        echo "<td>
                                <a href='edytuj_seans.php?id=$id' style='color: lightgreen; cursor:pointer'>Edytuj</a>
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