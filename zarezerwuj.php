<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/icon.ico" type="image/x-icon">
    <title>Sieć kin Omega</title>
    <script>
        var seats = [];
        function book(id){
            if(!seats.includes(id)){
                seats.push(id);
                document.getElementById(id).style = "border: 2px solid white;";
            }else{
                seats.splice(seats.indexOf(id), 1);
                document.getElementById(id).style = "border: 2px solid #00000000;";
            }
            document.getElementById("booked-seats").innerHTML = "";
            if(seats.length > 0){
                for(let i=0; i<seats.length; i++){
                    document.getElementById("booked-seats").innerHTML += "<div class='seat' style='cursor:default'>" + seats[i] + "</div>";
                }
            }else{
                document.getElementById("booked-seats").innerHTML = "Nie wybrano żadnych miejsc";
            }
        }
        function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }
        function redirectBooking(){
            document.cookie = "booked_seats=" + seats + "; path=/";
            location.reload();
        }
    </script>
    <?php 
        require_once "classes/User.php";
        require_once "classes/Showing.php";
        require_once "func/main.php";
        require "connect.php";
        session_start();
        if(!isset($_SESSION['user'])){
            header("Location: login.php");
        }
        catchError();
        $showing_id = $_GET['id'];
        $user = $_SESSION['user'];

        if(isset($_COOKIE['booked_seats'])){
            $booked_seats = explode(",",$_COOKIE['booked_seats']);
            unset($_COOKIE['booked_seats']);
            setcookie("booked_seats", "", time() - 3600, "/");
            $user_id = $user->getID();
            foreach($booked_seats as $seat){
                $check = "SELECT * FROM ticket WHERE showing_id = $showing_id AND seat_number = $seat;";
                if($conn->query($check)->num_rows == 0){
                    $sql = "INSERT INTO ticket (showing_id, user_id, seat_number) VALUES ($showing_id, $user_id, $seat)";
                    if($conn->query($sql)){
                        echo '<script> alert("Pomyślnie zarezerwowano siedzenie/a!"); </script>';
                        header("Location: user_panel.php");
                    }else{
                        setError("Wystąpił problem z tranzakcją! Spróbuj ponownie później");
                        header("Location: zarezerwuj.php");
                    }
                }else{
                    header("Location: index.php");
                }
            }
        }
    ?>
</head>
<body>
    <div class="top-panel">
        <div class="top-box" style="justify-content: left;">
            <img src="images/logo.png" width="50%">
        </div>
        <div class="top-box" style="justify-content: right; width:40%;">
            <a href="logout.php" class="form-button">
                Wyloguj
            </a>
            <a href="index.php" class="form-button">
                Strona główna
            </a>
        </div>
    </div>
    
    <div class="panel">
        <div class="seat-section">
            <span class="booking-title"> Wybierz miejsca </span>
            <div class='screen'>EKRAN</div>
            <?php 
                $showing = new Showing($showing_id);
                $movie = $showing->getMovie();
                $title = $movie->getTitle();
                $category = $movie->getCategory();
                $length = $movie->getLength('h');
                $year = formatDate($movie->getDate(), 'Y');
                $language = $showing->getLanguage();
                $desc = $movie->getDescription();
                $time = $showing->getTime();
                $date = formatDate($showing->getDate())."r.";
                $seats_num = $showing->getSeatsNum();
                $sql = "SELECT seat_number FROM ticket WHERE showing_id = $showing_id";
                $occupied_seats = $conn->query($sql)->fetch_all();
                //Changes nested arrays into integers
                for($i=0; $i<count($occupied_seats); $i++){
                    $occupied_seats[$i] = $occupied_seats[$i][0];
                }
                //Print out all seats
                for($i=1; $i<=$seats_num; $i++){
                    if($i % 10 == 1) echo "<div class='seat-row'>";
                    if(in_array($i, $occupied_seats)){
                        echo "<div class='seat seat-unactive'>$i</div>";
                    }else{
                        echo "<div id='$i' class='seat' onclick='book($i)'>$i</div>";
                    }
                    if($i % 10 == 0) echo "</div>";
                }
            ?>
        </div>
        <div class="seat-section">
            <span class="booking-title"><?php echo $title; ?></span>
            <span class="booking-details"><?php echo $category."&nbsp &nbsp &nbsp".$length."&nbsp &nbsp &nbsp".$year."r."."&nbsp &nbsp &nbsp".$language; ?></span>
            <span class="booking-details" style="font-size: 19px"><?php echo $desc; ?></span>
            <span class="booking-title"><?php echo $date." ".$time; ?></span><br><br><br>
            <span class="booking-details"> Wybrane miejsce/a </span>
            <div class="seat-row" id="booked-seats">Nie wybrano żadnych miejsc</div>
            <div class="booking-button" onclick="redirectBooking()">Rezerwuj</div>
        </div>
    </div>
</body>
</html>