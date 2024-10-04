<?php
    /* 
        Name: Shutirtha Roy
        Student ID: 105008711
        Course: COS80021 Web Application Development
        Function: This file contains all the logics 
        account.php. The function registerUser is used to register the user and sends 
        a successful response if successful. Further, it generates customer number
        and also sets session of the user details.
    */ 

    function generateCustomerNumber($totalCustomer) {
        $customerNumber = CUSTOMER_NUMBER_INDEX. ($totalCustomer + 1);
        return $customerNumber;
    }

    // function hasLoginCustomer($dbConnect, $customerNumber) {
    //     if(doesCustomerExist($dbConnect, $email)) {
    //         return ['success' => false, 'errors' => USER_ALREADY_EXISTS];
    //     }

    //     return ['success' => true, 'errors' => ''];
    // }

    function prepareUserData($customer_id, $first_name, $last_name, $email, $password, $phone) {
        return [
            'customer_id' => $customer_id,
            'first_name' => $first_name,
            'surname' => $last_name,
            'email' => $email,
            'password' => $password,
            'phone' => $phone
        ];
    }

    function createCustomerXmlElement($doc, $parent, $elementName, $value) {
        $element = $doc->createElement($elementName);
        $parent->appendChild($element);
        $textNode = $doc->createTextNode(trim($value));
        $element->appendChild($textNode);
        return $element;
    }

    

    // function setRegistrationSession($email, $customerNumber, $name) {
    //     session_start();
    //     $_SESSION['loggedin'] = true;
    //     $_SESSION['email'] = $email;
    //     $_SESSION['customer_number'] = $customerNumber;
    //     $_SESSION['name'] = $name;
    //     $_SESSION['just_registered'] = true;
    // }

    // function setLoginSession($customerNumber, $name) {
    //     session_start();
    //     $_SESSION['name'] = $name;
    //     $_SESSION['loggedin'] = true;
    //     $_SESSION['customer_number'] = $customerNumber;
    // }

    // function loginUser($dbConnect, $customerNumber, $password) {
    //     if(!hasCustomerWithCustomerNumber($dbConnect, $customerNumber, $password)) {
    //         return ['success' => false, 'errors' => INVALID_PASSWORD];
    //     }

    //     $loginUser = hasLoginCustomer($dbConnect, $customerNumber, $password);
    //     if($loginUser) {
    //         $name = getCustomerNameFromCustomerId($dbConnect, $customerNumber);
    //         setLoginSession($customerNumber, $name);
    //         header("location: ../request_shipment/request-shipment.php");
    //     };
    // }
?>