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
            if(confirm("Czy na pewno chcesz usunąć film?")){
                location.replace("usun.php?id_movie_del=" + id);
            }
        }
    </script>
    <?php 
        require_once __DIR__."/../classes/Employee.php";
        require_once __DIR__."/../classes/Movie.php";
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
            Zarządzanie filmami <br> <hr class="hr-break">
            </div>
            <form action="dodaj.php" method="POST" enctype="multipart/form-data">
                <textarea name="title" placeholder="Tytuł" class="panel-input" style = "width:100%" rows="1" maxlength="50"></textarea><br>
                <textarea name="description" placeholder="Opis" class="panel-input"style = "width:100%" rows="3" maxlength="1000"></textarea><br>
                <select name="category_id" placeholder="Kategoria" class="panel-input">
                    <?php addSelectCategories() ?>
                </select>
                <input type="text" name="length" placeholder="Długość" class="panel-input">
                <input type="date" name="date" placeholder="Data" class="panel-input"> <br><br>
                <input type="file" name="poster" placeholder="Plakat" class="panel-input"> <br><br>
                <input type="submit" accept=".jpg, .png" name="submit_movie" class="panel-input-submit" value="Dodaj film"><br><br>
            </form>
            <?php 
                require __DIR__."/../connect.php";
                $sql = "SELECT movie.id FROM movie";
                $results = $conn->query($sql)->fetch_all();
                echo "<table class='panel-movies' border='1'>";
                echo "<tr>";
                echo "<th>Tytuł</th>";
                echo "<th>Opis</th>";
                echo "<th>Kategoria</th>";
                echo "<th>Długość</th>";
                echo "<th>Data premiery</th>";
                echo "<th>Plakat</th>";
                echo "<th>Akcje</th>";
                echo "</tr>";
                foreach($results as $r){
                    $movie = new Movie($r[0]);
                    $id = $movie->getID();
                    echo "<tr>";
                        echo "<td>".$movie->getTitle()."</td>";
                        echo "<td>".$movie->getDescription()."</td>";
                        echo "<td>".$movie->getCategory()."</td>";
                        echo "<td>".$movie->getLength()."</td>";
                        echo "<td style='white-space: nowrap'>".formatDate($movie->getDate())."</td>";
                        echo "<td><a href='".$movie->posterLink()."' target='_blank'>Link</a></td>";
                        echo "<td>
                                <a href='edytuj_film.php?id=$id' style='color: lightgreen; cursor:pointer'>Edytuj</a>
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