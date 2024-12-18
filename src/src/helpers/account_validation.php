<?php
    /* 
        Name: Shutirtha Roy
        Student ID: 105008711
        Course: COS80021 Web Application Development
        Function: This file contains all the account validations of register.php
        account_service.php. 
    */
    
    function hasUserEnteredCorrectInputs($first_name, $last_name, $email, $password, $confirmPassword, $phone) {
        if(!matchName(trim($first_name))) {
            return ['success' => false, 'errors' => ERROR_FIRST_NAME_REQUIRED];
        }

        if(!matchName(trim($last_name))) {
            return ['success' => false, 'errors' => ERROR_LAST_NAME_REQUIRED];
        }
        
        if(!matchEmail(trim($email))) {
            return ['success' => false, 'errors' => ERROR_EMAIL_INVALID];
        }

        if (!matchPassword($password, $confirmPassword)) {
            echo "$password, $confirmPassword";
            return ['success' => false, 'errors' => ERROR_PASSWORDS_DO_NOT_MATCH];
        }

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