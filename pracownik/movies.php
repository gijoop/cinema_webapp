<?php 
require_once __DIR__."/../classes/Employee.php";
require_once __DIR__."/../classes/Movie.php";
require_once __DIR__."/../classes/Category.php";
require_once __DIR__."/../classes/DBHelper.php";
session_start();

if (!isset($_SESSION['employee'])) {
    header("Location: index.php");
    exit();
}
$employee = $_SESSION['employee'];

if (isset($_POST['submit_movie'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $length = $_POST['length'];
    $date = $_POST['date'];
    $poster = $_FILES['poster'];

    try {
        $poster_name = null;
        if ($poster['name']) {
            $poster_name = time() . "_" . basename($poster['name']);
            move_uploaded_file($poster['tmp_name'], "../posters/" . $poster_name);
        }
        DBHelper::executeQuery(
            "INSERT INTO movie (title, description, category_id, length, release_date, poster_name) VALUES (?, ?, ?, ?, ?, ?)",
            [$title, $description, $category_id, $length, $date, $poster_name]
        );
        header("Location: movies.php");
        exit();
    } catch (Exception $e) {
        $error_message = "Wystąpił błąd z bazą danych!";
    }
}

if (isset($_POST['delete_movie'])) {
    $movie_id = $_POST['movie_id'];

    try {
        DBHelper::executeQuery("DELETE FROM movie WHERE id = ?", [$movie_id]);
        header("Location: movies.php");
        exit();
    } catch (Exception $e) {
        $error_message = "Wystąpił błąd z bazą danych!";
    }
}

function addSelectCategories() {
    $categories = DBHelper::executeQuery("SELECT id, name FROM category")->fetch_all(MYSQLI_ASSOC);
    foreach ($categories as $category) {
        echo "<option value='{$category['id']}'>{$category['name']}</option>";
    }
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
    <title>Sieć kin Omega</title>
    <script>
        function checkDelete(id) {
            if (confirm("Czy na pewno chcesz usunąć film?")) {
                document.getElementById('delete_movie_id').value = id;
                document.getElementById('delete_form').submit();
            }
        }
    </script>
</head>
<body>
    <div class="top-panel">
        <div class="top-box" style="justify-content: left;">
            <img src="../images/logo.png" width="50%">
        </div>
        <div class="top-box" style="justify-content: right; width:40%;">
            <a href="../logout.php" class="form-button">Wyloguj</a>
            <a href="../index.php" class="form-button">Strona główna</a>
        </div>
    </div>
    
    <div class="panel">
        <?php 
        require_once 'menu.php'; 
        ?>
        <div class="panel-tab">
            <div class="panel-tab-header">
                Zarządzanie filmami <br> <hr class="hr-break">
            </div>
            <form action="movies.php" method="POST" enctype="multipart/form-data">
                <textarea name="title" placeholder="Tytuł" class="panel-input" style="width:100%" rows="1" maxlength="50"></textarea><br>
                <textarea name="description" placeholder="Opis" class="panel-input" style="width:100%" rows="3" maxlength="1000"></textarea><br>
                <select name="category_id" placeholder="Kategoria" class="panel-input">
                    <?php addSelectCategories(); ?>
                </select>
                <input type="text" name="length" placeholder="Długość" class="panel-input">
                <input type="date" name="date" placeholder="Data" class="panel-input"> <br><br>
                <input type="file" name="poster" placeholder="Plakat" class="panel-input"> <br><br>
                <input type="submit" accept=".jpg, .png" name="submit_movie" class="panel-input-submit" value="Dodaj film"><br><br>
            </form>
            <form id="delete_form" action="movies.php" method="POST" style="display: none;">
                <input type="hidden" name="movie_id" id="delete_movie_id">
                <input type="hidden" name="delete_movie" value="1">
            </form>
            <?php 
            $movies = DBHelper::executeQuery("SELECT id FROM movie")->fetch_all(MYSQLI_ASSOC);
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
            foreach ($movies as $movieData) {
                $movie = new Movie($movieData['id']);
                $id = $movie->getID();
                echo "<tr>";
                    echo "<td>".$movie->getTitle()."</td>";
                    echo "<td>".$movie->getDescription()."</td>";
                    echo "<td>".$movie->getCategory()."</td>";
                    echo "<td>".$movie->getLength()."</td>";
                    echo "<td style='white-space: nowrap'>".$movie->getDate()."</td>";
                    echo "<td><a href='".$movie->getPosterLink()."' target='_blank'>Link</a></td>";
                    echo "<td>
                            <a href='edit_movie.php?id=$id' style='color: lightgreen; cursor:pointer'>Edytuj</a>
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