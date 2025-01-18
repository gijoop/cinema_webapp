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
        require_once __DIR__."/../classes/Movie.php";
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
                    $movie = new Movie($_GET['id']);
                    $_SESSION['movie_id'] = $movie->getID();
                    
                    echo
                    '
                    <div class="panel-tab-header">
                    Edytuj film <br> <hr class="hr-break">
                    </div>
                    <form action="edytuj_film.php" method="POST" enctype="multipart/form-data">
                        <textarea name="title" class="panel-input" cols="50" rows="1" maxlength="50">'.$movie->getTitle().'</textarea><br>
                        <textarea name="description" class="panel-input" cols="50" rows="10" maxlength="1000">'.$movie->getDescription().'</textarea><br>
                        <select name="category" class="panel-input">
                            <option>'.$movie->getCategory().'</option>
                    ';
                    addSelectCategories();
                    echo
                    '
                        </select>
                        <input type="text" name="length" value="'.$movie->getLength().'" class="panel-input">
                        <input type="date" name="date" value="'.$movie->getDate().'" class="panel-input"> <br><br>
                        <input type="file" name="poster" class="panel-input"> <br><br>
                        <input type="submit" accept=".jpg, .png" name="submit" class="panel-input-submit" value="Edytuj"><br><br>
                    </form>
                    ';
                }elseif(isset($_POST['submit'])){
                    $id = $_SESSION['movie_id'];
                    unset($_SESSION['movie_id']);
                    $title = $_POST['title'];
                    $description = $_POST['description'];
                    $category = new Category($_POST['category']);
                    $category_id = $category->getID();
                    $length = $_POST['length'];
                    $date = $_POST['date'];
                    if($_FILES['poster']['name']){
                        var_dump($_FILES['poster']);
                        $poster = $_FILES['poster'];
                        $edited_movie = new createdMovie($title,$description,$category->getName(),$length,$date,$poster);
                        try{
                            $edited_movie->update($id);
                        }catch(Exception $e){
                            setError($e->getMessage());
                        }
                        header("Location: filmy.php");
                    }else{
                        $edited_movie = new createdMovie($title,$description,$category->getName(),$length,$date,NULL);
                        try{
                            $edited_movie->updateWithoutPoster($id);
                        }catch(Exception $e){
                            setError($e->getMessage());
                        }
                        header("Location: filmy.php");
                    }
                }
            ?>
        </div>
    </div>
</body>
</html>