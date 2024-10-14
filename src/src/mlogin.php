<?php
include 'constants/account-service-contants.php';
include 'helpers/account_validation.php';
include 'service/account_service.php';

session_start();

function containsManager($mid, $password) {
    $textfile = '../../data/manager.txt';
    
    if (!file_exists($textfile)) {
        echo("Your manager account is not registered.");
        return false; 
    }
    
    $managers = file($textfile);

    for($i=0; $i < count($managers); $i++) {
        $managerInfo = explode(",", $managers[$i]);
        $managerId = trim($managerInfo[0]);
        $managerPassword = trim($managerInfo[1]);

        if ($managerId == $mid && $managerPassword != $password) {
            echo "Invalid manager account password.";
            return false;
        }

        if ($managerId == $mid && $managerPassword == $password) {
            return true;
        }
    }
    
    echo("Your manager account is not registered.");
    return false; 
}

if(isset($_GET["mid"]) && isset($_GET["password"])) {
	$mid = $_GET["mid"];
	$password = $_GET["password"];

	if(containsManager($mid, $password)) {
        $_SESSION['mid'] = $mid;
        echo("Dear Manager $mid, you have successfully logged in!");
	}
}
?>