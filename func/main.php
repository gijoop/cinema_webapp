<?php
function formatDate($date, $format = 'd.m.Y'){
    $ddate = new DateTime($date);
    return $ddate->format($format);
}

function catchError(){
    if(isset($_SESSION['error'])){
        displayAlert($_SESSION['error']);
        unset($_SESSION['error']);
    }
}
function setError($error){
    try{
        session_start();
    }finally{
        unset($_SESSION['error']);
        $_SESSION['error'] = $error;
    }
}
function displayAlert($message){
    echo "
    <script>
        alert('$message');
    </script>
    ";
}