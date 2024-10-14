<?php
    /* 
        Name: Shutirtha Roy
        Student ID: 105008711
        Course: COS80021 Web Application Development
        Function: This file contains all the validations of 
        request_service.php. 
    */
    function hasCorrectItemInputs($itemName, $itemPrice, $itemQuantity, $itemDescription) {
        if(!matchEmptyItem($itemName)) {
            return ['success' => false, 'errors' => ERROR_ITEM_NAME_INVALID];
        }

        if(!is_numeric($itemPrice) || $itemPrice < 0) {
            return ['success' => false, 'errors' => ERROR_ITEM_PRICE_INVALID];
        }
        
        if(!is_numeric($itemQuantity) || $itemQuantity < 0) {
            return ['success' => false, 'errors' => ERROR_ITEM_QUANTITY_INVALID];
        }

        if(!matchEmptyItem($itemDescription)) {
            return ['success' => false, 'errors' => ERROR_ITEM_DESCRIPTION_INVALID];
        }

        return ['success' => true, 'errors' => ''];
    }

    
    function matchEmptyItem($value) {
        return !empty($value); 
    }
?>