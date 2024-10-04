<?php
    /* 
        Name: Shutirtha Roy
        Student ID: 105008711
        Course: COS80021 Web Application Development
        Function: This file contains all the account validations of 
        account_service.php. 
    */
    function hasUserEnteredCorrectInputs($first_name, $last_name, $email, $password, $confirmPassword, $phone) {
        if(!matchName($first_name)) {
            return ['success' => false, 'errors' => ERROR_FIRST_NAME_REQUIRED];
        }

        //echo "NAME IS CORRECT";

        if(!matchName($last_name)) {
            return ['success' => false, 'errors' => ERROR_LAST_NAME_REQUIRED];
        }

        //echo "LAST NAME IS CORRECT";
        
        if(!matchEmail($email)) {
            return ['success' => false, 'errors' => ERROR_EMAIL_INVALID];
        }

        //echo "EMAIL IS CORRECT";

        if (!matchPassword($password, $confirmPassword)) {
            echo "$password, $confirmPassword";
            return ['success' => false, 'errors' => ERROR_PASSWORDS_DO_NOT_MATCH];
        }

        // if (!matchPhoneNumber($phone)) {
        //     return ['success' => false, 'errors' => ERROR_PHONE_INVALID];
        // }

        return ['success' => true, 'errors' => ''];
    }

    function matchName($name) {
        return !empty($name);
    }

    function matchEmail($email) {
        return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    function matchPassword($password, $confirmPassword) {
        return true;
    }

    function matchPhoneNumber($phoneNumber) {
        if(empty($phoneNumber)) {
            return false;
        }

        if (strpos($phoneNumber, '+61') == 0
            && strlen($phoneNumber) === 12) {
            return true;
        }
        
        if (strpos($phoneNumber, '0') === 0
            && strlen($phoneNumber) === 10) {
            return true;
        }

        return false;
    }
?>