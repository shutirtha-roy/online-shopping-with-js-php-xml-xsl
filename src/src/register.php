<?php
include 'constants/account-service-contants.php';
include 'helpers/account_validation.php';
include 'service/account_service.php';

function hasUniqueEmail($email) {
    $xmlfile = '../../data/customer.xml';
    
    if (!file_exists($xmlfile)) {
        return true;
    }
    
    $doc = new DOMDocument();
    $doc->load($xmlfile);
    $customers = $doc->getElementsByTagName('customer');
    
    foreach ($customers as $customer) {
        $customerEmail = $customer->getElementsByTagName('email')->item(0)->nodeValue;
        if ($customerEmail === $email) {
            return false;
        }
    }
    
    return true;
}

function registerUser($first_name, $last_name, $email, $password, $confirmPassword, $phone) {
    $hasCorrectInput = hasUserEnteredCorrectInputs($first_name, $last_name, 
        $email, $password, $confirmPassword, $phone);
	
    if(!$hasCorrectInput['success']) {
        return false;
    }

    $hasCreatedCustomerXML = createXMLOfCustomer($first_name, $last_name, 
		$email, $password, $confirmPassword, $phone);

	return $hasCreatedCustomerXML;
}

function getTotalCustomerLength() {
	$xmlfile = '../../data/customer.xml';

	if (!file_exists($xmlfile)){
		return 0;
	}

	$doc = new DOMDocument();
	$doc->load($xmlfile);
	$customers = $doc->getElementsByTagName('customer');
	$totalCustomers = $customers->length;
	return $totalCustomers;
}

function createXMLOfCustomer($first_name, $last_name, $email, $password, $confirmPassword, $phone) {
	try {
		$xmlfile = '../../data/customer.xml';

		$doc = new DomDocument();
		
		if (!file_exists($xmlfile)){
			$customers = $doc->createElement('customers');
			$doc->appendChild($customers);
		}
		else {
			$doc->preserveWhiteSpace = FALSE; 
			$doc->load($xmlfile);  
		}

		$customers = $doc->getElementsByTagName('customers')->item(0);
		$customer = $doc->createElement('customer');
		$customers->appendChild($customer);

		$customer_id = generateCustomerNumber(getTotalCustomerLength());

		$customerData = prepareUserData($customer_id, $first_name, $last_name, $email, $password, $phone);

		foreach ($customerData as $key => $value) {
			if ($key == "phone" && $value == "") {
				continue;
			}

			createCustomerXmlElement($doc, $customer, $key, $value);
		}

		$doc->formatOutput = true;
		$customerXML = $doc->save($xmlfile);  
		chmod('../../data/customer.xml', 0777);

		return true;
	}
	catch (Exception $e) {
		return false;
	}
}

if(isset($_GET["fname"]) && isset($_GET["lname"]) && isset($_GET["email"]) 
	&& isset($_GET["password"]) && isset($_GET["confirm_password"]) && isset($_GET["phone"])) {
	$first_name = $_GET["fname"];
	$last_name = $_GET["lname"];
	$email = $_GET["email"];
	$password = $_GET["password"];
	$confirmPassword = $_GET["confirm_password"];
	$phone = $_GET["phone"];

	try{
		if(!hasUniqueEmail($email)) {
			echo("Your email is already registered.");
		} else {
			$isUserRegistered = registerUser($first_name, $last_name, 
			$email, $password, $confirmPassword, $phone);
	
		
			if ($isUserRegistered) {
				echo("Dear $first_name, you have successfully registered with your email: $email!");
			} 
	
			if (!$isUserRegistered) {
				echo("Invalid Validation");
			}
		}
	}
	catch (Exception $e) {
		echo($e);
	}
	
} else {
	echo ("The user could not be registered!");
}
?>