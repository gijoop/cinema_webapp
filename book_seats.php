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
        function book(id) {
            if (!seats.includes(id)) {
                seats.push(id);
                document.getElementById(id).style.border = "2px solid white";
            } else {
                seats.splice(seats.indexOf(id), 1);
                document.getElementById(id).style.border = "2px solid transparent";
            }
            document.getElementById("booked-seats").innerHTML = seats.length > 0 ? seats.map(seat => `<div class='seat' style='cursor:default'>${seat}</div>`).join('') : "Nie wybrano żadnych miejsc";
        }
        function redirectBooking() {
            document.cookie = "booked_seats=" + seats.join(",") + "; path=/";
            location.reload();
        }
    </script>
    <?php 
        require_once "classes/User.php";
        require_once "classes/Showing.php";
        require_once "classes/DBHelper.php";
        session_start();

        if (!isset($_SESSION['user'])) {
            header("Location: login.php");
            exit();
        }

        $showing_id = $_GET['id'];
        $user = $_SESSION['user'];

        if (isset($_COOKIE['booked_seats'])) {
            $booked_seats = explode(",", $_COOKIE['booked_seats']);
            setcookie("booked_seats", "", time() - 3600, "/");
            $user_id = $user->getID();

            foreach ($booked_seats as $seat) {
                $check = DBHelper::executeQuery("SELECT * FROM ticket WHERE showing_id = ? AND seat_number = ?", [$showing_id, $seat]);
                if ($check->num_rows == 0) {
                    DBHelper::executeQuery("INSERT INTO ticket (showing_id, user_id, seat_number) VALUES (?, ?, ?)", [$showing_id, $user_id, $seat]);
                } else {
                    setError("Miejsce $seat jest już zajęte!");
                    header("Location: book_seats.php?id=$showing_id");
                    exit();
                }
            }
            echo '<script>alert("Pomyślnie zarezerwowano siedzenie/a!");</script>';
            header("Location: user_panel.php");
            exit();
        }
    ?>
</head>
<body>
    <div class="top-panel">
        <div class="top-box" style="justify-content: left;">
            <img src="images/logo.png" width="50%">
        </div>
        <div class="top-box" style="justify-content: right; width:40%;">
            <a href="logout.php" class="form-button">Wyloguj</a>
            <a href="index.php" class="form-button">Strona główna</a>
        </div>
    </div>
    
    <div class="panel">
        <div class="seat-section">
            <span class="booking-title">Wybierz miejsca</span>
            <div class='screen'>EKRAN</div>
            <?php 
                $showing = new Showing($showing_id);
                $seats_num = $showing->getSeatsNum();
                $occupied_seats = DBHelper::executeQuery("SELECT seat_number FROM ticket WHERE showing_id = ?", [$showing_id])->fetch_all(MYSQLI_ASSOC);
                if ($occupied_seats == null) $occupied_seats = [];
                $occupied_seats = array_column($occupied_seats, 'seat_number');

                for ($i = 1; $i <= $seats_num; $i++) {
                    if ($i % 10 == 1) echo "<div class='seat-row'>";
                    if (in_array($i, $occupied_seats)) {
                        echo "<div class='seat seat-unactive'>$i</div>";
                    } else {
                        echo "<div id='$i' class='seat' onclick='book($i)'>$i</div>";
                    }
                    if ($i % 10 == 0) echo "</div>";
                }
            ?>
        </div>
        <div class="seat-section">
            <span class="booking-title"><?php echo $showing->getMovie()->getTitle(); ?></span>
            <span class="booking-details">
                <?php 
                echo $showing->getMovie()->getCategory() . "&nbsp &nbsp &nbsp" . 
                $showing->getMovie()->getLength('h') . "&nbsp &nbsp &nbsp" . 
                $showing->getMovie()->getDate() . "&nbsp &nbsp &nbsp" . 
                $showing->getLanguage(); 
                ?>
            </span>
            <span class="booking-details" style="font-size: 19px"><?php echo $showing->getMovie()->getDescription(); ?></span>
            <span class="booking-title"><?php echo $showing->getDate() . " " . $showing->getTime(); ?></span><br><br><br>
            <span class="booking-details">Wybrane miejsce/a</span>
            <div class="seat-row" id="booked-seats">Nie wybrano żadnych miejsc</div>
            <div class="booking-button" onclick="redirectBooking()">Rezerwuj</div>
        </div>
    </div>
</body>
</html>