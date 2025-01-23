<?php 
require_once __DIR__."/../classes/User.php";
require_once __DIR__."/../classes/Movie.php";
require_once __DIR__."/../classes/Category.php";
require_once __DIR__."/../classes/DBHelper.php";
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$user = $_SESSION['user'];

if(!$user->isEmployee()){
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: movies.php");
    exit();
}

$movie_id = $_GET['id'];
$movie = new Movie($movie_id);

if (isset($_POST['submit_edit_movie'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $length = $_POST['length'];
    $date = $_POST['date'];
    $poster = $_FILES['poster'];

    try {
        $poster_name = $movie->getPosterName();
        if ($poster['name']) {
            $poster_name = time() . "_" . basename($poster['name']);
            move_uploaded_file($poster['tmp_name'], "../posters/" . $poster_name);
        }
        DBHelper::executeQuery(
            "UPDATE movie SET title = ?, description = ?, category_id = ?, length = ?, release_date = ?, poster_name = ? WHERE id = ?",
            [$title, $description, $category_id, $length, $date, $poster_name, $movie_id]
        );
        header("Location: movies.php");
        exit();
    } catch (Exception $e) {
        $error_message = "Wystąpił błąd z bazą danych!";
    }
}

function addSelectCategories($selectedCategoryId) {
    $categories = DBHelper::executeQuery("SELECT id, name FROM category")->fetch_all(MYSQLI_ASSOC);
    foreach ($categories as $category) {
        $selected = $category['id'] == $selectedCategoryId ? "selected" : "";
        echo "<option value='{$category['id']}' $selected>{$category['name']}</option>";
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
                Edytuj film <br> <hr class="hr-break">
            </div>
            <form action="edit_movie.php?id=<?php echo $movie_id; ?>" method="POST" enctype="multipart/form-data">
                <textarea name="title" class="panel-input" cols="50" rows="1" maxlength="50"><?php echo $movie->getTitle(); ?></textarea><br>
                <textarea name="description" class="panel-input" cols="50" rows="10" maxlength="1000"><?php echo $movie->getDescription(); ?></textarea><br>
                <select name="category_id" class="panel-input">
                    <?php addSelectCategories($movie->getCategoryID()); ?>
                </select>
                <input type="text" name="length" value="<?php echo $movie->getLength(); ?>" class="panel-input">
                <input type="date" name="date" value="<?php echo $movie->getDate(); ?>" class="panel-input"> <br><br>
                <input type="file" name="poster" class="panel-input"> <br><br>
                <input type="submit" accept=".jpg, .png" name="submit_edit_movie" class="panel-input-submit" value="Zapisz zmiany"><br><br>
            </form>
        </div>
    </div>
</body>
</html>