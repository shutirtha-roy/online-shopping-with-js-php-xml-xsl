<?php
/*
    Name: Shutirtha Roy
    Student ID: 105008711
    Course: COS80021 Web Application Development
    Function: This file is used to login the customer with the email and password, by searching from the xml file. 
    If the customer is matched with the id and password then a success message is sent back to the client-side JavaScript.
*/

include 'constants/account-service-contants.php';
include 'helpers/account_validation.php';
include 'service/account_service.php';

session_start();

function containsCorrectCredentials($email, $password) {
    try{
        $xmlfile = '../../data/customer.xml';
    
        if (!file_exists($xmlfile)) {
            return false;
        }
        
        $doc = new DOMDocument();
        $doc->load($xmlfile);
        $customers = $doc->getElementsByTagName('customer');
        
        foreach ($customers as $customer) {
            $customerEmail = trim($customer->getElementsByTagName('email')->item(0)->nodeValue);
            $customerPassword = trim($customer->getElementsByTagName('password')->item(0)->nodeValue);

            if ($customerEmail == $email && $customerPassword == $password) {
                $customerId = trim($customer->getElementsByTagName('customer_id')->item(0)->nodeValue);
                return ['hasCorrectCredential' => true, 'customerId' => $customerId];
            }
        }
        
        return ['hasCorrectCredential' => false];
    }
    catch (Exception $e) {
        return ['hasCorrectCredential' => false];
    }
}

if(isset($_GET["email"]) && isset($_GET["password"])) {
	$email = $_GET["email"];
	$password = $_GET["password"];

    $userInfo = containsCorrectCredentials($email, $password);

	if($userInfo['hasCorrectCredential']) {
        $customerId = $userInfo['customerId'];
        $_SESSION['customer_id'] = $customerId;
        echo($customerId);
	} else {
        echo("Login Failed");
    }
}
?>