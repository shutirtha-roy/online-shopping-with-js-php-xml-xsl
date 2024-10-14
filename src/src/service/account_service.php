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
?>