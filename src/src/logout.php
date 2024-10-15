<?php
/*
    Name: Shutirtha Roy
    Student ID: 105008711
    Course: COS80021 Web Application Development
    Function: This file is used to logout the customer or manager by at first checking the request sent from the javascript 
    what whether it is customer or manager. It then sends the session value back to the client if the session is set. If the session
    is set then the ID is sent back and the session is destroyed. 
*/

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