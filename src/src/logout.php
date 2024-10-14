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

if(isset($_GET["isCustomer"])) {
    if(isset($_SESSION['customer_id'])) {
        if(isset($_SESSION['customer_id']) == "") {
            echo("Not Found");
        }

        $customerId = htmlspecialchars($_SESSION['customer_id']);
        echo("$customerId");
    } else {
        echo("Not Found");
    }

    session_unset();
    session_destroy();
}
?>