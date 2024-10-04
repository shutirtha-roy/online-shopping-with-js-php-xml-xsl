<?php
    /* 
        Name: Shutirtha Roy
        Student ID: 105008711
        Course: COS80021 Web Application Development
        Function: This file contains all the validations of 
        request_service.php. 
    */    
    
    function matchEmptyItem($value) {
        return !empty($value); 
    }

    function matchWeight($weight) {
        return is_numeric($weight) 
            && $weight >= 0 
            && $weight <= 20;
    }

    function isValidDate($day, $month) {
        if ($day < 0) {
            return false;
        }
    }
?>