<?php
session_start();

if(isset($_GET["isManager"])) {


    if(isset($_SESSION['mid'])) {
        if(isset($_SESSION['mid']) == "") {
            echo("Not Found");
        }

        $managerId = htmlspecialchars($_SESSION['mid']);
        echo("$managerId");
    } else {
        echo("Not Found");
    }
    
    session_unset();
    session_destroy();
}
?>