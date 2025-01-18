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
        require_once __DIR__."/../classes/Category.php";
        require_once __DIR__."/../classes/Showing.php";
        require_once __DIR__."/../func/functions.php";
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
            <?php 
                
                if(isset($_GET['id'])){
                    $showing = new Showing($_GET['id']);
                    $_SESSION['showing_id'] = $showing->getID();
                    echo 
                       '<form action="edytuj_seans.php" method="POST">
                        <select name="movie_id" placeholder="Film" class="panel-input">
                            <option value='.$showing->getMovie()->getID().'>'.$showing->getMovie()->getTitle().'</option>';
                            addSelectMovies();
                    echo '</select>
                        <input type="date" name="date" value="'.$showing->getDate().'" class="panel-input"> 
                        <input type="time" name="time" value="'.$showing->getTime().'" class="panel-input">
                        <select name="hall_id" placeholder="Kategoria" class="panel-input">
                            <option>'.$showing->getHall().'</option>';
                            addSelectHalls();
                    echo '</select>
                        <select name="language_id" placeholder="Kategoria" class="panel-input">
                            <option value='.$showing->getLanguageID().'>'.$showing->getLanguage().'</option>';
                            addSelectLanguages();
                    echo '</select><br><br>
                        <input type="submit" name="submit" class="panel-input-submit" value="Edytuj"><br><br>
                    </form>
                    ';
                }elseif(isset($_POST['submit'])){
                    require __DIR__."/../connect.php";
                    $id = $_SESSION['showing_id'];
                    unset($_SESSION['showing_id']);
                    $movie_id = $_POST['movie_id'];
                    $date = $_POST['date'];
                    $time = $_POST['time'];
                    $hall_id = $_POST['hall_id'];
                    $language_id = $_POST['language_id'];
                    $sql = "UPDATE showing SET movie_id=$movie_id, date='$date', time='$time', hall_id=$hall_id, language_id=$language_id WHERE id = $id";
                    if(!$conn->query($sql)){
                        setError("Wystąpił błąd z bazą danych!");
                    }
                    header("Location: seanse.php");

                }
            ?>
        </div>
    </div>
</body>
</html>